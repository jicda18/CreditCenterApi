<?php

namespace App\Http\Controllers;

use App\Models\Frequency;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
    }

    public function calculator(Request $request)
    {
        $rules = [
            'balance' => ['required', 'numeric', 'min:0'],
            'interest' => ['required', 'numeric', 'min:0', 'max:1'],
            'fees' => ['requiredIf:type,interest_capital', 'numeric', 'min:1'],
            'frequency_key' => ['required', Rule::exists('tbFrequencies', 'uuid')->whereNull('deleted_at')],
            'start_at' => ['required', 'date'],
        ];

        $this->validate($request, $rules);

        $data = get_fees_detail(Loan::INTEREST_CAPITAL, $request->balance, $request->interest, $request->fees);

        /**
         * @var \App\Models\Frequency
         */
        $frequency = Frequency::where('uuid', '=', $request->frequency_key)->first();
        $paid_at_key = 'paid_at';

        if (count($data) > 1) {
            $data[0][$paid_at_key] = Carbon::createFromTimestamp(strtotime($request->start_at));

            for ($i = 1; $i < count($data); $i++) {
                $preview = $data[$i - 1][$paid_at_key];

                $data[$i][$paid_at_key] = $frequency->nextDate($preview);
            }
        }

        return response()->json(['data' => $data], 200);
    }
}
