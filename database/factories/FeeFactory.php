<?php

namespace Database\Factories;

use App\Models\Fee;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $loans = Loan::doesntHave('fees')->get()->all();
        $loan = $this->faker->randomElement($loans);

        return [
            'loan_id' => $loan->loan_id,
            'payment_on' => $loan->next_on,
            'amount' => $loan->fee_amount,
            'limit_on' => $loan->next_on,
            'paid_at' => null,
        ];
    }
}
