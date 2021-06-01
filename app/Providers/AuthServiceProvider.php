<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::hashClientSecrets();

        // Todo: move message to centered file
        Gate::define('admin-required', function (User $user) {
            return $user->isAdmin() ? Response::allow() : Response::deny('No tiene pivilegios para realizar esta acción.');
        });

        Gate::define('required-permission', function (User $user, int $permission) {
            return $user->permissions->pluck('permission_id')->contains($permission) ? Response::allow() : Response::deny('No tiene pivilegios para realizar esta acción.');
        });

        Passport::tokensExpireIn(Carbon::now()->addHours(24));
        Passport::refreshTokensExpireIn(Carbon::now()->addHours(36));
    }
}
