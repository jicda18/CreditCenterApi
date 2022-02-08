<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDebtorToAddGarantorFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbDebtors', function (Blueprint $table) {
            $table->string('guarantor_email')->nullable()->after('guarantor');
            $table->string('guarantor_phone')->nullable()->after('guarantor');
            $table->string('guarantor_personal_id')->nullable()->after('guarantor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbDebtors', function (Blueprint $table) {
            $table->dropColumn('guarantor_email');
            $table->dropColumn('guarantor_phone');
            $table->dropColumn('guarantor_personal_id');
        });
    }
}
