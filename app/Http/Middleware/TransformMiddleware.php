<?php

namespace App\Http\Middleware;

use App\Models\Bank;
use App\Models\Business;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Debtor;
use App\Models\Fee;
use App\Models\Frequency;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\Province;
use App\Models\User;
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
        $transformerInput = $this->uuidToId($transformerInput);

        $request->replace($transformerInput);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();

            $msgs = json_decode(json_encode($data->error), true);
            if (is_array($msgs)) {
                $tMsgs = $this->errorTransform($msgs, $resource);
                $data->error = json_decode(json_encode($tMsgs));
            } else {
                $data->error = $msgs;
            }

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

    private function uuidToId($inputs)
    {
        $props = [
            'bank_id' => Bank::class,
            'business_id' => Business::class,
            'company_id' => Company::class,
            'country_id' => Country::class,
            'currency_id' => Currency::class,
            'debtor_id' => Debtor::class,
            'fee_id' => Fee::class,
            'frequency_id' => Frequency::class,
            'guarantor_id' => Guarantor::class,
            'loan_id' => Loan::class,
            'notification_id' => Notification::class,
            'permission_id' => Permission::class,
            'province_id' => Province::class,
            'user_id' => User::class,
        ];

        $transformed = collect($inputs)->map(function ($input, $prop) use ($props) {
            if (key_exists($prop, $props)) {
                $model = $props[$prop]::where('uuid', '=', $input)->first();

                if ($model === null) {
                    return -1;
                }

                return $model->{$prop};
            }

            return $input;
        })->all();

        return $transformed;
    }
}
