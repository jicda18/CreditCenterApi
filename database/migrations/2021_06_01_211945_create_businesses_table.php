<?php

use App\Traits\MigrationControlFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    use MigrationControlFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbBusinesses', function (Blueprint $table) {
            $table->id('business_id');
            $table->string('name');
            $table->enum('type', ['own', 'rent']);
            $table->string('phone');
            $table->unsignedBigInteger('province_id');
            $table->string('address');
            $table->text('comment');

            $this->addControlFields($table);

            $table->foreign('province_id')->references('province_id')->on('tbProvinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbBusinesses');
    }
}
