<?php

namespace App\Http\Controllers;

use App\Classes\MailableObject;
use App\Models\Frequency;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
    }

    private function calculatorDate($balance, $interest, $fees, $frequency_key, $start_at)
    {
        $data = get_fees_detail(Loan::INTEREST_CAPITAL, $balance, $interest, $fees);

        /**
         * @var \App\Models\Frequency
         */
        $frequency = Frequency::where('uuid', '=', $frequency_key)->first();
        $paid_at_key = 'paid_at';

        if (count($data) > 1) {
            $data[0][$paid_at_key] = Carbon::createFromTimestamp(strtotime($start_at));

            for ($i = 1; $i < count($data); $i++) {
                $preview = $data[$i - 1][$paid_at_key];

                $data[$i][$paid_at_key] = $frequency->nextDate($preview);
            }
        }

        return $data;
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

        $data = $this->calculatorDate($request->balance, $request->interest, $request->fees, $request->frequency_key, $request->start_at);

        return response()->json(['data' => $data], 200);
    }

    public function calculatorEmail(Request $request)
    {
        $rules = [
            'balance' => ['required', 'numeric', 'min:0'],
            'interest' => ['required', 'numeric', 'min:0', 'max:1'],
            'fees' => ['requiredIf:type,interest_capital', 'numeric', 'min:1'],
            'frequency_key' => ['required', Rule::exists('tbFrequencies', 'uuid')->whereNull('deleted_at')],
            'start_at' => ['required', 'date'],
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
        ];

        $this->validate($request, $rules);

        $data = $this->calculatorDate($request->balance, $request->interest, $request->fees, $request->frequency_key, $request->start_at);

        $subject = 'Cotización Prestamos';
        $body = "<div class=\"table\">\n";
        $body .= Markdown::parse($this->toMarkdownTable($data));
        $body .= "\n</div>\n";

        send_email($request->name, $subject, $body, [new MailableObject($request->name, $request->email)]);

        return response()->json(['data' => $data], 200);
    }

    private function toMarkdownTable($data): string
    {
        if (!is_array($data)) {
            return 'Interes: $' . number_format($data, precision);
        }

        $table = "| | Fecha | Anterior | Couta | Capital | Interes | Balance |\n";
        $table .= "| :---: | :---: | :---: | :---: | :---: | :---: | :---: |\n";

        $c = count($data);
        for ($i = 0; $i < $c; $i++) {
            $item = $data[$i];

            $index = $i + 1;
            $preview = '$' . number_format($item['preview'], precision);
            $amount = '$' . number_format($item['amount'], precision);
            $interest = '$' . number_format($item['interest'], precision);
            $payment = '$' . number_format($item['payment'], precision);
            $pending = '$' . number_format($item['pending'], precision);
            $paid_at = $item['paid_at']->isoFormat('MM/DD/YYYY');

            $table .= "| $index | $paid_at | $preview | $amount | $payment | $interest | $pending |\n";
        }

        return $table;
    }
}
