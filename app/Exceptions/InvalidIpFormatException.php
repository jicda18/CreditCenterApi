<?php

namespace App\Exceptions;

use Exception;

class InvalidIpFormatException extends Exception
{
    public function __construct(string $ip)
    {
        $this->message = "\"{$ip}\" isn't a valid ip format. Try with one of the following format: x.x.x.x/x, x.x.x.x-y.y.y.y, x.x.x.x";
    }
}
