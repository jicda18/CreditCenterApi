<?php

namespace App\Console\Commands;

use App\Models\Debtor;
use App\Models\Fee;
use App\Models\Notification;
use App\Models\User;
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
                ->groupBy('loan.debtor_id')
                ->each(function ($fees, $debtor_id) use ($dt) {
                    $users = $this->getUsersToNotified();

                    if ($users->count() !== 0) {
                        $debtor = Debtor::find($debtor_id);
                        $overdue = $fees->where('limit_on', '<', $dt);
                        $late = $fees->where('limit_on', '>=', $dt);
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
                            $plural = $late->count() === 1 ? '' : 's';

                            $text .= " {$late->count()} couta{$plural} atrasada{$plural}";
                        }

                        /**
                         * @var \App\Models\Notification
                         */
                        $notification = Notification::create([
                            'level' => $level,
                            'text' => $text . '.',
                        ]);

                        $notification->users()->sync($users->pluck('user_id')->all());
                    }
                });
        });
    }

    protected function getUsersToNotified()
    {
        $default = collect();

        // Todo: set the correct users to identified
        return User::all();
    }
}
