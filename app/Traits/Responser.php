<?php

namespace App\Traits;

trait Responser
{
    protected function json($payload, $code)
    {
        return response()->json($payload, $code);
    }

    protected function success($data, $code = 200)
    {
        return $this->json(['data' => $data], $code);
    }

    protected function error($data, $code = 422)
    {
        // Todo: add error relation data
        return $this->json(['error' => $data], $code);
    }
}
