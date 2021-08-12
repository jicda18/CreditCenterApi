<?php

namespace App\Policies;

use App\Models\Frequency;
use App\Models\User;

class FrequencyPolicy extends ApiPolicy
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
     * @param  \App\Models\Frequency  $frequency
     * @return mixed
     */
    public function view(User $user, Frequency $frequency)
    {
        //
    }
}
