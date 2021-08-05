<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GenericController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use GenericController;

    private $relations = [];

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
        $rules = [
            'loan_id' => ['required', 'exists:' . Loan::class],
            'bank_id' => ['required', 'exists:' . Bank::class],
            'amount' => ['required', 'numeric'],
            'currency_id' => ['required', 'exists:' . Currency::class],
            'comment' => ['required', 'string'],
        ];

        return $this->change($request, $rules, new Transaction(), true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return  $this->showOne($transaction);
    }

    public function banksTransactionsIndex(Bank $bank)
    {
        return $this->modelRelationIndex($bank, 'transactions', $this->relations);
    }

    public function loansTransactionsIndex(Loan $loan)
    {
        return $this->modelRelationIndex($loan, 'transactions', $this->relations);
    }

    public function usersTransactionsIndex(User $user)
    {
        return $this->modelRelationIndex($user, 'transactions', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [
                'loan_id' => $request->loan_id,
                'bank_id' => $request->bank_id,
                'register_user_id' => Auth::user()->user_id,
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'comment' => $request->comment,
            ];
        }

        return [];
    }
}
