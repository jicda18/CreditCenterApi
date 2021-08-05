<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbFees', function (Blueprint $table) {
            $table->id('fee_id');
            $table->unsignedBigInteger('loan_id');
            $table->date('payment_on');
            $table->float('amount');
            $table->float('balance');
            $table->date('limit_on');
            $table->timestamp('paid_at')->nullable();

            $this->addControlFields($table);

            $table->foreign('loan_id')->references('loan_id')->on('tbLoans');

            $table->index('loan_id');
            $table->index('payment_on');
            $table->index('limit_on');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbFees');
    }
}
