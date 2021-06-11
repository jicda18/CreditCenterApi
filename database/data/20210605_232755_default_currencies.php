<?php

use App\Classes\MigrationData;
use App\Models\Currency;

class DefaultCurrencies extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Currency::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['name' => 'Dollar', 'symbol' => '$', 'default' => true, 'exchange_value' => 1],
            ['name' => 'Peso', 'symbol' => 'RD$', 'default' => false, 'exchange_value' => 57.7],
        ];
    }
}
