<?php

use App\Classes\MigrationData;
use Spatie\Permission\Models\Permission as ModelsPermission;

class DefaultPermissions extends MigrationData
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
            ['guard_name' => 'api', 'name' => 'Country.view', 'description' => 'Ver Paises.'],
            ['guard_name' => 'api', 'name' => 'Country.store', 'description' => 'Crear Paises.'],
            ['guard_name' => 'api', 'name' => 'country.update', 'description' => 'Actualizar Paises.'],
            ['guard_name' => 'api', 'name' => 'country.delete', 'description' => 'Eliminar Paises.'],
            ['guard_name' => 'api', 'name' => 'province.view', 'description' => 'Ver Provincias.'],
            ['guard_name' => 'api', 'name' => 'province.store', 'description' => 'Crear Provincias.'],
            ['guard_name' => 'api', 'name' => 'province.update', 'description' => 'Actualizar Provincias.'],
            ['guard_name' => 'api', 'name' => 'province.delete', 'description' => 'Eliminar Provincias.'],
            ['guard_name' => 'api', 'name' => 'debtor.view', 'description' => 'Ver Deudores.'],
            ['guard_name' => 'api', 'name' => 'debtor.store', 'description' => 'Crear Deudores.'],
            ['guard_name' => 'api', 'name' => 'debtor.update', 'description' => 'Actualizar Deudores.'],
            ['guard_name' => 'api', 'name' => 'debtor.delete', 'description' => 'Eliminar Deudores.'],
            ['guard_name' => 'api', 'name' => 'permision.view', 'description' => 'Ver Permisos.'],
            ['guard_name' => 'api', 'name' => 'user.view', 'description' => 'Ver Usuarios.'],
            ['guard_name' => 'api', 'name' => 'user.store', 'description' => 'Crear Usuarios.'],
            ['guard_name' => 'api', 'name' => 'user.update', 'description' => 'Actualizar Usuarios.'],
            ['guard_name' => 'api', 'name' => 'user.delete', 'description' => 'Eliminar Usuarios.'],
            ['guard_name' => 'api', 'name' => 'bank.view', 'description' => 'Ver Bancos.'],
            ['guard_name' => 'api', 'name' => 'bank.store', 'description' => 'Crear Bancos.'],
            ['guard_name' => 'api', 'name' => 'bank.update', 'description' => 'Actualizar Bancos.'],
            ['guard_name' => 'api', 'name' => 'bank.delete', 'description' => 'Eliminar Bancos.'],
            ['guard_name' => 'api', 'name' => 'loan.view', 'description' => 'Ver Deudas.'],
            ['guard_name' => 'api', 'name' => 'loan.store', 'description' => 'Crear Deudas.'],
            ['guard_name' => 'api', 'name' => 'loan.update', 'description' => 'Actualizar Deudas.'],
            ['guard_name' => 'api', 'name' => 'loan.delete', 'description' => 'Eliminar Deudas.'],
            ['guard_name' => 'api', 'name' => 'fee.view', 'description' => 'Ver Coutas.'],
            ['guard_name' => 'api', 'name' => 'frecuency.view', 'description' => 'Ver Frecuencias.'],
            ['guard_name' => 'api', 'name' => 'transantions.view', 'description' => 'Ver Transaciones.'],
            ['guard_name' => 'api', 'name' => 'currency.view', 'description' => 'Ver Monedas.'],
            ['guard_name' => 'api', 'name' => 'currency.store', 'description' => 'Crear Monedas.'],
            ['guard_name' => 'api', 'name' => 'currency.update', 'description' => 'Actualizar Monedas.'],
            ['guard_name' => 'api', 'name' => 'currency.delete', 'description' => 'Eliminar Monedas.'],
            ['guard_name' => 'api', 'name' => 'logType.view', 'description' => 'Ver Tipos de Log.'],
            ['guard_name' => 'api', 'name' => 'log.view', 'description' => 'Ver Logs.'],
            ['guard_name' => 'api', 'name' => 'securityLog.view', 'description' => 'Ver Logs de Seguridad.'],
            ['guard_name' => 'api', 'name' => 'accesslist.view', 'description' => 'Ver Accesslist.'],
        ];
    }
}
