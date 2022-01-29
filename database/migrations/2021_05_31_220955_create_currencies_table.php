<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbCurrencies', function (Blueprint $table) {
            $table->id('currency_id');
            $table->string('name');
            $table->string('symbol');
            $table->boolean('default')->default(false);
            $table->double('exchange_value');

            $this->addControlFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbCurrencies');
    }
}
