<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbNotifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedTinyInteger('level');
            $table->text('text');

            $this->addControlFields($table);

            $table->foreign('company_id')->references('company_id')->on('tbCompanies');

            $table->index('company_id');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbNotifications');
    }
}
