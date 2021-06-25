<?php

use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;

if (!function_exists('null_prevent_property')) {
    /**
     * Get property parameter if object isn't null.
     *
     * @param   mixed   $object
     * @param   string   $prop
     * @param   mixed   $default
     * @return  mixed
     */
    function null_prevent_property($object, string $prop, $default = null)
    {
        return $object === null ? $default : $object->{$prop};
    }
}

if (!function_exists('null_prevent_function')) {

    /**
     * Run function if object isn't null.
     *
     * @param   mixed   $object
     * @param   string  $prop
     * @param   mixed   $arguments
     * @param   mixed   $default
     * @return  mixed
     */
    function null_prevent_function($object, string $function, $arguments = null, $default = null)
    {
        if ($arguments === null) {
            return $object === null ? $default : $object->{$function}();
        }

        if (is_array($arguments)) {
            return $object === null ? $default : $object->{$function}(...$arguments);
        }

        return $object === null ? $default : $object->{$function}($arguments);
    }
}

if (!function_exists('auth_company')) {
    /**
     * get the current auth company.
     *
     * @return  \App\Models\Company|null
     */
    function auth_company()
    {
        $bearerToken = request()->bearerToken();

        if ($bearerToken !== null) {
            $parser = Configuration::forUnsecuredSigner()->parser()->parse($bearerToken);

            if ($parser !== null && $parser instanceof Plain) {
                $jti = null_prevent_function($parser->claims(), 'get', ['jti']);
                $token = Token::find($jti);
                $client = null_prevent_property($token, 'client');

                return null_prevent_property($client, 'company');
            }
        }

        return null;
    }
}

if (!function_exists('request_ip')) {
    /**
     * get the current auth company.
     *
     * @return  \App\Models\Company|null
     */
    function request_ip()
    {
        $request = request();

        return $request->header('X-Forwarded-For') ?? $request->ip();
    }
}
