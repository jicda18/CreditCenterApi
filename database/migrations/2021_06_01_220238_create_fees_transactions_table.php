<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbFeesTransactionsPivot', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_id');
            $table->unsignedBigInteger('transaction_id');
            $table->double('interest'); // Monto a intere
            $table->double('capital'); // Monto al capital
            $table->double('late_payment'); // Monto a mora
            $table->timestamps();

            $table->foreign('fee_id')->references('fee_id')->on('tbFees');
            $table->foreign('transaction_id')->references('transaction_id')->on('tbTransactions');

            $table->primary(['fee_id', 'transaction_id']);
            $table->index('fee_id');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbFeesTransactionsPivot');
    }
}
