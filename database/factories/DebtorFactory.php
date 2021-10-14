<?php

namespace Database\Factories;

use App\Models\Debtor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebtorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Debtor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->faker->numberBetween(1, 2),
            'guarantor' => $this->faker->name(),
            'business' => $this->faker->company,
            'fullname' => $this->faker->name(),
            'nickname' => '',
            'personal_id' => $this->faker->numerify('###-#######-#'),
            'gender' => $this->faker->randomElement(['female', 'male']),
            'province_id' => 1,
            'address' => $this->faker->streetAddress,
            'date_of_birth' => $this->faker->date('Y-m-d', '2003-06-10'),
            'phone' => $this->faker->phoneNumber,
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'occupation' => $this->faker->jobTitle,
            'salary' => $this->faker->randomFloat(2, 10000, 100000),
        ];
    }
}
