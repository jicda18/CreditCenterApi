<?php

namespace App\Exceptions;

use App\Traits\ExceptionRenderResponse;
use Exception;

class InvalidIpFormatException extends Exception
{
    use ExceptionRenderResponse;

    public function __construct(string $ip)
    {
        $this->message = "\"{$ip}\" isn't a valid ip format. Try with one of the following format: x.x.x.x/x, x.x.x.x-y.y.y.y, x.x.x.x";
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $this->response($this->message);
    }
}
