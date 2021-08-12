<?php

namespace App\Policies;

use App\Models\Debtor;
use App\Models\User;

class DebtorPolicy extends ApiPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Debtor  $debtor
     * @return mixed
     */
    public function view(User $user, Debtor $debtor)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Debtor  $debtor
     * @return mixed
     */
    public function update(User $user, Debtor $debtor)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Debtor  $debtor
     * @return mixed
     */
    public function delete(User $user, Debtor $debtor)
    {
        //
    }
}
