<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends ApiModel
{
    protected $table = 'tbBusinesses';
    protected $primaryKey = 'business_id';
    protected $fillable = [
        'name',
        'type',
        'number_of_employee',
        'phone',
        'province_id',
        'address',
        'comment',
    ];
    protected $hidden = ['business_id'];

    protected $casts = [
        'business_id' => 'integer',
        'number_of_employee' => 'integer',
        'province_id' => 'integer',
    ];

    /**
     * Get the province that owns the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    /**
     * Get all of the debtors for the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors(): HasMany
    {
        return $this->hasMany(Debtor::class, 'business_id', 'business_id');
    }

    public function authorized(Company $company, User $user): bool
    {
        return $company->company_id === $this->debtor->company_id;
    }
}
