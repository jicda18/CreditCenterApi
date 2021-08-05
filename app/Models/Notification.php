<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends ApiModel
{
    protected $table = 'tbNotifications';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
        'company_id',
        'level',
        'text',
        'severity',
    ];
    protected $hidden = ['notification_id', 'company_id'];

    protected $casts = [
        'notification_id' => 'integer',
        'company_id' => 'integer',
        'level' => 'integer',
    ];

    protected $levelMap = [
        0 => 'info',
        1 => 'success',
        2 => 'warning',
        3 => 'alert',
        4 => 'danger',
        5 => 'error',
        6 => 'critical',
        7 => 'urgent',
    ];

    public function getSeverityAttribute()
    {
        if ($this->level <= 0) {
            return $this->levelMap[0];
        }

        if ($this->level >= count($this->levelMap) - 1) {
            return $this->levelMap[count($this->levelMap) - 1];
        }

        return $this->levelMap[$this->level];
    }

    public function setSeverityAttribute($severity)
    {
        $severityMap = array_flip($this->levelMap);

        if (key_exists($severity, $severityMap)) {
            $this->attributes['level'] = $severityMap[$severity];
        }

        $this->attributes['level'] = 0;
    }

    /**
     * Get the company that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * The users that belong to the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tbNotificationsUsersPivot', 'notification_id', 'user_id', 'notification_id', 'user_id')->withTimestamps()->withPivot(['read_at']);
    }

    public function authorized(Company $company, User $user): bool
    {
        return $company->company_id === $this->company_id && $user->user_id === $this->user_id;
    }
}
