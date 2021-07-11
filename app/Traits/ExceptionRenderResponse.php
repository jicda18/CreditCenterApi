<?php

namespace App\Traits;

trait ExceptionRenderResponse
{

    protected function response($message, $code = 400)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function formatted(string $msg, array $arg = []): string
    {
        return sprintf($msg, ...$arg);
    }
}
