<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnomalyNotifierCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:anomaly {dte? : Date to generate anomaly notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create anomaly notification.';

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
            Fee::with('loan.debtor')
                ->where('limit_on', '<', $dt)
                ->orWhere('payment_on', '<', $dt)
                ->cursor()
                ->groupBy('loan.debtor.company_id')
                ->each(function ($fees, $company_id) use ($dt) {
                    $users = $this->getUsersToNotified($company_id);

                    if ($users->count() !== 0) {
                        $fees->groupBy('loan.debtor_id')->each(function ($fees) use ($dt, $company_id, $users) {
                            $debtor = $fees->first()->loan->debtor;
                            $overdue = $fees->where('payment_on', '<', $dt);
                            $late = $fees->where('payment_on', '>=', $dt);
                            $level = 2;

                            $text = "{$debtor->fullname} tiene";
                            if ($overdue->count() !== 0) {
                                $level = 3;
                                $plural = $overdue->count() === 1 ? '' : 's';

                                $text .= " {$overdue->count()} couta{$plural} vencida{$plural}";

                                if ($late->count() !== 0) {
                                    $text .= ' y';
                                }
                            }

                            if ($late->count() !== 0) {
                                $plural = $overdue->count() === 1 ? '' : 's';

                                $text .= " {$late->count()} couta{$plural} atrasada{$plural}";
                            }

                            /**
                             * @var \App\Models\Notification
                             */
                            $notification = Notification::create([
                                'company_id' => $company_id,
                                'level' => $level,
                                'text' => $text . '.',
                            ]);

                            $notification->users()->sync($users->pluck('user_id')->all());
                        });
                    }
                });
        });
    }

    protected function getUsersToNotified(int $company_id)
    {
        $default = collect();

        // Todo: set the correct users to identified
        return null_prevent_property(Company::find($company_id), 'users', $default) ?? $default;
    }
}
