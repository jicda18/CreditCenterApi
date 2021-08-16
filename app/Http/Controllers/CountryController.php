<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = CountryResource::class;
        $this->default_key_sort = 'country_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Country::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return  $this->showOne($country, $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
