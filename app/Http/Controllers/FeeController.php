<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeResource;
use App\Models\Fee;
use App\Models\Loan;
use App\Models\Transaction;
use App\Traits\GenericController;
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
        return  $this->showOne($fee, $this->relations);
    }

    public function loansFeesIndex(Loan $loan)
    {
        return $this->modelRelationIndex($loan, 'fees', $this->relations);
    }

    public function transactionsFeesIndex(Transaction $transaction)
    {
        return $this->modelRelationIndex($transaction, 'fees', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
