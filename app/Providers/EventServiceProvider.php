<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Fee;
use App\Models\Loan;
use App\Models\Transaction;
use App\Observers\CurrencyObserver;
use App\Observers\FeeObserver;
use App\Observers\LoanObserver;
use App\Observers\TransactionObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Currency::observe(CurrencyObserver::class);
        Fee::observe(FeeObserver::class);
        Transaction::observe(TransactionObserver::class);
        Loan::observe(LoanObserver::class);
    }
}
