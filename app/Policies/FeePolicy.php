<?php

namespace App\Policies;

use App\Models\Fee;
use App\Models\User;

class FeePolicy extends ApiPolicy
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
     * @param  \App\Models\Fee  $fee
     * @return mixed
     */
    public function view(User $user, Fee $fee)
    {
        //
    }
}
