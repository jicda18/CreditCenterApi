<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbProvinces', function (Blueprint $table) {
            $table->id('province_id');
            $table->unsignedBigInteger('country_id');
            $table->string('name');

            $this->addControlFields($table);

            $table->foreign('country_id')->references('country_id')->on('tbCountries');

            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbProvinces');
    }
}
