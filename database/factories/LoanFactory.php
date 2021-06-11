<?php

namespace Database\Factories;

use App\Models\Debtor;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $debtors = Debtor::doesntHave('loans')->get()->pluck('debtor_id')->all();
        $dte = $this->faker->date();

        return [
            'debtor_id' => $this->faker->randomElement($debtors),
            'currency_id' => $this->faker->numberBetween(1, 2),
            'fee_amount' => $this->faker->randomFloat(2, 100, 1000),
            'start_on' => $dte,
            'thanks_days' => $this->faker->numberBetween(0, 2),
            'next_on' => $dte,
            'frequency_id' => $this->faker->numberBetween(1, 4),
            'status' => $this->faker->randomElement(['active', 'settled', 'paused', 'canceled']),
        ];
    }
}
