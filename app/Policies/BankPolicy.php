<?php

namespace App\Policies;

use App\Models\Bank;
use App\Models\User;

class BankPolicy extends ApiPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function view(User $user, Bank $bank)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function update(User $user, Bank $bank)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $bank
     * @return mixed
     */
    public function delete(User $user, Bank $bank)
    {
        //
    }
}
