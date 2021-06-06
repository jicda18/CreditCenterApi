<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends ApiModel
{
    protected $table = 'tNotifications';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
        'company_id',
        'level',
        'text',
    ];
    protected $hidden = ['notification_id', 'company_id'];

    protected $casts = [
        'notification_id' => 'integer',
        'company_id' => 'integer',
        'level' => 'integer',
    ];

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
}
