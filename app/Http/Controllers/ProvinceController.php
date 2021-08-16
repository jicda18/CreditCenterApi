<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvinceResource;
use App\Models\Country;
use App\Models\Province;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = ProvinceResource::class;
        $this->default_key_sort = 'province_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Province::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        return $this->forSelect(Province::all(), function (Province $province) {
            return [
                'value' => $province->uuid,
                'label' => $province->name,
            ];
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function show(Province $province)
    {
        return  $this->showOne($province, $this->relations);
    }

    public function countriesProvincesIndex(Country $country)
    {
        return $this->modelRelationIndex($country, 'provinces', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
