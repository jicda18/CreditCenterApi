<?php

namespace Database\Factories;

use App\Models\Guarantor;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuarantorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guarantor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->faker->numberBetween(1, 2),
            'fullname' => $this->faker->name(),
            'personal_id' => $this->faker->numerify('###-#######-#'),
            'gender' => $this->faker->randomElement(['female', 'male']),
            'province_id' => 1,
            'address' => $this->faker->streetAddress,
            'phone' => $this->faker->phoneNumber,
            'occupation' => $this->faker->jobTitle,
            'workplace' => $this->faker->company,
            'salary' => $this->faker->randomFloat(2, 10000, 100000),
            'other_income' => $this->faker->randomFloat(2, 10000, 100000),
        ];
    }
}
