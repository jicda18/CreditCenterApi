<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends ApiModel
{
    protected $table = 'tbCurrencies';
    protected $primaryKey = 'currency_id';
    protected $fillable = [
        'name',
        'symbol',
        'default',
        'exchange_value',
    ];
    protected $hidden = ['currency_id'];

    protected $casts = [
        'currency_id' => 'integer',
        'default' => 'boolean',
        'exchange_value' => 'float',
    ];

    /**
     * Get all of the banks for the Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class, 'currency_id', 'currency_id');
    }

    /**
     * Get all of the loans for the Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'currency_id', 'currency_id');
    }

    // Todo: add function tu convert to default and to convert to other
    // Mutator to set to default
}
