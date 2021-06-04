<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccesslistScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where(function ($query) {
            $query->whereNull('until_at')->orWhere('until_at', '>=', date('Y-m-d H:i:s'));
        });
    }
}
