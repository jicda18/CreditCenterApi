<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait GenericController
{
    protected function otherValidations(Request $request, Model $model, bool $store): ?array
    {
        return null;
    }

    protected function beforeToSave(Request $request, Model $model, bool $store): Model
    {
        return $model;
    }

    protected function afterToSave(Request $request, Model $model, bool $store): Model
    {
        return $model;
    }
}
