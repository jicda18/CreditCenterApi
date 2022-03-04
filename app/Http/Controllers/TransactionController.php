<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Bank;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GenericController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use GenericController;

    private $relations = ['loan', 'bank', 'register', 'fees', 'currency'];

    public function __construct()
    {
        $this->resource = TransactionResource::class;
        $this->default_key_sort = 'transaction_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        required_permission('transactions.view');

        $query = $this->includeRelations(Transaction::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        required_permission('transactions.store');

        $rules = [
            'loan_id' => ['required', 'exists:' . Loan::class],
            'is_cash' => ['required', 'boolean'],
            'bank_id' => ['required_if:is_cash,0', 'nullable', 'exists:' . Bank::class],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:regular,capital,total'],
            'comment' => ['required', 'string'],
        ];

        return $this->change($request, $rules, new Transaction(), true, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        required_permission('transactions.view');

        return  $this->showOne($transaction, $this->relations);
    }

    public function banksTransactionsIndex(Bank $bank)
    {
        required_permission('transactions.view');

        return $this->modelRelationIndex($bank, 'transactions', $this->relations);
    }

    public function loansTransactionsIndex(Loan $loan)
    {
        required_permission('transactions.view');

        return $this->modelRelationIndex($loan, 'transactions', $this->relations);
    }

    public function usersTransactionsIndex(User $user)
    {
        required_permission('transactions.view');

        return $this->modelRelationIndex($user, 'transactions', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            $currency_id = (bool) $request->is_cash ? Loan::find($request->loan_id)->currency_id : Bank::find($request->bank_id)->currency_id;

            return [
                'loan_id' => $request->loan_id,
                'is_cash' => $request->is_cash,
                'bank_id' => $request->bank_id,
                'register_user_id' => Auth::user()->user_id,
                'amount' => -$request->amount,
                'currency_id' => $currency_id,
                'comment' => $request->comment,
                'type' => $request->type,
            ];
        }

        return [];
    }

    protected function otherValidations(Request $request, Model $model, bool $store): ?array
    {
        $errors = [];

        $loan = Loan::find($request->loan_id);


        // Todo: validate if loan is active
        // Todo: validate amount if max loan total

        if ($request->type === 'total') {
            $total = $loan->total;
            if ($request->amount != $total) {
                $formatTotal = '$' . number_format($total, precision);
                $errors['amount'] = ["El monto es diferente al total de {$formatTotal}."];
            }
        }

        if ($request->type === 'capital') {
            if ($loan->pending_balance) {
                $errors['type'] = ["Prestamo con balance pendiente."];
            }
        }

        return $errors;
    }
}
