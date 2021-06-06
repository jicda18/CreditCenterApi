<?php

namespace App\Models;

use App\Exceptions\InvalidIpFormatException;
use App\Scopes\AccesslistScope;
use App\Traits\AccesslistHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Accesslist extends ApiModel
{
    use AccesslistHelper;

    protected $table = 'tbAccesslists';
    protected $primaryKey = 'accesslist_id';
    protected $fillable = [
        'start_ip',
        'end_ip',
        'description',
        'blacklisted',
        'until_at',
        'transaction_id',
    ];
    protected $hidden = ['accesslist_id'];

    protected $casts = [
        'accesslist_id' => 'integer',
        'blacklisted' => 'boolean',
        'until_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AccesslistScope);
    }

    public function setIpRangeAttribute($ip_range)
    {
        if ($this->isValidNetwork($ip_range)) {
            $arr = explode('/', $ip_range);
            $this->attributes['start_ip'] = $this->networkAddress($arr[0], (int) $arr[1]);
            $this->attributes['end_ip'] = $this->broadcastAddress($arr[0], (int) $arr[1]);
        } elseif ($this->isValidRange($ip_range)) {
            $arr = explode('-', $ip_range);
            $this->attributes['start_ip'] = $arr[0];
            $this->attributes['end_ip'] = $arr[1];
        } elseif ($this->isValidIp($ip_range)) {
            $this->attributes['start_ip'] = $ip_range;
            $this->attributes['end_ip'] = $ip_range;
        } else {
            throw new InvalidIpFormatException($ip_range);
        }
    }

    static function searchIp(string $ip): Builder
    {
        $longIp = ip2long($ip);
        return static::query()
            ->where(DB::raw('INET_ATON(start_ip)'), '<=', $longIp)
            ->where(DB::raw('INET_ATON(end_ip)'), '>=', $longIp);
    }
}
