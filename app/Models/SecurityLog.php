<?php

namespace App\Models;

use App\Traits\IpGeo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends ApiModel
{
    use IpGeo;

    protected $table = 'tbSecurityLogs';
    protected $primaryKey = 'security_log_id';
    protected $fillable = [
        'type',
        'level',
        'event_at',
        'success',
        'description',
        'user_id',
        'ip',
        'user_agent',
        'route',
        'url',
        'query',
        'geo',
    ];
    protected $hidden = ['security_log_id'];

    protected $casts = [
        'security_log_id' => 'integer',
        'level' => 'integer',
        'event_at' => 'datetime',
        'success' => 'boolean',
        'user_id' => 'integer',
        'query' => AsArrayObject::class,
        'geo' => AsArrayObject::class,
    ];

    /**
     * Get the user that owns the SecurityLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function (Model $model) {
            $request = request();

            $model->user_id = null_prevent_property($request->user(), 'user_id');
            $model->ip = request_ip();
            $model->user_agent = $request->userAgent();
            $model->route =  null_prevent_function($request->route(), 'getName');
            $model->url = $request->url();
            $model->query = $request->query();
            $model->geo = $this->getGeoFromIp($model->ip);
        });
    }
}
