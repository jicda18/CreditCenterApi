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
            ['name' => 'Ver paises', 'description' => 'Permite ver los paises existentes.'],
            ['name' => 'Crear paises', 'description' => 'Permite crear nuevos paises.'],
            ['name' => 'Actualizar paises', 'description' => 'Permite actualizar la información de los paises existentes.'],
            ['name' => 'Eliminar paises', 'description' => 'Permite eliminar los paises existentes.'],
            ['name' => 'Ver provincias', 'description' => 'Permite ver las provincias existentes.'],
            ['name' => 'Crear provincias', 'description' => 'Permite crear nuevas provincias.'],
            ['name' => 'Actualizar provincias', 'description' => 'Permite actualizar la información de las provincias existentes.'],
            ['name' => 'Eliminar provincias', 'description' => 'Permite eliminar las provincias existentes.'],
            ['name' => 'Ver empresas', 'description' => 'Permite ver las empresas existentes.'],
            ['name' => 'Crear empresas', 'description' => 'Permite crear nuevas empresas.'],
            ['name' => 'Actualizar empresas', 'description' => 'Permite actualizar la información de las empresas existentes.'],
            ['name' => 'Eliminar empresas', 'description' => 'Permite eliminar las empresas existentes.'],
            ['name' => 'Ver garantes', 'description' => 'Permite ver los garantes existentes.'],
            ['name' => 'Crear garantes', 'description' => 'Permite crear nuevos garantes.'],
            ['name' => 'Actualizar garantes', 'description' => 'Permite actualizar la información de los garantes existentes.'],
            ['name' => 'Eliminar garantes', 'description' => 'Permite eliminar los garantes existentes.'],
            ['name' => 'Ver deudores', 'description' => 'Permite ver los deudores existentes.'],
            ['name' => 'Crear deudores', 'description' => 'Permite crear nuevos deudores.'],
            ['name' => 'Actualizar deudores', 'description' => 'Permite actualizar la información de los deudores existentes.'],
            ['name' => 'Eliminar deudores', 'description' => 'Permite eliminar los deudores existentes.'],
            ['name' => 'Ver perrmisos', 'description' => 'Permite ver los perrmisos existentes.'],
            ['name' => 'Ver compañias', 'description' => 'Permite ver las compañias existentes.'],
            ['name' => 'Crear compañias', 'description' => 'Permite crear nuevas compañias.'],
            ['name' => 'Actualizar compañias', 'description' => 'Permite actualizar la información de las compañias existentes.'],
            ['name' => 'Eliminar compañias', 'description' => 'Permite eliminar las compañias existentes.'],
            ['name' => 'Ver usuarios', 'description' => 'Permite ver los usuarios existentes.'],
            ['name' => 'Crear usuarios', 'description' => 'Permite crear nuevos usuarios.'],
            ['name' => 'Actualizar usuarios', 'description' => 'Permite actualizar la información de los usuarios existentes.'],
            ['name' => 'Eliminar usuarios', 'description' => 'Permite eliminar los usuarios existentes.'],
            ['name' => 'Ver bancos', 'description' => 'Permite ver los bancos existentes.'],
            ['name' => 'Crear bancos', 'description' => 'Permite crear nuevos bancos.'],
            ['name' => 'Actualizar bancos', 'description' => 'Permite actualizar la información de los bancos existentes.'],
            ['name' => 'Eliminar bancos', 'description' => 'Permite eliminar los bancos existentes.'],
            ['name' => 'Ver deudas', 'description' => 'Permite ver las deudas existentes.'],
            ['name' => 'Crear deudas', 'description' => 'Permite crear nuevas deudas.'],
            ['name' => 'Actualizar deudas', 'description' => 'Permite actualizar la información de las deudas existentes.'],
            ['name' => 'Eliminar deudas', 'description' => 'Permite eliminar las deudas existentes.'],
            ['name' => 'Ver coutas', 'description' => 'Permite ver las coutas existentes.'],
            ['name' => 'Ver frecuencias', 'description' => 'Permite ver las frecuencias existentes.'],
            ['name' => 'Ver transacciones', 'description' => 'Permite ver las transacciones existentes.'],
            ['name' => 'Crear transacciones', 'description' => 'Permite crear nuevas transacciones.'],
            ['name' => 'Actualizar transacciones', 'description' => 'Permite actualizar la información de las transacciones existentes.'],
            ['name' => 'Eliminar transacciones', 'description' => 'Permite eliminar las transacciones existentes.'],
            ['name' => 'Ver monedas', 'description' => 'Permite ver las monedas existentes.'],
            ['name' => 'Crear monedas', 'description' => 'Permite crear nuevas monedas.'],
            ['name' => 'Actualizar monedas', 'description' => 'Permite actualizar la información de las monedas existentes.'],
            ['name' => 'Eliminar monedas', 'description' => 'Permite eliminar las monedas existentes.'],
            ['name' => 'Ver tipos de logs', 'description' => 'Permite ver los tipos de logs existentes.'],
            ['name' => 'Ver changes logs', 'description' => 'Permite ver los changes logs existentes.'],
            ['name' => 'Ver securities logs', 'description' => 'Permite ver los securities logs existentes.'],
            ['name' => 'Ver access list', 'description' => 'Permite ver los access list existentes.'],
        ];
    }
}
