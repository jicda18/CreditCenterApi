<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fee extends ApiModel
{
    protected $table = 'tbFees';
    protected $primaryKey = 'fee_id';
    protected $fillable = [
        'loan_id',
        'payment_on',
        'amount',
        'interest',
        'capital',
        'limit_on',
        'paid_at',
        'extra',
    ];
    protected $hidden = ['fee_id'];

    protected $casts = [
        'fee_id' => 'integer',
        'loan_id' => 'integer',
        'payment_on' => 'date',
        'amount' => 'float',
        'interest' => 'float',
        'capital' => 'float',
        'late_payment' => 'float',
        'limit_on' => 'date',
        'paid_at' => 'datetime',
        'extra' => 'boolean',
    ];

    protected $attributes = [
        'late_payment' => 0,
    ];

    public function getBalanceAttribute()
    {
        return round($this->interest + $this->capital + $this->late_payment, precision);
    }

    public function getPaidInterestAttribute()
    {
        return round($this->transactions->sum(function (Transaction $transaction) {
            return round(null_prevent_property($transaction->pivot, 'interest'), precision);
        }), precision);
    }

    public function getPaidCapitalAttribute()
    {
        return round($this->transactions->sum(function (Transaction $transaction) {
            return round(null_prevent_property($transaction->pivot, 'capital'), precision);
        }), precision);
    }

    public function getPaidLatePaymentAttribute()
    {
        return round($this->transactions->sum(function (Transaction $transaction) {
            return round(null_prevent_property($transaction->pivot, 'late_payment'), precision);
        }), precision);
    }

    public function getPaidAttribute()
    {
        return round($this->paid_interest + $this->paid_capital + $this->paid_late_payment, precision);
    }

    public function getPendingInterestAttribute()
    {
        return round($this->interest - $this->paid_interest, precision);
    }

    public function getPendingCapitalAttribute()
    {
        return round($this->capital - $this->paid_capital, precision);
    }

    public function getPendingLatePaymentAttribute()
    {
        return round($this->late_payment - $this->paid_late_payment, precision);
    }

    public function getPendingAttribute()
    {
        return round($this->balance - $this->paid, precision);
    }

    /**
     * Get the loan that owns the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }

    /**
     * Get all of the fees for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class, 'tbFeesTransactionsPivot', 'fee_id', 'transaction_id', 'fee_id', 'transaction_id')->withTimestamps()->withPivot(['interest', 'capital', 'late_payment']);
    }

    static public function makeFromLoan(Loan $loan): ?Fee
    {
        /**
         * @var \Carbon\Carbon
         */
        $payment_on = $loan->next_on;

        // validate fee doesn't exist
        if (Fee::where('loan_id', '=', $loan->loan_id)->where('payment_on', '=', $payment_on->isoFormat('YYYY-MM-DD'))->count() > 0) {
            return null;
        }

        $total_fees = $loan->number_fees;
        $count_fees = $loan->fees()->where('payment_on', '<', $payment_on)->get();

        $balance = $loan->balance - $count_fees->sum('capital');

        if ($balance <= 0) {
            return null;
        }

        $amount = get_fee_amount($loan->type, $balance, $loan->interest, $total_fees - $count_fees->count());
        $interest = apply_interest($balance, $loan->interest);
        return Fee::create([
            'loan_id' => $loan->loan_id,
            'payment_on' => $payment_on,
            'limit_on' => $payment_on->copy()->addDays($loan->thanks_days),
            'amount' => $amount,
            'interest' => $interest,
            'capital' => round($amount - $interest, 2),
        ]);
    }
}
