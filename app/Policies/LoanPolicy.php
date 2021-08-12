<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy extends ApiPolicy
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
     * @param  \App\Models\Loan  $loan
     * @return mixed
     */
    public function view(User $user, Loan $loan)
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
     * @param  \App\Models\Loan  $loan
     * @return mixed
     */
    public function update(User $user, Loan $loan)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan  $loan
     * @return mixed
     */
    public function delete(User $user, Loan $loan)
    {
        //
    }
}
