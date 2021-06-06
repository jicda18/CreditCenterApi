<?php

use App\Classes\MigrationData;
use App\Models\Country;

class DefaultCountries extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Country::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            // Todo: Implement DefaultCountries
        ];
    }
}
