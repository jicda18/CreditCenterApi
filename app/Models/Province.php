<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends ApiModel
{
    protected $table = 'tbProvinces';
    protected $primaryKey = 'province_id';
    protected $fillable = [
        'country_id',
        'name',
    ];
    protected $hidden = ['province_id'];

    protected $casts = [
        'province_id' => 'integer',
        'country_id' => 'integer',
    ];

    /**
     * Get the country that owns the Province
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    /**
     * Get all of the debtors for the Province
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors(): HasMany
    {
        return $this->hasMany(Debtor::class, 'province_id', 'province_id');
    }

    public function authorized(Company $company, User $user): bool
    {
        return true;
    }
}
