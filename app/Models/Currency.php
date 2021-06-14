<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();

        static::updating(function (Currency $currency) {
            $originalDefault = $currency->getOriginal('default');

            if ($originalDefault === true) {
                $currency->default = true;
            }
        });

        static::updated(function (Currency $currency) {
            $originalDefault = $currency->getOriginal('default');

            if ($currency->default === $originalDefault) {
                return;
            }

            if ($currency->default === false) {
                return;
            }

            DB::table('tbCurrencies')->update(['exchange_value' => DB::raw("exchange_value/{$currency->exchange_value}"), 'default' => false]);

            DB::table('tbCurrencies')->where('currency_id', '=', $currency->currency_id)->update(['default' => true, 'exchange_value' => 1]);

            $currency->refresh();
        });
    }

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

    public function convertTo(Currency $currency, float $value = 1): float
    {
        return round($value * ($this->exchange_value / $currency->exchange_value), 4);
    }
}
