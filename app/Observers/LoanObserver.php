<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\loan;

class LoanObserver
{

    /**
     * Handle the loan "creating" event.
     *
     * @param  \App\Models\loan  $loan
     * @return void
     */
    public function creating(loan $loan)
    {
        $loan->next_on = $loan->start_on;
    }

    /**
     * Handle the loan "updating" event.
     *
     * @param  \App\Models\loan  $loan
     * @return void
     */
    public function updating(loan $loan)
    {
        if ($loan->balance <= 0) {
            $loan->status = 'paid';
        }
    }

    /**
     * Handle the loan "updated" event.
     *
     * @param  \App\Models\loan  $loan
     * @return void
     */
    public function updated(loan $loan)
    {
        if ($loan->fees()->count() === 0) {
            // Create first fee
            Fee::makeFromLoan($loan);
        }
    }
}
