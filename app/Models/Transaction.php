<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends ApiModel
{
    protected $table = 'tbTransactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'loan_id',
        'bank_id',
        'register_user_id',
        'was_at',
        'amount',
        'comment',
    ];
    protected $hidden = ['transaction_id'];

    protected $casts = [
        'transaction_id' => 'integer',
        'loan_id' => 'integer',
        'bank_id' => 'integer',
        'register_user_id' => 'integer',
        'was_at' => 'datetime',
        'amount' => 'float',
    ];

    /**
     * Get the register that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function register(): BelongsTo
    {
        return $this->belongsTo(User::class, 'register_user_id', 'user_id');
    }

    /**
     * Get the loan that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }

    /**
     * Get the bank that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    /**
     * Get all of the fees for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'transaction_id', 'transaction_id');
    }
}
