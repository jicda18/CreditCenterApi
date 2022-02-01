<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeResource;
use App\Models\Fee;
use App\Models\Loan;
use App\Models\Transaction;
use App\Traits\GenericController;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = FeeResource::class;
        $this->default_key_sort = 'fee_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        required_permission('fee.view');

        $query = $this->includeRelations(Fee::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)
    {
        required_permission('fee.view');

        return  $this->showOne($fee, $this->relations);
    }

    public function loansFeesIndex(Loan $loan)
    {
        required_permission('fee.view');

        return $this->modelRelationIndex($loan, 'fees', $this->relations);
    }

    public function transactionsFeesIndex(Transaction $transaction)
    {
        required_permission('transation.view');

        return $this->modelRelationIndex($transaction, 'fees', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }

    protected function cxc(Request $request)
    {
        required_permission('fee.view');

        $dte = Carbon::now()->addDays(env('CXC_DAYS', 5))->endOfDay();

        $fees = Fee::whereNull('paid_at')->where('payment_on', '<=', $dte)
            ->with(['loan', 'loan.debtor', 'loan.currency', 'loan.fees'])
            ->orderBy('payment_on')
            ->get()

            ->map(function (Fee $fee) use ($request) {
                return (new $this->resource($fee))->response()->getData()->data;
            })
            ->groupBy('loan_key')
            ->values();

        return response()->json([
            'data' => $fees,
        ], 200);
    }
}
