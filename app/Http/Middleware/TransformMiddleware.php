<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $resource)
    {
        $requestInput = $request->all();
        $transformerInput = $resource::toModelKeysArray($requestInput);
        $request->replace($transformerInput);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();

            $msgs = json_decode(json_encode($data->error), true);
            $tMsgs = $this->errorTransform($msgs, $resource);

            $data->error = json_decode(json_encode($tMsgs));
            $response->setData($data);
        }

        return $response;
    }

    private function errorTransform(array $vMsg, $resource): array
    {
        $tMsgs = [];
        foreach ($vMsg as $key => $msg) {
            if ($resource::modelHas($key)) {
                $tKey = $resource::toResourceAttribute($key);

                $tMsg = str_replace(str_replace("_", " ", $key), str_replace("_", " ", $tKey), $msg);

                $tMsgs[$tKey] = $tMsg;
            } else {
                $tMsgs[$key] = $msg;
            }
        }

        return $tMsgs;
    }
}
