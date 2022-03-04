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
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->boolean('is_cash')->default(false);
            $table->unsignedBigInteger('register_user_id');
            $table->timestamp('was_at');
            $table->double('amount');
            $table->unsignedBigInteger('currency_id');
            $table->boolean('is_interest')->default(false);
            $table->double('loan_amount')->nullable();
            $table->double('loan_balance')->nullable();
            $table->double('bank_amount')->nullable();
            $table->double('bank_balance')->nullable();
            $table->text('comment');

            $this->addControlFields($table);

            $table->foreign('loan_id')->references('loan_id')->on('tbLoans');
            $table->foreign('bank_id')->references('bank_id')->on('tbBanks');
            $table->foreign('register_user_id')->references('user_id')->on('tbUsers');
            $table->foreign('currency_id')->references('currency_id')->on('tbCurrencies');

            $table->index('loan_id');
            $table->index('bank_id');
            $table->index('register_user_id');
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
        Schema::dropIfExists('tbTransactions');
    }
}
