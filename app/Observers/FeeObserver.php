<?php

namespace App\Observers;

use App\Models\Fee;
use Carbon\Carbon;

class FeeObserver
{
    /**
     * Handle the Fee "created" event.
     *
     * @param  \App\Models\Fee  $fee
     * @return void
     */
    public function creating(Fee $fee)
    {
        $fee->balance = $fee->amount;
    }

    /**
     * Handle the Fee "updated" event.
     *
     * @param  \App\Models\Fee  $fee
     * @return void
     */
    public function updating(Fee $fee)
    {
        if ($fee->balance <= 0) {
            $fee->balance = 0;
            $fee->paid_at = Carbon::now();
        }
    }
}
