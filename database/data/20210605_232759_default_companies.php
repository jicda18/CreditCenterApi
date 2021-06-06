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
        // Todo: set real companies fields
        return [
            ['name' => 'Test 1', 'logo' => 'test1.png'],
            ['name' => 'Test 2', 'logo' => 'test2.png'],
        ];
    }
}
