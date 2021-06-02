<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesslistsTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbAccesslists', function (Blueprint $table) {
            $table->id('accesslist_id');
            $table->string('start_ip');
            $table->string('end_ip');
            $table->text('description');
            $table->boolean('blacklisted');
            $table->timestamp('until_at')->nullable();

            $this->addControlFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbAccesslists');
    }
}
