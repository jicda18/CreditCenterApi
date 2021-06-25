<?php

namespace App\Http\Middleware;

use Closure;

class TokenMiddleware
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
        $response = $next($request);

        $content = json_decode($response->getContent());

        if (property_exists($content, 'error')) {
            return $response;
        }

        return response()->json(['data' => $content], 200);
    }
}
