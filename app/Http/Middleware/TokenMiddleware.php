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
            $response = response()->json(['error' => $content->message, 'code' => 401], 401);
            $response->headers->add(['X-Error-Auth' => 1]);

            return $response;
        }

        return response()->json(['data' => $content], 200);
    }
}
