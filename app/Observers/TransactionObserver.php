<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\Loan;
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
        // Set paid datetime
        $transaction->was_at = Carbon::now();

        if ($transaction->is_cash) {
            // if cash doesn't affect bank balance
            $transaction->bank_id = null;
        }

        /**
         * @var \App\Models\Currency
         */
        $currency = $transaction->currency;
        if ($transaction->loan !== null &&  $transaction->loan_amount === null) {
            /**
             * @var \App\Models\Loan
             */
            $loan = $transaction->loan;
            // Convert to loan currency
            $transaction->loan_amount = $currency->convertTo($loan->currency, $transaction->amount);
        }

        if ($transaction->bank_id !== null && $transaction->bank_amount === null) {
            /**
             * @var \App\Models\Bank
             */
            $bank = $transaction->bank;
            // Convert to bank currency
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
            // if a payment then update fees
            if ($transaction->amount < 0) {
                $amount = -$transaction->loan_amount;

                if ($transaction->type === 'capital') {
                    $relations = $this->capital($loan, $amount);
                } elseif ($transaction->type === 'total') {
                    $relations = $this->total($loan, $amount);
                } else {
                    $relations = $this->regular($loan, $amount);
                }

                // update balance
                foreach ($relations as $key => $relation) {
                    $loan->balance -= $relation['capital'];
                }

                // attach fees to transaction
                $transaction->fees()->syncWithoutDetaching($relations);

                $transaction->fees->each(function (Fee $fee) {
                    $fee->paid_at = Carbon::now();
                    $fee->save();
                });
            } else {
                $loan->balance = $transaction->loan_amount;
            }

            $loan->save();
            $transaction->loan_balance = $loan->balance;
        }

        $transaction->save();
    }

    private function total(Loan $loan, float $amount)
    {
        $loan->fees()->whereNull('paid_at')->where('payment_on', '>', Carbon::now())->delete();

        return $this->processFees($loan, $amount);

        // $loan->balance = 0;
        // $loan->save();

        // return $relation;
    }

    private function regular(Loan $loan, float $amount)
    {
        return $this->processFees($loan, $amount);
    }

    private function capital(Loan $loan, float $amount)
    {
        $interest = 0;
        $capital = $amount;
        $late_payment = 0;

        $fee = Fee::create([
            'loan_id' => $loan->loan_id,
            'payment_on' => Carbon::now(),
            'limit_on' => Carbon::now(),
            'amount' => $capital,
            'interest' => $interest,
            'capital' => $capital,
            'paid_at' => Carbon::now(),
            'extra' => true,
        ]);

        return [
            $fee->fee_id => [
                'interest' => $interest,
                'capital' => $capital,
                'late_payment' => $late_payment,
            ],
        ];
    }

    /**
     * @return \App\Models\Fee[]
     */
    private function processFees(Loan $loan, float $amount)
    {
        /**
         * @var \App\Models\Fee[]
         */
        $fees = $loan->fees()->whereNull('paid_at')->orderBy('payment_on')->get()->all();

        $relation = [];
        $i = 0;
        while ($amount > 0) {
            if ($i < count($fees)) {
                $fee = $fees[$i];

                $interest = min($fee->pending_interest, $amount);
                $amount = round($amount - $interest, precision);

                $late_payment = min($fee->pending_late_payment, $amount);
                $amount = round($amount - $late_payment, precision);

                $capital = min($fee->pending_capital, $amount);
                $amount = round($amount - $capital, precision);

                $relation[$fee->fee_id] = [
                    'interest' => $interest,
                    'capital' => $capital,
                    'late_payment' => $late_payment,
                ];
            } else {
                $interest = 0;
                $capital = $amount;
                $late_payment = 0;

                $fee = Fee::create([
                    'loan_id' => $loan->loan_id,
                    'payment_on' => Carbon::now(),
                    'limit_on' => Carbon::now(),
                    'amount' => $capital,
                    'interest' => $interest,
                    'capital' => $capital,
                    'paid_at' => Carbon::now(),
                    'extra' => true,
                ]);

                $amount -= $capital;

                $relation[$fee->fee_id] = [
                    'interest' => $interest,
                    'capital' => $capital,
                    'late_payment' => $late_payment,
                ];
            }

            $i++;
        }

        return $relation;
    }
}
