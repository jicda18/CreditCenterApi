<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'currency_id',
        'loan_amount',
        'bank_amount',
        'loan_balance',
        'bank_balance',
        'comment',
    ];
    protected $hidden = ['transaction_id'];

    protected $casts = [
        'transaction_id' => 'integer',
        'loan_id' => 'integer',
        'bank_id' => 'integer',
        'register_user_id' => 'integer',
        'currency_id' => 'integer',
        'was_at' => 'datetime',
        'amount' => 'float',
        'loan_amount' => 'float',
        'bank_amount' => 'float',
        'loan_balance' => 'float',
        'bank_balance' => 'float',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fees(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class, 'tbFeesTransactionsPivot', 'transaction_id', 'fee_id', 'transaction_id', 'fee_id')->withTimestamps()->withPivot(['payment']);
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

    public function authorized(Company $company, User $user): bool
    {
        return $company->company_id === $this->bank->company_id;
    }
}
