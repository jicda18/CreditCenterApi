<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDebtorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbDebtors', function (Blueprint $table) {
            $table->string('occupation')->nullable()->change();
            $table->dropColumn('other_income');

            $table->dropIndex('tbdebtors_guarantor_id_index');
            $table->dropIndex('tbdebtors_business_id_index');

            $table->dropColumn('business_id');
            $table->string('business')->nullable()->after('company_id');

            $table->dropColumn('guarantor_id');
            $table->string('guarantor')->nullable()->after('company_id');
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
            //
        });
    }
}
