<?php

use App\Classes\MigrationData;
use App\Models\Frequency;

class NewsFrequncy extends MigrationData
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
            ['function_name' => 'biweekly', 'label' => 'Quincenal'],
            ['function_name' => 'trimonthly', 'label' => 'Trimestral'],
            ['function_name' => 'biannual', 'label' => 'Semestral'],
        ];
    }
}
