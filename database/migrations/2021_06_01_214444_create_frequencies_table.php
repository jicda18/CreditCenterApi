<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequenciesTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbFrequencies', function (Blueprint $table) {
            $table->id('frequency_id');
            $table->string('function_name');
            $table->string('label');

            $this->addControlFields($table);

            $table->unique('function_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbFrequencies');
    }
}
