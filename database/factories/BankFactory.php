<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->faker->numberBetween(1, 2),
            'currency_id' => $this->faker->numberBetween(1, 2),
            'bank_name' => $this->faker->company,
            'account_number' => $this->faker->iban('DO'),
            'alias' => 'Bank ' . $this->faker->numberBetween(1, 9999),
            'balance' => 0
        ];
    }
}
