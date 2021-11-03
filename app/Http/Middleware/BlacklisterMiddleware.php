<?php

namespace App\Http\Middleware;

use App\Classes\ToMuchBadAuth;
use App\Classes\ToMuchRequest;
use app\Interfaces\BlackListerChecker;
use Closure;

class BlacklisterMiddleware
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
        $key = $this->ip_key_generate($request);

        /**
         * @var \App\Interfaces\BlackListerChecker[]
         */
        $pre_blockers = [
            new ToMuchRequest(60, 256),
            new ToMuchRequest(3600, 8192),
            new ToMuchRequest(84600, 131072),
        ];

        if ($blocker = $this->validate_blockers($pre_blockers, $key)) {
            $blocker->blacklist($request, $key);

            throw $blocker->buildException($blocker->getBlockSeconds());
        }

        $response = $next($request);

        $headers = $response->headers;
        if ($headers->has('X-Error-Auth')) {
            /**
             * @var \App\Interfaces\BlackListerHandler[]
             */
            $post_blockers = [
                new ToMuchBadAuth(60, 128),
                new ToMuchBadAuth(3600, 4096),
                new ToMuchBadAuth(84600, 65536),
            ];

            if ($blocker = $this->validate_blockers($post_blockers, $key)) {
                $blocker->blacklist($request, $key);

                throw $blocker->buildException($blocker->getBlockSeconds());
            }
        }

        return $response;
    }

    /**
     *  Validate any blocker until find true.
     *
     * @param  \App\Interfaces\BlackListerHandler[] $pre_blockers
     * @param  string $key
     * @return  \App\Interfaces\BlackListerHandler[] | null
     */
    protected function validate_blockers($pre_blockers, $key): ?BlackListerChecker
    {
        foreach ($pre_blockers as $blocker) {
            if ($blocker->evaluate($key, $blocker->getSeconds(), $blocker->getLimit())) {
                return $blocker;
            }
        }

        return null;
    }

    protected function ip_key_generate($request): string
    {
        return sha1(request_ip());
    }
}
