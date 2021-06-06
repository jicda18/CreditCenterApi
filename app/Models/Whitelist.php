<?php

namespace App\Models;

use App\Scopes\WhitelistScope;

class Whitelist extends Accesslist
{
    protected $attributes = [
        'blacklisted' => false,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new WhitelistScope);
    }
}
