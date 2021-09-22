<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['own', 'rent']),
            'number_of_employee' => $this->faker->numberBetween(10, 50),
            'phone' => $this->faker->phoneNumber,
            'province_id' => 1,
            'address' => $this->faker->streetAddress,
            'comment' => $this->faker->paragraph($this->faker->numberBetween(1, 3)),
            'company_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}
