<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends ApiModel
{
    protected $table = 'tbLoans';
    protected $primaryKey = 'loan_id';
    protected $fillable = [
        'debtor_id',
        'currency_id',
        'fee_amount',
        'start_on',
        'thanks_days',
        'next_on',
        'frequency_id',
        'status',
    ];
    protected $hidden = ['loan_id'];

    protected $casts = [
        'loan_id' => 'integer',
        'debtor_id' => 'integer',
        'currency_id' => 'integer',
        'fee_amount' => 'float',
        'start_on' => 'date',
        'thanks_days' => 'integer',
        'next_on' => 'date',
        'frequency_id' => 'integer',
    ];

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
