<?php

namespace App\Observers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionObserver
{
    /**
     * Handle the Transaction "creating" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function creating(Transaction $transaction)
    {
        $transaction->was_at = Carbon::now();

        /**
         * @var \App\Models\Currency
         */
        $currency = $transaction->currency;
        if ($transaction->loan_amount === null) {
            /**
             * @var \App\Models\Loan
             */
            $loan = $transaction->loan;
            $transaction->loan_amount = $currency->convertTo($loan->currency, $transaction->amount);
        }

        if ($transaction->bank_amount === null) {
            /**
             * @var \App\Models\Bank
             */
            $bank = $transaction->bank;
            $transaction->bank_amount = -$currency->convertTo($bank->currency, $transaction->amount);
        }
    }

    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        // Update Bank balance
        /**
         * @var \App\Models\Bank
         */
        $bank = $transaction->bank;
        if ($bank !== null) {
            $bank->balance += $transaction->bank_amount;
            $bank->save();

            $transaction->bank_balance = $bank->balance;
        }

        // Update Loan
        /**
         * @var \App\Models\Loan
         */
        $loan = $transaction->loan;
        if ($loan !== null) {
            $loan->balance += $transaction->loan_amount;
            $loan->save();

            $transaction->loan_balance = $loan->balance;

            // If a payment
            if ($transaction->loan_amount < 0) {
                // Update Fees
                $balance = $transaction->loan_amount;
                while ($balance > 0) {
                    /**
                     * @var \App\Models\Fee
                     */
                    $fee = $loan->fees()->where('balance', '>', '0')->orderDesc('payment_on')->first();
                    $payment = min($balance, $fee->balance);
                    $fee->balance -= $payment;
                    $fee->save();

                    $fee->transactions()->syncWithoutDetaching([$transaction->transaction_id => ['payment' => $payment]]);

                    $balance -= $payment;
                }
            }
        }

        $transaction->save();
    }
}
