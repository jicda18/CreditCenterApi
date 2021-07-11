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
            return response()->json(['error' => $content->message, 'code' => 401], 401)->headers->add(['X-Error-Auth' => 1]);
        }

        return response()->json(['data' => $content], 200);
    }
}
