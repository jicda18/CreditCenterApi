<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guarantor extends ApiModel
{
    protected $table = 'tbGuarantors';
    protected $primaryKey = 'guarantor_id';
    protected $fillable = [
        'company_id',
        'fullname',
        'personal_id',
        'gender',
        'province_id',
        'address',
        'phone',
        'occupation',
        'workplace',
        'salary',
        'other_income',
    ];
    protected $hidden = ['guarantor_id', 'company_id'];

    protected $casts = [
        'guarantor_id' => 'integer',
        'company_id' => 'integer',
        'province_id' => 'integer',
        'salary' => 'float',
        'other_income' => 'float',
    ];

    public function getFullnameAttribute($fullname)
    {
        return ucwords($fullname);
    }

    public function setFullnameAttribute($fullname)
    {
        $this->attributes['fullname'] = mb_strtolower($fullname);
    }

    /**
     * Get the province that owns the Guarantor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    /**
     * Get the company that owns the Guarantor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Get all of the debtors for the Guarantor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors(): HasMany
    {
        return $this->hasMany(Debtor::class, 'guarantor_id', 'guarantor_id');
    }
}
