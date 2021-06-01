<?php

namespace App\Trait;

use Illuminate\Database\Schema\Blueprint;

trait MigrationControlFields
{
    protected function addControlFields(Blueprint $table)
    {
        // To prevent mysql current timestamp timezone error
        $table->timestamp('mysql_use_current')->useCurrent()->useCurrentOnUpdate();

        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->uuid('uuid');

        $table->index('uuid');
        $table->index('created_at');
    }
}
