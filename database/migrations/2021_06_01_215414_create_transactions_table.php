<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbTransactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('register_user_id');
            $table->timestamp('was_at');
            $table->float('amount');
            $table->text('comment');

            $this->addControlFields($table);

            $table->foreign('loan_id')->references('loan_id')->on('tbLoans');
            $table->foreign('bank_id')->references('bank_id')->on('tbBanks');
            $table->foreign('register_user_id')->references('user_id')->on('tbUsers');

            $table->index('loan_id');
            $table->index('bank_id');
            $table->index('register_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbTransactions');
    }
}
