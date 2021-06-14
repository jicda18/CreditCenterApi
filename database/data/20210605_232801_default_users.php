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
            // Todo: give all permissions
            ['username' => 'jicruz', 'password' => 'J1cdA18', 'email' => 'jicda18@outlook.com', 'fullname' => 'Josué Cruz', 'must_change_password' => false, 'admin_at' => Carbon::now(), 'companies' => [1, 2], 'permissions' => [['permission_id' => 1, 'company_id' => 1], ['permission_id' => 2, 'company_id' => 1], ['permission_id' => 3, 'company_id' => 1], ['permission_id' => 1, 'company_id' => 2], ['permission_id' => 2, 'company_id' => 2], ['permission_id' => 3, 'company_id' => 2]]],
        ];
    }
}
