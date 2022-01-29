<?php

use App\Classes\MigrationData;
use App\Models\User;
use Carbon\Carbon;

class DefaultUsers extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return User::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['username' => 'jicruz', 'password' => 'J1cdA18', 'email' => 'jicda18@outlook.com', 'phone' => null, 'fullname' => 'Josué Cruz', 'must_change_password' => false, 'roles' => [1]],
            ['username' => 'ejaquez', 'password' => 'ejaquez2021', 'email' => 'emiljaquez@gmail.com', 'phone' => null, 'fullname' => 'Emil Jaquez', 'must_change_password' => false, 'roles' => [1]],
            ['username' => 'Lanfranco', 'password' => 'ZAYDEN', 'email' => 'g-lanfranco@hotmail.com', 'phone' => '8492140210', 'fullname' => 'Gelvin Lanfranco', 'must_change_password' => false, 'roles' => [1]],
            ['username' => 'elvin', 'password' => 'gabriela', 'email' => 'elvin_13@hotmail.com', 'phone' => '8492140953', 'fullname' => 'Elvin Lanfranco', 'must_change_password' => false, 'roles' => [1]],
            // ['username' => 'enmanuel', 'password' => 'Moises', 'email' => 'e.moises_29@hotmail.com', 'phone' => '8297228644', 'fullname' => 'Enmanuel Moises', 'must_change_password' => false, 'roles' => [2]],
            ['username' => 'cecilia', 'password' => 'Credit', 'email' => 'ceciliaurea1@gmail.com', 'phone' => '8098417803', 'fullname' => 'Cecilia Ureña', 'must_change_password' => false, 'roles' => [1]],
        ];
    }
}
