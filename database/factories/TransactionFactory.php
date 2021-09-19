<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\Loan;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $loans = Loan::doesntHave('transactions')->get()->pluck('loan_id')->all();
        $banks = Bank::all()->pluck('bank_id')->all();
        $currencies = Currency::all()->pluck('currency_id')->all();

        return [
            'loan_id' => $this->faker->randomElement($loans),
            'bank_id' => $this->faker->randomElement($banks),
            'currency_id' => $this->faker->randomElement($currencies),
            'register_user_id' => 1,
            'was_at' => $this->faker->dateTime(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'comment' => $this->faker->paragraph($this->faker->numberBetween(1, 3)),
        ];
    }
}
