<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbLogs', function (Blueprint $table) {
            $table->id('log_id');
            $table->string('model');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('log_type_id');
            $table->unsignedBigInteger('user_id');
            $table->string('ip');
            $table->string('route');
            $table->timestamp('at')->useCurrent();
            $table->text('old');
            $table->text('new');
            $table->uuid('uuid');

            $table->index('uuid');
            $table->index('model');
            $table->index('reference_id');
            $table->index('log_type_id');
            $table->index('user_id');
            $table->index('at');

            $table->foreign('log_type_id')->references('log_type_id')->on('tbLogTypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbLogs');
    }
}
