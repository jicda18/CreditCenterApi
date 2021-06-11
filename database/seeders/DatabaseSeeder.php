<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Business;
use App\Models\Debtor;
use App\Models\Fee;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('migrate:data', ['--fresh' => true]);

        $debtorCount = 8;

        Notification::factory()->count(6)->create();
        Bank::factory()->count(3)->create();
        Guarantor::factory()->count($debtorCount)->create();
        Business::factory()->count($debtorCount)->create();
        Debtor::factory()->count($debtorCount)->create();
        Loan::factory()->count($debtorCount)->create();
        Transaction::factory()->count($debtorCount)->create();
        Fee::factory()->count($debtorCount)->create();
    }
}
