<?php

use App\Classes\MigrationData;
use Spatie\Permission\Models\Permission as ModelsPermission;

class NewDefaultPermissions extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return ModelsPermission::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['guard_name' => 'api', 'name' => 'transactions.bank.store', 'description' => 'Crear Transacciones a Bancos.'],
        ];
    }
}
