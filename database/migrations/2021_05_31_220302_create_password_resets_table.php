<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbPasswordResets', function (Blueprint $table) {
            $table->id('password_reset_id');
            $table->unsignedBigInteger('user_id');
            $table->string('email');
            $table->string('token');
            $table->timestamp('expired_at');
            $table->string('created_ip');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->string('closed_ip')->nullable();
            $table->string('old_password')->nullable();

            $this->addControlFields($table);

            $table->foreign('user_id')->references('user_id')->on('tbUsers');

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
        Schema::dropIfExists('tbPasswordResets');
    }
}
