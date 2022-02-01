<?php

use App\Classes\MigrationData;
use Spatie\Permission\Models\Role;

class DefaultRoles extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Role::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['guard_name' => 'api', 'name' => 'admin', 'description' => 'Administrador'],
            ['guard_name' => 'api', 'name' => 'agent', 'description' => 'Agente', 'permissions' => [1, 5, 9, 13, 14, 18, 22, 26, 30, 31, 32, 34,]],
        ];
    }
}
