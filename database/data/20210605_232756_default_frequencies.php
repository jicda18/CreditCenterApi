<?php

use App\Classes\MigrationData;
use App\Models\Frequency;

class DefaultFrequencies extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Frequency::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['function_name' => 'daily', 'label' => 'Diario'],
            ['function_name' => 'weekly', 'label' => 'Semanal'],
            ['function_name' => 'monthly', 'label' => 'Mensual'],
            ['function_name' => 'yearly', 'label' => 'Anual'],
        ];
    }
}
