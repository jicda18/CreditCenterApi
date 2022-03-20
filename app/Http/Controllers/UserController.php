<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use App\Models\User;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use App\Traits\PasswordScore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use GenericController, PasswordScore;

    private $relations = ['roles', 'permissions'];

    /**
     * function name that don't required Authentication
     *
     * @var string[] withoutAuth
     */
    protected $withoutAuth = ['avatar', 'requestChangePassword', 'changePasswordConfirm', 'revokeChangePassword'];

    public function __construct()
    {
        $this->resource = UserResource::class;
        $this->default_key_sort = 'user_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        required_permission('user.view');

        $query = $this->includeRelations(User::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        required_permission('user.store');

        $rules = [
            'username' => ['required', 'alpha_num'],
            'password' => ['required', 'min:7'],
            'avatar' => ['file', 'image', 'mimes:jpg,png,jpeg'],
            'email' => ['required', 'email'],
            'fullname' => ['required', 'string'],
        ];

        return $this->change($request, $rules, new User(), true, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        required_permission('user.view');

        return  $this->showOne($user, $this->relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        required_permission('user.update');

        $rules = [
            'username' => [new NotPresent],
            'avatar' => ['file', 'image', 'mimes:jpg,png,jpeg'],
            'password' => ['required_with:old_password', 'min:8'],
            'email' => ['email'],
            'fullname' => ['string'],
        ];

        return $this->change($request, $rules, $user, false, $this->relations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        required_permission('user.delete');

        $user->delete();

        return  $this->showOne($user, []);
    }

    public function usersMeShow()
    {
        $user = $this->authValidate();

        return $this->showOne($user, $this->relations);
    }

    public function usersMeUpdate(Request $request)
    {
        $user = $this->authValidate();

        $rules = [
            'username' => [new NotPresent],
            'old_password' => ['required_with:password'],
            'password' => ['required_with:old_password', 'min:7'],
            'avatar' => ['file', 'image', 'mimes:jpg,png,jpeg'],
            'email' => ['email'],
            'fullname' => ['string'],
        ];

        return $this->change($request, $rules, $user, false, $this->relations);
    }

    public function requestChangePassword(Request $request)
    {
        $rules = [
            'username' => ['required', 'string'],
        ];

        $this->validate($request, $rules);

        $user = User::where('username', '=', $request->username)->orWhere('email', '=', $request->username)->first();

        if ($user === null) {
            throw ValidationException::withMessages([
                'username' => ['El usuario o email proporcionado no existe.'],
            ]);
        }

        // Limite de recuperación de contraseña
        $count = $user->resets()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->count();
        if ($count >= env('RECOVERY_LIMIT', 5)) {
            throw ValidationException::withMessages([
                'reset' => ['Excedio el limite de intento permitido del dia.'],
            ]);
        }

        $fails = $user->resets()->whereBetween('failed_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->count();
        if ($fails >= env('FAILS_LIMIT', 3)) {
            throw ValidationException::withMessages([
                'reset' => ['Excedio el limite de intento permitido del dia.'],
            ]);
        }

        $user->resets()->whereNull('completed_at')
            ->whereNull('failed_at')
            ->whereNull('revoked_at')
            ->where('expired_at', '>=', Carbon::now())
            ->update(['revoked_at' => Carbon::now()]);

        $token = generateRandomString(8);
        $reset = PasswordReset::create([
            'user_id' => $user->user_id,
            'email' => $user->email,
            'token' => $token,
            'expired_at' => Carbon::now()->addMinutes(30),
            'created_ip' => request_ip(),
            'old_password' => $user->password,
        ]);

        return response()->json([
            'data' => [
                'key' => $user->uuid,
                'token' => $reset->uuid,
                'email' => $reset->email,
            ],
        ]);
    }

    public function revokeChangePassword(User $user, PasswordReset $reset)
    {
        $reset->expired();

        $reset->used();

        $reset->validateUser($user->user_id);

        $reset->revoked_at = Carbon::now();

        $reset->save();

        return response()->json([
            'data' => ['msg' => 'El codigo de recuperación fue revocado.'],
        ], 200);
    }

    public function changePasswordConfirm(Request $request, User $user, PasswordReset $reset)
    {
        $rules = [
            'password' => ['required', 'min:7'],
            'code' => ['required'],
        ];

        $this->validate($request, $rules);

        try {
            $reset->validate($user->user_id, $request->code);

            $user->password = $request->password;

            $user->save();

            $reset->completed_at = Carbon::now();

            $reset->save();
        } catch (\Throwable $th) {
            $reset->failed_at = Carbon::now();

            $reset->save();

            throw $th;
        }

        return response()->json([
            'data' => ['msg' => 'Contraseña Cambiada.'],
        ], 200);
    }

    public function avatar(User $user)
    {
        if ($user->avatar !== null) {
            return response()->file(User::get_avatar_directory($user->avatar));
        }

        return response()->file(User::get_avatar_directory('no-user.png'));
    }

    protected function otherValidations(Request $request, Model $model, bool $store): ?array
    {
        $errors = [];

        if ($request->has('password')) {
            if ($request->has('old_password')) {
                if (!$model->validatePassword($request->old_password)) {
                    $errors['old_password'] = ['Contraseña actual incorrecta.'];
                }
            }

            if ($request->password !== 'Password1*') {
                extract($this->getScore($request->password, $model->username ?? $request->username, $model->fullname ?? $request->fullname, $model->email ?? $request->email));

                if ($score < 2) {
                    $msg = 'La nueva contraseña es muy debil.' . ($suggestions !== null && $suggestions !== '' ? ' ' . $suggestions : '');
                    $errors['password'] = [$msg];
                }
            }
        }

        return $errors;
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'fullname' => $request->fullname,
            ];
        }

        return $request->only(['username', 'password', 'email', 'fullname']);
    }

    protected function beforeToSave(Request $request, Model $model, bool $store): Model
    {
        if ($request->has('avatar')) {
            $file = $request->file('avatar');
            $name = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(User::get_avatar_directory(), $name);

            if ($model->avatar !== null && $model->avatar !== '') {
                File::delete(User::get_avatar_directory($model->avatar));
            }

            $model->avatar = $name;
        }

        return $model;
    }

    protected function afterToSave(Request $request, Model $model, bool $store): Model
    {
        $avatar = $model->getOriginal('avatar');

        if ($avatar != null && $avatar !== '' && $avatar !== $model->avatar) {
            File::delete(User::get_avatar_directory($avatar));
        }

        return $model;
    }

    private function authValidate(): User
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        if ($user === null) {
            throw new ModelNotFoundException('Can\'t verify who you are.');
        }

        return $user;
    }
}
