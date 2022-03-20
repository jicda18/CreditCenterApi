<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        'old_password',
    ];
    protected $hidden = ['password_reset_id', 'token', 'old_password'];

    protected $casts = [
        'password_reset_id' => 'integer',
        'user_id' => 'integer',
        'expired_at' => 'datetime',
        'completed_at' => 'datetime',
        'revoked_at' => 'datetime',
        'failed_at' => 'datetime',
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

    public function expired()
    {
        if (Carbon::now()->gt($this->expired_at)) {
            throw ValidationException::withMessages([
                'expired' => ['Codigo de recuperación expirado.']
            ]);
        }
    }

    public function used()
    {
        if ($this->completed_at !== null || $this->failed_at !== null ||  $this->revoked_at !== null) {
            throw ValidationException::withMessages([
                'used' => ['Codigo de recuperación no existe.']
            ]);
        }
    }

    public function validateUser(int $user_id)
    {
        if ($this->user_id !== $user_id) {
            throw ValidationException::withMessages([
                'user_key' => ['Codigo de usuario invalido.']
            ]);
        }
    }

    public function validate(int $user_id, string $token)
    {
        $this->expired();

        $this->used();

        $this->validateUser($user_id);

        if (!Hash::check($token, $this->token)) {
            throw ValidationException::withMessages([
                'code' => ['Codigo de recuperación invalido.']
            ]);
        }
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
}
