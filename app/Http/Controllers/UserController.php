<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use App\Traits\PasswordScore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use GenericController, PasswordScore;

    private $relations = ['companies', 'permissions'];

    /**
     * function name that don't required Authentication
     *
     * @var string[] withoutAuth
     */
    protected $withoutAuth = ['avatar'];

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
        // Todo: Storage a image
        $rules = [
            'username' => ['required', 'alpha_num'],
            'password' => ['required', 'min:8'],
            'email' => ['required', 'email'],
            'fullname' => ['required', 'string'],
        ];

        return $this->change($request, $rules, new User(), true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
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
        // Todo: Storage a image
        $rules = [
            'username' => [new NotPresent],
            'old_password' => ['required_with:password'],
            'password' => ['required_with:old_password', 'min:8'],
            'email' => ['email'],
            'fullname' => ['string'],
        ];

        return $this->change($request, $rules, $user, false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return  $this->showOne($user);
    }

    public function usersMeShow()
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        if ($user === null) {
            // Todo: set message and model
            throw new ModelNotFoundException('');
        }

        $user->permissions;

        return $this->showOne($user);
    }

    public function changePassword(Request $request, User $user)
    {
        //
    }

    public function requestChangePassword(Request $request)
    {
        //
    }

    public function changePasswordConfirm(Request $request, User $user, string $token)
    {
        //
    }

    public function companiesUsersIndex(Company $company)
    {
        return $this->modelRelationIndex($company, 'users', $this->relations);
    }

    public function avatar(User $user)
    {
        // return response()->file(storage_path('resources' . DIRECTORY_SEPARATOR . $user->logo));
        // Todo: completed
        return response()->file(storage_path('uploads' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'no-user.png'));
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

    protected function otherValidations(Request $request, Model $model, bool $store): ?array
    {
        $errors = [];

        if ($request->has('password')) {
            if (!$store) {
                if (!$model->validatePassword($request->old_password)) {
                    $errors['old_password'] = ['Contraseña actual incorrecta.'];
                }
            }

            if ($request->password !== 'Password1*') {
                extract($this->getScore($request->password, $model->user ?? $request->username, $model->fullname ?? $request->fullname, $model->email ?? $request->email));
                // $warning, $suggestions, $score

                if ($score <= 2) {
                    $msg = 'La nueva contraseña es muy debil.' . ($suggestions !== null && $suggestions !== '' ? ' ' . $suggestions : '');
                    $errors['password'] = [$msg];
                }
            }
        }

        return $errors;
    }
}
