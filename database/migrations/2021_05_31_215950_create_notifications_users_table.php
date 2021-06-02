<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbNotificationsUsersPivot', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('notification_id')->references('notification_id')->on('tbNotifications');
            $table->foreign('user_id')->references('user_id')->on('tbUsers');

            $table->primary(['notification_id', 'user_id']);
            $table->index('notification_id');
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
        Schema::dropIfExists('tbNotificationsUsersPivot');
    }
}
