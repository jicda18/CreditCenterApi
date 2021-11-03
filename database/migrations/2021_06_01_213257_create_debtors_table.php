<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtorsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbDebtors', function (Blueprint $table) {
            $table->id('debtor_id');
            $table->unsignedBigInteger('company_id');
            $table->string('business')->nullable();
            $table->string('guarantor_id')->nullable();
            $table->string('fullname');
            $table->string('nickname')->nullable();
            $table->string('personal_id');
            $table->enum('gender', ['female', 'male']);
            $table->unsignedBigInteger('province_id');
            $table->string('address');
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('occupation')->nullable();
            $table->float('salary');

            $this->addControlFields($table);

            $table->foreign('company_id')->references('company_id')->on('tbCompanies');
            $table->foreign('province_id')->references('province_id')->on('tbProvinces');

            $table->index('company_id');
            $table->index('province_id');
            $table->unique('personal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbDebtors');
    }
}
