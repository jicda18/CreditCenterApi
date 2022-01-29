<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbBanks', function (Blueprint $table) {
            $table->id('bank_id');
            $table->unsignedBigInteger('currency_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('alias');
            $table->double('balance');

            $this->addControlFields($table);

            $table->foreign('currency_id')->references('currency_id')->on('tbCurrencies');

            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbBanks');
    }
}
