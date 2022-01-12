<?php

use App\Models\Frequency;
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

if (!function_exists('request_ip')) {
    /**
     * get the request ip
     * @return string
     */
    function request_ip()
    {
        $request = request();

        return $request->header('X-Forwarded-For') ?? $request->ip();
    }
}

if (!function_exists('get_fee_amount')) {
    /**
     * get the fee amount.
     *
     * @return  float
     */
    function get_fee_amount(string $type, float $balance, float $interest, int $fees = 1)
    {
        if ($type === 'interest_capital') {
            $temp = pow(1 + $interest, $fees);

            return round(($balance * $interest * $temp) / ($temp - 1), 2);
        }

        return round($balance * $interest, 2);
    }
}
