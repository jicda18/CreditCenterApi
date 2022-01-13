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
            ['username' => 'jicruz', 'password' => 'J1cdA18', 'email' => 'jicda18@outlook.com', 'fullname' => 'Josué Cruz', 'must_change_password' => false, 'roles' => [1]],
            ['username' => 'ejaquez', 'password' => 'ejaquez2021', 'email' => 'emiljaquez@gmail.com', 'fullname' => 'Emil Jaquez', 'must_change_password' => false, 'roles' => [1]],
            ['username' => 'glanfranco', 'password' => 'glanfranco2021', 'email' => '', 'fullname' => 'Lanfranco', 'must_change_password' => false, 'roles' => [1]],
        ];
    }
}
