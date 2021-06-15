<?php

namespace App\Console\Commands;

use App\Models\Fee;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FeeGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fee:generate {dte? : Date to generate new fee}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the fees for a gave date.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dte = $this->argument('dte') ?? date('Y-m-d');
        $dt = new Carbon(strtotime($dte));

        DB::transaction(function () use ($dt) {
            Loan::where('next_on', '=', $dt)->cursor()->each(function (Loan $loan) use ($dt) {
                Fee::create([
                    'loan_id' => $loan->loan_id,
                    'payment_on' => $dt,
                    'amount' => $loan->fee_amount,
                    'limit_on' => $dt->copy()->addDays($loan->thanks_days),
                ]);

                /**
                 * @var \App\Models\Frequency
                 */
                $frequency = $loan->frequency;
                $loan->next_on = $frequency->nextDate($dt);
                $loan->save();
            });
        });
    }
}
