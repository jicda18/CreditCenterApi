<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Models\Debtor;
use App\Models\Frequency;
use App\Models\Loan;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    use GenericController;

    private $relations = [];

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

    // Todo: update
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

    public function debtorsLoansIndex(Debtor $debtor)
    {
        return $this->modelRelationIndex($debtor, 'loans', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [];
        }

        return $request->only(['frequency_id', 'thanks_days', 'status', 'fee_amount']);
    }
}
