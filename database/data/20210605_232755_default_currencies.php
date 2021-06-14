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
            ['name' => 'Peso', 'symbol' => 'RD$', 'default' => true, 'exchange_value' => 1],
            ['name' => 'Dollar', 'symbol' => '$', 'default' => false, 'exchange_value' => 57.7],
            ['name' => 'Euro', 'symbol' => '€', 'default' => false, 'exchange_value' => 71.35],
        ];
    }
}
