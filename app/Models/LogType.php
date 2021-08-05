<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class LogType extends ApiModel
{
    protected $table = 'tbLogTypes';
    protected $primaryKey = 'log_type_id';
    protected $fillable = [
        'description',
    ];
    protected $hidden = ['log_type_id'];

    protected $casts = [
        'log_type_id' => 'integer',
    ];

    /**
     * Get all of the logs for the LogType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'log_type_id', 'log_type_id');
    }

    public function authorized(Company $company, User $user): bool
    {
        return true;
    }
}
