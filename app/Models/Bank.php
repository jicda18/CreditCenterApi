<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends ApiModel
{
    protected $table = 'tbBanks';
    protected $primaryKey = 'bank_id';
    protected $fillable = [
        'company_id',
        'currency_id',
        'bank_name',
        'account_number',
        'alias',
        'balance',
    ];
    protected $hidden = ['bank_id', 'company_id'];

    protected $casts = [
        'bank_id' => 'integer',
        'company_id' => 'integer',
        'currency_id' => 'integer',
        'balance' => 'float',
    ];

    /**
     * Get the company that owns the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Get the currency that owns the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    /**
     * Get all of the transactions for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Bank::class, 'bank_id', 'bank_id');
    }
}
