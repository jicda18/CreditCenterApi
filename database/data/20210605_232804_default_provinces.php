<?php

use App\Classes\MigrationData;
use App\Models\Province;

class DefaultProvinces extends MigrationData
{
    /**
     * Get the model reference class to use to insert the data.
     *
     * @return  return_type
     */
    public function getModel(): string
    {
        return Province::class;
    }

    /**
     * Get the entities to insert into database.
     *
     * @return  array
     */
    public function getEntities(): array
    {
        return [
            ['country_id' => 1, 'name' => 'Azua'],
            ['country_id' => 1, 'name' => 'Bahoruco'],
            ['country_id' => 1, 'name' => 'Barahona'],
            ['country_id' => 1, 'name' => 'Dajabón'],
            ['country_id' => 1, 'name' => 'Duarte'],
            ['country_id' => 1, 'name' => 'El Seibo'],
            ['country_id' => 1, 'name' => 'Elías Piña'],
            ['country_id' => 1, 'name' => 'Espaillat'],
            ['country_id' => 1, 'name' => 'Hato Mayor'],
            ['country_id' => 1, 'name' => 'Hermanas Mirabal'],
            ['country_id' => 1, 'name' => 'Independencia'],
            ['country_id' => 1, 'name' => 'La Altagracia'],
            ['country_id' => 1, 'name' => 'La Romana'],
            ['country_id' => 1, 'name' => 'La Vega'],
            ['country_id' => 1, 'name' => 'María Trinidad Sánchez'],
            ['country_id' => 1, 'name' => 'Monseñor Nouel'],
            ['country_id' => 1, 'name' => 'Monte Cristi'],
            ['country_id' => 1, 'name' => 'Monte Plata'],
            ['country_id' => 1, 'name' => 'Pedernales'],
            ['country_id' => 1, 'name' => 'Peravia'],
            ['country_id' => 1, 'name' => 'Puerto Plata'],
            ['country_id' => 1, 'name' => 'Samaná'],
            ['country_id' => 1, 'name' => 'San Cristóbal'],
            ['country_id' => 1, 'name' => 'San José de Ocoa'],
            ['country_id' => 1, 'name' => 'San Juan'],
            ['country_id' => 1, 'name' => 'San Pedro de Macorís'],
            ['country_id' => 1, 'name' => 'Sánchez Ramírez'],
            ['country_id' => 1, 'name' => 'Santiago'],
            ['country_id' => 1, 'name' => 'Santiago Rodríguez'],
            ['country_id' => 1, 'name' => 'Santo Domingo'],
            ['country_id' => 1, 'name' => 'Valverde'],
            ['country_id' => 1, 'name' => 'Distrito Nacional'],
            ['country_id' => 2, 'name' => 'Alabama'],
            ['country_id' => 2, 'name' => 'Alaska'],
            ['country_id' => 2, 'name' => 'Arizona'],
            ['country_id' => 2, 'name' => 'Arkansas'],
            ['country_id' => 2, 'name' => 'California'],
            ['country_id' => 2, 'name' => 'Carolina del Norte'],
            ['country_id' => 2, 'name' => 'Carolina del Sur'],
            ['country_id' => 2, 'name' => 'Colorado'],
            ['country_id' => 2, 'name' => 'Connecticut'],
            ['country_id' => 2, 'name' => 'Dakota del Norte'],
            ['country_id' => 2, 'name' => 'Dakota del Sur'],
            ['country_id' => 2, 'name' => 'Delaware'],
            ['country_id' => 2, 'name' => 'Florida'],
            ['country_id' => 2, 'name' => 'Georgia'],
            ['country_id' => 2, 'name' => 'Hawái'],
            ['country_id' => 2, 'name' => 'Idaho'],
            ['country_id' => 2, 'name' => 'Illinois'],
            ['country_id' => 2, 'name' => 'Indiana'],
            ['country_id' => 2, 'name' => 'Iowa'],
            ['country_id' => 2, 'name' => 'Kansas'],
            ['country_id' => 2, 'name' => 'Kentucky'],
            ['country_id' => 2, 'name' => 'Luisiana'],
            ['country_id' => 2, 'name' => 'Maine'],
            ['country_id' => 2, 'name' => 'Maryland'],
            ['country_id' => 2, 'name' => 'Massachusetts'],
            ['country_id' => 2, 'name' => 'Míchigan'],
            ['country_id' => 2, 'name' => 'Minnesota'],
            ['country_id' => 2, 'name' => 'Misisipi'],
            ['country_id' => 2, 'name' => 'Misuri'],
            ['country_id' => 2, 'name' => 'Montana'],
            ['country_id' => 2, 'name' => 'Nebraska'],
            ['country_id' => 2, 'name' => 'Nevada'],
            ['country_id' => 2, 'name' => 'Nueva Jersey'],
            ['country_id' => 2, 'name' => 'Nueva York'],
            ['country_id' => 2, 'name' => 'Nuevo Hampshire'],
            ['country_id' => 2, 'name' => 'Nuevo México'],
            ['country_id' => 2, 'name' => 'Ohio'],
            ['country_id' => 2, 'name' => 'Oklahoma'],
            ['country_id' => 2, 'name' => 'Oregón'],
            ['country_id' => 2, 'name' => 'Pensilvania'],
            ['country_id' => 2, 'name' => 'Rhode Island'],
            ['country_id' => 2, 'name' => 'Tennessee'],
            ['country_id' => 2, 'name' => 'Texas'],
            ['country_id' => 2, 'name' => 'Utah'],
            ['country_id' => 2, 'name' => 'Vermont'],
            ['country_id' => 2, 'name' => 'Virginia'],
            ['country_id' => 2, 'name' => 'Virginia Occidental'],
            ['country_id' => 2, 'name' => 'Washington'],
            ['country_id' => 2, 'name' => 'Wisconsin'],
            ['country_id' => 2, 'name' => 'Wyoming'],
        ];
    }
}
