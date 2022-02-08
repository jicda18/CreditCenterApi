<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debtor extends ApiModel
{
    protected $table = 'tbDebtors';
    protected $primaryKey = 'debtor_id';
    protected $fillable = [
        'guarantor',
        'guarantor_email',
        'guarantor_phone',
        'guarantor_personal_id',
        'business',
        'fullname',
        'nickname',
        'personal_id',
        'gender',
        'province_id',
        'address',
        'date_of_birth',
        'phone',
        'email',
        'marital_status',
        'occupation',
        'salary',
    ];
    protected $hidden = ['debtor_id'];

    protected $casts = [
        'debtor_id' => 'integer',
        'province_id' => 'integer',
        'date_of_birth' => 'datetime',
        'salary' => 'float',
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
     * Get the province that owns the Debtor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    /**
     * Get all of the loans for the Debtor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'debtor_id', 'debtor_id');
    }
}
