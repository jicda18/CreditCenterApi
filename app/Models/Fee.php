<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends ApiModel
{
    protected $table = 'tbFees';
    protected $primaryKey = 'fee_id';
    protected $fillable = [
        'loan_id',
        'payment_on',
        'amount',
        'limit_on',
        'paid_at',
        'transaction_id',
    ];
    protected $hidden = ['fee_id'];

    protected $casts = [
        'fee_id' => 'integer',
        'loan_id' => 'integer',
        'payment_on' => 'date',
        'amount' => 'float',
        'limit_on' => 'date',
        'paid_at' => 'datetime',
        'transaction_id' => 'integer',
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
     * Get the transaction that owns the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
