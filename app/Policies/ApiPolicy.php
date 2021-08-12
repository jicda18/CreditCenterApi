<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class ApiPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return $this->allow();
        }
    }

    protected function hasPermissions(Company $company, User $user, array $required): bool
    {
        return $user->permissions()->where('company_id', '=', $company->company_id)->whereIn('permission_id', $required)->count() === count($required);
    }
}
