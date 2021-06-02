<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbLoans', function (Blueprint $table) {
            $table->id('loan_id');
            $table->unsignedBigInteger('debtor_id');
            $table->unsignedBigInteger('currency_id');
            $table->float('fee_amount');
            $table->date('start_on');
            $table->unsignedSmallInteger('thanks_days')->default(0);
            $table->unsignedBigInteger('frequency_id');
            $table->enum('status', ['active', 'settled', 'paused', 'canceled']);

            $this->addControlFields($table);

            $table->index('debtor_id');
            $table->index('currency_id');
            $table->index('frequency_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbLoans');
    }
}
