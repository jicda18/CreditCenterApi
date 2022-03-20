<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frequency extends ApiModel
{
    protected $table = 'tbFrequencies';
    protected $primaryKey = 'frequency_id';
    protected $fillable = [
        'function_name',
        'label',
    ];
    protected $hidden = ['frequency_id'];

    protected $casts = [
        'frequency_id' => 'integer',
    ];

    public function nextDate(Carbon $dte): Carbon
    {
        $functionName = $this->function_name;

        return $this->{$functionName}($dte);
    }

    public function day(Carbon $dte): string
    {
        $functionName = $this->function_name . 'Day';

        return $this->{$functionName}($dte);
    }

    /**
     * Get all of the loans for the Frequency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'frequency_id', 'frequency_id');
    }

    /**
     * Return the next daily date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function daily(Carbon $dte): Carbon
    {
        return $dte->copy()->addDay();
    }

    /**
     * Return the next weekly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function weekly(Carbon $dte): Carbon
    {
        return $dte->copy()->addWeek();
    }

    /**
     * Return the next daily date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function biweekly(Carbon $dte): Carbon
    {
        // If february
        if ($dte->month === 2) {
            return $dte->day <= 15 ? $dte->copy()->endOfMonth() : $dte->copy()->addMonth()->addDays(15 - $dte->day);
        }

        return $dte->day <= 15 ? $dte->copy()->addDays(30 - $dte->day) : $dte->copy()->addMonth()->addDays(15 - $dte->day);
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function monthly(Carbon $dte): Carbon
    {
        return $dte->copy()->addMonth();
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function trimonthly(Carbon $dte): Carbon
    {
        return $dte->copy()->addMonths(3);
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function biannual(Carbon $dte): Carbon
    {
        return $dte->copy()->addMonths(6);
    }

    /**
     * Return the next yearly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  \Carbon\Carbon
     */
    private function yearly(Carbon $dte): Carbon
    {
        return $dte->copy()->addYear();
    }

    /**
     * Return the next daily date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function dailyDay(Carbon $dte): string
    {
        return $this->label;
    }

    /**
     * Return the next weekly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function weeklyDay(Carbon $dte): string
    {
        $days = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miercoles',
            'Jueves',
            'Viernes',
            'Sabados',
        ];

        return $days[$dte->dayOfWeek];
    }

    /**
     * Return the next daily date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function biweeklyDay(Carbon $dte): string
    {
        return $this->label;
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function monthlyDay(Carbon $dte): string
    {
        return "{$dte->day}";
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function trimonthlyDay(Carbon $dte): string
    {
        return $this->label;
    }

    /**
     * Return the next monthly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function biannualDay(Carbon $dte): string
    {
        return $this->label;
    }

    /**
     * Return the next yearly date.
     *
     * @param   \Carbon\Carbon   $dte
     * @return  string
     */
    private function yearlyDay(Carbon $dte): string
    {
        $moth = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];

        return "{$dte->day} {$moth[$dte->month - 1]}";
    }
}
