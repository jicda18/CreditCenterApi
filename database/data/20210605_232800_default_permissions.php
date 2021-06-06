<?php

use App\Classes\MigrationData;
use App\Models\Permission;

class DefaultPermissions extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Permission::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            // Todo: Implement DefaultPermissions
        ];
    }
}
