<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Currency;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    use GenericController;

    private $relations = ['currency', 'transactions'];

    public function __construct()
    {
        $this->resource = BankResource::class;
        $this->default_key_sort = 'bank_id';

        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth_company();

        $query = $this->includeRelations($company->banks()->getQuery(), $this->relations);

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
            'currency_id' => ['required', 'exists:' . Currency::class],
            'bank_name' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'alias' => ['required', 'string'],
            'balance' => [new NotPresent()],
        ];

        return $this->change($request, $rules, new Bank(), true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        return  $this->showOne($bank);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $rules = [
            'currency_id' => [new NotPresent()],
            'bank_name' => ['string'],
            'account_number' => ['string'],
            'alias' => ['string'],
            'balance' => [new NotPresent()],
        ];

        return $this->change($request, $rules, $bank, false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return  $this->showOne($bank);
    }

    public function companiesBanksIndex(Company $company)
    {
        return $this->modelRelationIndex($company, 'banks', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        $arr = $request->only(['bank_name', 'account_number', 'alias', 'currency_id']);

        if ($store) {
            $arr['company_id'] = auth_company()->company_id;
            $arr['balance'] = 0;
        }

        return $arr;
    }
}
