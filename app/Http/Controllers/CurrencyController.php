<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CurrencyController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = CurrencyResource::class;
        $this->default_key_sort = 'currency_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        required_permission('currency.view');

        $query = $this->includeRelations(Currency::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        required_permission('currency.view');

        return $this->forSelect(Currency::all(), function (Currency $currency) {
            return [
                'value' => $currency->uuid,
                'label' => $currency->name,
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        required_permission('currency.store');

        $rules =  [
            'name' => ['required', 'string'],
            'symbol' => ['required', 'string'],
            'default' => [new NotPresent()],
            'exchange_value' => ['required', 'numeric'],
        ];

        return $this->change($request, $rules, new Currency(), true, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        required_permission('currency.view');

        return  $this->showOne($currency, $this->relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        required_permission('currency.update');

        $rules =  [
            'name' => ['string'],
            'symbol' => ['string'],
            'default' => ['boolean'],
            'exchange_value' => ['numeric'],
        ];

        return $this->change($request, $rules, $currency, false, $this->relations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        required_permission('currency.delete');

        if ($currency->banks()->count() > 0) {
            throw ValidationException::withMessages(['delete' => "Can't delete {$currency->name} because is assigned in some banks."]);
        }

        // if ($currency->transactions()->count() > 0) {
        //     throw ValidationException::withMessages(['delete' => "Can't delete {$currency->name} because is assigned in some transactions."]);
        // }

        if ($currency->loans()->count() > 0) {
            throw ValidationException::withMessages(['delete' => "Can't delete {$currency->name} because is assigned in some loans."]);
        }

        $currency->delete();

        return  $this->showOne($currency, []);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        if ($store) {
            return [
                'name' => $request->name,
                'symbol' => $request->symbol,
                'default' => false,
                'exchange_value' => $request->exchange_value,
            ];
        }

        return $request->only(['name', 'symbol', 'default', 'exchange_value']);
    }
}
