<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityLogsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbSecurityLogs', function (Blueprint $table) {
            $table->id('security_log_id');
            $table->string('type');
            $table->unsignedTinyInteger('level');
            $table->boolean('success');
            $table->text('description');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip');
            $table->string('user_agent');
            $table->string('route');
            $table->text('url');
            $table->text('query');
            $table->text('geo')->nullable();

            $this->addControlFields($table);

            $table->foreign('user_id')->references('user_id')->on('tbUsers');

            $table->index('type');
            $table->index('level');
            $table->index('success');
            $table->index('user_id');
            $table->index('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbSecurityLogs');
    }
}
