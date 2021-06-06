<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends ApiModel implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    protected $table = 'tbUsers';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'password',
        'email',
        'fullname',
        'must_change_password',
        'block_at',
    ];
    protected $hidden = ['user_id', 'password', 'admin_at', 'must_change_password', 'block_at'];

    protected $casts = [
        'user_id' => 'integer',
        'block_at' => 'datetime',
        'must_change_password' => 'boolean',
        'admin_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    public function getFullnameAttribute($fullname)
    {
        return ucwords($fullname);
    }

    public function setFullnameAttribute($fullname)
    {
        $this->attributes['fullname'] = mb_strtolower($fullname);
    }

    public function isAdmin(): bool
    {
        return $this->admin_at !== null;
    }

    public function isBlocked(): bool
    {
        return $this->block_at !== null;
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }

    /**
     * The companies that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'tbCompaniesUsersPivot', 'user_id', 'company_id', 'user_id', 'company_id')->withTimestamps();
    }

    /**
     * The notifications that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'tbNotificationsUsersPivot', 'user_id', 'notification_id', 'user_id', 'notification_id')->withTimestamps()->withPivot(['read_at']);
    }

    /**
     * The permissions that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'tbPermissionsUsersPivot', 'user_id', 'permission_id', 'user_id', 'permission_id')->withTimestamps()->withPivot(['company_id']);
    }

    /**
     * Get all of the resets for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'user_id', 'user_id');
    }

    /**
     * Get all of the transactions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'register_user_id', 'user_id');
    }

    /**
     * Get all of the logs for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'user_id', 'user_id');
    }

    /**
     * Get all of the securities for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function securities(): HasMany
    {
        return $this->hasMany(SecurityLog::class, 'user_id', 'user_id');
    }
}
