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
            $table->unsignedBigInteger('guarantor_id');
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('fullname');
            $table->string('nickname')->nullable();
            $table->string('personal_id');
            $table->unsignedBigInteger('province_id');
            $table->string('address');
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('occupation');
            $table->float('float');
            $table->float('other_income');

            $this->addControlFields($table);

            $table->foreign('company_id')->references('company_id')->on('tbCompanies');
            $table->foreign('guarantor_id')->references('guarantor_id')->on('tbGuarantors');
            $table->foreign('business_id')->references('business_id')->on('tbBusinesses');
            $table->foreign('province_id')->references('province_id')->on('tbProvinces');

            $table->unique('company_id');
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
