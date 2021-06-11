<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Debtor;
use App\Models\Guarantor;
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
        $guarantors = Guarantor::doesntHave('debtors')->get()->pluck('guarantor_id')->all();
        $businesses = Business::doesntHave('debtors')->get()->pluck('business_id')->all();

        return [
            'company_id' => $this->faker->random_int(1, 2),
            'guarantor_id' => $this->faker->randomElement($guarantors),
            'business_id' => $this->faker->randomElement($businesses),
            'fullname' => $this->faker->name(),
            'nickname' => '',
            'personal_id' => $this->faker->numerify('###-#######-#'),
            'gender' => $this->faker->randomElement(['female', 'male']),
            'province_id' => 1,
            'address' => $this->faker->streetAddress,
            'date_of_birth' => $this->faker->date('Y-m-d', '2003-06-10'),
            'phone' => $this->faker->phoneNumber,
            'marital_status' => $this->faker->randomElement(['soltero', 'casado', 'divorciado', 'vuido']),
            'occupation' => $this->faker->jobTitle,
            'salary' => $this->faker->randomFloat(2, 10000, 100000),
            'other_income' => $this->faker->randomFloat(2, 10000, 100000),
        ];
    }
}
