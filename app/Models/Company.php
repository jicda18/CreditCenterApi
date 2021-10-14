<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends ApiModel
{
    protected $table = 'tbCompanies';
    protected $primaryKey = 'company_id';
    protected $fillable = [
        'name',
        'logo',
        'icon',
        'frontend',
    ];
    protected $hidden = ['company_id'];

    protected $casts = [
        'company_id' => 'integer',
    ];

    public function getImageAttribute()
    {
        return route('companies.logo.show', $this);
    }

    public function getIcoAttribute()
    {
        return route('companies.ico.show', $this);
    }

    /**
     * The users that belong to the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tbCompaniesUsersPivot', 'company_id', 'user_id', 'company_id', 'user_id')->withTimestamps();
    }

    /**
     * Get all of the banks for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class, 'company_id', 'company_id');
    }

    /**
     * Get all of the notifications for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'company_id', 'company_id');
    }

    /**
     * Get all of the debtors for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors(): HasMany
    {
        return $this->hasMany(Debtor::class, 'company_id', 'company_id');
    }

    /**
     * Get all of the clients for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(OauthClient::class, 'company_id', 'company_id');
    }

    public function authorized(Company $company, User $user): bool
    {
        return $company->company_id === $this->company_id;
    }
}
