<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\Loan;
use Carbon\Carbon;

class FeeObserver
{
    /**
     * Handle the Fee "updating" event.
     *
     * @param  \App\Models\Fee  $fee
     * @return void
     */
    public function updating(Fee $fee)
    {
        if ($fee->paid_at !== null && $fee->pending > 0) {
            $fee->paid_at = null;
        }
    }
}
