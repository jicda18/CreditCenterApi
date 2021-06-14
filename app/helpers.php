<?php

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
