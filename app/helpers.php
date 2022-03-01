<?php

use App\Exceptions\PermissionRequiredException;
use App\Mail\Basic;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

const precision = 2;

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

if (!function_exists('apply_interest')) {
    /**
     * calcule the interest for a period.
     *
     * @return  float
     */
    function apply_interest(float $balance, float $interest)
    {
        return round($balance * $interest, precision);
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
        if ($type === Loan::INTEREST_CAPITAL) {
            if ($interest === (float) 0) {
                return round($balance / $fees, precision);
            }

            $temp = pow(1 + $interest, $fees);

            return round(($balance * $interest * $temp) / ($temp - 1), precision);
        }

        return apply_interest($balance, $interest);
    }
}

if (!function_exists('get_fees_detail')) {
    /**
     * get the fees details.
     *
     * @return  float|array
     */
    function get_fees_detail(string $type, float $balance, float $interest, int $fees = 1)
    {
        if ($type === Loan::INTEREST_CAPITAL) {
            $array = [];
            $capital = $balance;

            for ($i = $fees; $i > 0; $i--) {
                $fee_amount = get_fee_amount($type, $capital, $interest, $i);

                $interest_amount = round($capital * $interest, precision);
                $payment = round($fee_amount - $interest_amount, precision);
                $pending = round($capital - $payment, precision);

                $array[] = [
                    'preview' => $capital,
                    'amount' => $fee_amount,
                    'interest' => $interest_amount,
                    'payment' => $payment,
                    'pending' => $pending,
                ];

                $capital = $pending;
            }

            return $array;
        }

        return apply_interest($balance, $interest);
    }
}

if (!function_exists('user')) {
    /**
     * get the fees details.
     *
     * @return  \App\Models\User | null
     */
    function user(): ?User
    {
        return Auth::user();
    }
}

if (!function_exists('required_permission')) {
    /**
     * get the fees details.
     *
     * @return  \App\Models\User | null
     */
    function required_permission(string $permission_name)
    {
        if (!user()->can($permission_name)) {
            throw new PermissionRequiredException($permission_name);
        }
    }
}

if (!function_exists('send_email')) {
    /**
     * get the fees details.
     *
     * @return  \App\Models\User | null
     */
    function send_email(string $name, string $subject, string $body, array $to, array $attaches = null, array $cc = [], array $bcc = [])
    {
        $message = new Basic($name, $subject, $subject, $body, $attaches);

        $mail = Mail::to($to)->cc($cc)->bcc($bcc);

        $mail->send($message);
    }
}
