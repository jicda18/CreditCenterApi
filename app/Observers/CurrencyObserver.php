<?php

namespace App\Observers;

use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class CurrencyObserver
{
    /**
     * Handle the Currency "created" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function updating(Currency $currency)
    {
        $originalDefault = $currency->getOriginal('default');

        if ($originalDefault === true) {
            $currency->default = true;
        }
    }

    /**
     * Handle the Currency "updated" event.
     *
     * @param  \App\Models\Currency  $currency
     * @return void
     */
    public function updated(Currency $currency)
    {
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
    }
}
