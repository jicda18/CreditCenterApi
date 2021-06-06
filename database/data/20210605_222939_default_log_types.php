<?php

use App\Classes\MigrationData;
use App\Models\LogType;

class DefaultLogTypes extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return LogType::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['description' => 'Insert'],
            ['description' => 'Update'],
            ['description' => 'Delete'],
        ];
    }
}
