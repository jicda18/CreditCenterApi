<?php

namespace App\Http\Middleware;

use App\Models\Blacklist;
use App\Models\Whitelist;
use Closure;

class AccessListMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = request_ip();
        $whitelist = Whitelist::searchIp($ip)->count() > 0;
        $blacklist = Blacklist::searchIp($ip)->get();

        if (!$whitelist && $blacklist->count() > 0) {
            $until = '';
            if ($blacklist->whereNull('until_at')->count() > 0) {
                $until = 'permanent';
            } else {
                $seconds = strtotime($blacklist->max('until_at')) - time();

                $until = "for {$seconds} seconds";
            }

            $code = 423;
            return response()->json(['error' => "Your ip '{$ip}' is blocked $until.", 'code' => $code], $code);
        }

        return $next($request);
    }
}
