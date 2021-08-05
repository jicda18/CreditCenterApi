<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends ApiModel
{
    protected $table = 'tbPasswordResets';
    protected $primaryKey = 'password_reset_id';
    protected $fillable = [
        'user_id',
        'email',
        'token',
        'expired_at',
        'created_ip',
        'completed_at',
        'revoked_at',
        'closed_ip',
        'old_password',
    ];
    protected $hidden = ['password_reset_id', 'token', 'old_password'];

    protected $casts = [
        'password_reset_id' => 'integer',
        'user_id' => 'integer',
        'expired_at' => 'datetime',
        'completed_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function getEmailAttribute($email)
    {
        $arr = explode('@', $email);
        if ($arr === false || count($arr) !== 2) return '';

        $username = $arr[0];
        $domain = $arr[1];
        return substr_replace($username, str_repeat('*', strlen($username) - 3), 3) . '@' . substr($domain, 0, 1) . str_repeat('*', strlen($domain) - 8) . substr($domain, strlen($domain) - 7);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    /**
     * Get the user that owns the PasswordReset
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function authorized(Company $company, User $user): bool
    {
        return $user->user_id === $this->user->user_id;
    }
}
