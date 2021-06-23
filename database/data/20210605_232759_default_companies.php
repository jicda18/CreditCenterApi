<?php

use App\Classes\MigrationData;
use App\Models\Company;

class DefaultCompanies extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Company::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        // Todo: set icon and frontend
        return [
            ['name' => 'Credit Center', 'logo' => 'company_logo_1.png', 'icon' => '', 'frontend' => ''],
            // Todo: set company 2
            ['name' => 'Test 2', 'logo' => 'company_logo_2.png', 'icon' => '', 'frontend' => ''],
        ];
    }
}
