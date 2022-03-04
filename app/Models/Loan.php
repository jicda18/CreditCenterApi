<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends ApiModel
{
    const FIXED_INTEREST = 'fixed_interest';
    const INTEREST_CAPITAL = 'interest_capital';

    protected $table = 'tbLoans';
    protected $primaryKey = 'loan_id';
    protected $fillable = [
        'debtor_id',
        'currency_id',
        'start_on',
        'thanks_days',
        'frequency_id',
        'type',
        'interest',
        'amount',
        'number_fees',
    ];
    protected $hidden = ['loan_id'];

    protected $casts = [
        'loan_id' => 'integer',
        'debtor_id' => 'integer',
        'currency_id' => 'integer',
        'number_fees' => 'integer',
        'amount' => 'float',
        'balance' => 'float',
        'interest' => 'float',
        'start_on' => 'date',
        'thanks_days' => 'integer',
        'next_on' => 'date',
        'frequency_id' => 'integer',
    ];

    protected $attributes = [
        'balance' => 0,
        'number_fees' => 0,
        'status' => 'active',
    ];

    public function getFeeAmountAttribute()
    {
        return get_fee_amount($this->type, $this->amount, $this->interest, $this->number_fees);
    }

    public function getInterestBalanceAttribute()
    {
        return $this->fees()->whereNull('paid_at')->where('payment_on', '<=', Carbon::now())
            ->get()->sum('pending_interest');
    }

    public function getLateBalanceAttribute()
    {
        return $this->fees()->whereNull('paid_at')->where('payment_on', '<=', Carbon::now())
            ->get()->sum('pending_late');
    }

    public function getPayBalanceAttribute()
    {
        return $this->fees()->whereNull('paid_at')->where('payment_on', '<=', Carbon::now())
            ->get()->sum('pending_capital');
    }

    public function getPendingBalanceAttribute()
    {
        return $this->fees()->whereNull('paid_at')->where('payment_on', '<=', Carbon::now())
            ->get()->sum('pending');
    }

    public function getTotalAttribute()
    {
        return $this->balance + $this->interest_balance + $this->late_balance;
    }

    public function setBalanceAttribute($balance)
    {
        $this->attributes['balance'] = $balance < 0  ? 0 : $balance;
    }

    /**
     * Get all of the transactions for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'loan_id', 'loan_id');
    }

    /**
     * Get the currency that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    /**
     * Get the debtor that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Debtor::class, 'debtor_id', 'debtor_id');
    }

    /**
     * Get all of the fees for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'loan_id', 'loan_id');
    }

    /**
     * Get the frequency that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function frequency(): BelongsTo
    {
        return $this->belongsTo(Frequency::class, 'frequency_id', 'frequency_id');
    }
}
