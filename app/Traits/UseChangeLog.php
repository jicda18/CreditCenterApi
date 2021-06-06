<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait UseChangeLog
{
    /**
     * Is DB has attribute.
     *
     * @param   string   $attribute
     * @return  bool
     */
    protected function hasAttribute(string $attribute): bool
    {
        return Schema::hasColumn($this->getTable(), $attribute);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (Model $model) {
            $attribute = 'uuid';
            if ($model->hasAttribute($attribute)) {
                $model->{$attribute} = Str::uuid()->toString();
            }
        });

        // Todo: add change log functions
    }
}
