<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuarantorsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbGuarantors', function (Blueprint $table) {
            $table->id('guarantor_id');
            $table->unsignedBigInteger('company_id');
            $table->string('fullname');
            $table->string('personal_id');
            $table->unsignedBigInteger('province_id');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('occupation');
            $table->string('workplace');
            $table->float('float');
            $table->float('other_income');

            $this->addControlFields($table);

            $table->foreign('company_id')->references('company_id')->on('tbCompanies');
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
        Schema::dropIfExists('tbGuarantors');
    }
}
