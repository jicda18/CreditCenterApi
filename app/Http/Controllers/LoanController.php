<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Models\Bank;
use App\Models\Debtor;
use App\Models\Frequency;
use App\Models\Loan;
use App\Models\Transaction;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    use GenericController;

    private $relations = ['currency', 'debtor', 'fees', 'frequency', 'transactions'];

    public function __construct()
    {
        $this->resource = LoanResource::class;
        $this->default_key_sort = 'loan_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Loan::query(), $this->relations);

        return $this->idx($query);
    }


    public function update(Request $request, Loan $loan)
    {
        $rules = [
            'currency_id' => [new NotPresent],
            'frequency_id' => ['exists:' . Frequency::class],
            'fee_amount' => ['numeric'],
            'thanks_days' => ['integer'],
            'next_on' => [new NotPresent],
            'status' => ['in:active,settled,paused,canceled'],
        ];

        return $this->change($request, $rules, $loan, false, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        return  $this->showOne($loan, $this->relations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'debtor_id' => ['required', 'exists:' . Debtor::class],
            'start_on' => ['required', 'date'],
            'thanks_days' => ['nullable', 'integer'],
            'frequency_id' => ['required', 'exists:' . Frequency::class],
            'bank_id' => ['required', 'exists:' . Bank::class],
            'type' => ['required', 'in:fixed_interest,interest_capital'],
            'interest' => ['required', 'numeric', 'min:0'],
            'balance' => ['required', 'numeric', 'min:0'],
            'fees' => ['requiredIf:type,interest_capital', 'numeric'],
        ];

        return $this->change($request, $rules, new Loan(), true, $this->relations);
    }

    public function debtorsLoansIndex(Debtor $debtor)
    {
        return $this->modelRelationIndex($debtor, 'loans', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            $bank = Bank::find($request->bank_id);
            $fee_amount = get_fee_amount($request->type, $request->balance, $request->interest, $request->fees ?? 1);

            return [
                'debtor_id' => $request->debtor_id,
                'currency_id' => $bank->currency_id,
                'fee_amount' => $fee_amount,
                'start_on' => $request->start_on,
                'thanks_days' => $request->thanks_days ?? 0,
                'next_on' => $request->start_on,
                'frequency_id' => $request->frequency_id,
                'status' => 'active',
                'amount' => $request->balance,
                'type' => $request->type,
                'interest' => $request->interest,
            ];
        }

        return [];
    }

    protected function afterToSave(Request $request, Model $model, bool $store): Model
    {
        if ($store) {
            $transaction = Transaction::create([
                'loan_id' => $model->loan_id,
                'bank_id' => $request->bank_id,
                'register_user_id' => Auth::user()->user_id,
                'amount' => $request->balance,
                'currency_id' => $model->currency_id,
                'comment' => 'Nuevo prestamo para ' . $model->fullname,
            ]);
        }

        return $model;
    }
}
