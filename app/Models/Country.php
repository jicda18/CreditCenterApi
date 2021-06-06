<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends ApiModel
{
    protected $table = 'tbCountries';
    protected $primaryKey = 'country_id';
    protected $fillable = [
        'name',
        'abbreviation',
    ];
    protected $hidden = ['country_id'];

    protected $casts = [
        'country_id' => 'integer',
    ];

    /**
     * Get all of the provinces for the Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'country_id', 'country_id');
    }
}
