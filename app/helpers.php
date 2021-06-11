<?php

if (!function_exists('null_prevent')) {
    /**
     * Get property parameter if object isn't null.
     *
     * @param   mixed   $object
     * @param   string   $prop
     * @param   mixed   $default
     * @return  mixed
     */
    function null_prevent(mixed $object, string $prop, mixed $default = null): mixed
    {
        return $object === null ? $default : $object->{$prop};
    }
}
