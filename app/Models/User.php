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
use Spatie\Permission\Traits\HasRoles;

class User extends ApiModel implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, HasRoles;

    protected $table = 'tbUsers';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'password',
        'email',
        'fullname',
        'phone',
        'must_change_password',
        'block_at',
    ];
    protected $hidden = ['user_id', 'password', 'must_change_password', 'block_at'];

    protected $casts = [
        'user_id' => 'integer',
        'block_at' => 'datetime',
        'must_change_password' => 'boolean',
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

    public function getAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isBlocked(): bool
    {
        return $this->block_at !== null;
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function validatePassword($password)
    {
        return Hash::check($password, $this->password);
    }

    public function validateForPassportPasswordGrant($password)
    {
        return $this->validatePassword($password);
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
