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
        'balance',
        'limit_on',
        'paid_at',
    ];
    protected $hidden = ['fee_id'];

    protected $casts = [
        'fee_id' => 'integer',
        'loan_id' => 'integer',
        'payment_on' => 'date',
        'amount' => 'float',
        'balance' => 'float',
        'limit_on' => 'date',
        'paid_at' => 'datetime',
    ];

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
        return $this->belongsToMany(Transaction::class, 'tbFeesTransactionsPivot', 'fee_id', 'transaction_id', 'fee_id', 'transaction_id')->withTimestamps()->withPivot(['payment']);
    }
}
