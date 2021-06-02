<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbPermissionsUsersPivot', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('tbCompanies');
            $table->foreign('permission_id')->references('permission_id')->on('tbPermissions');
            $table->foreign('user_id')->references('user_id')->on('tbUsers');

            $table->primary(['company_id', 'permission_id', 'user_id']);
            $table->index('company_id');
            $table->index('permission_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbPermissionsUsersPivot');
    }
}
