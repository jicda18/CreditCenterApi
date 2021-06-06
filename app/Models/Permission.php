<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends ApiModel
{
    protected $table = 'tbPermissions';
    protected $primaryKey = 'permission_id';
    protected $fillable = [
        'name',
        'description',
    ];
    protected $hidden = ['permission_id'];

    protected $casts = [
        'permission_id' => 'integer',
    ];

    /**
     * The users that belong to the Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tbPermissionsUsersPivot', 'user_id', 'permission_id', 'user_id', 'permission_id')->withTimestamps()->withPivot(['company_id']);
    }
}
