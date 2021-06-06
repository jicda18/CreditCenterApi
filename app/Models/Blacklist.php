<?php

namespace App\Models;

use App\Scopes\BlacklistScope;

class Blacklist extends Accesslist
{
    protected $attributes = [
        'blacklisted' => true,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BlacklistScope);
    }
}
