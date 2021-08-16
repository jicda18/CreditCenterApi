<?php

namespace App\Http\Controllers;

use App\Http\Resources\FrequencyResource;
use App\Models\Frequency;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class FrequencyController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = FrequencyResource::class;
        $this->default_key_sort = 'frequency_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Frequency::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        return $this->forSelect(Frequency::all(), function (Frequency $frequency) {
            return [
                'value' => $frequency->uuid,
                'label' => $frequency->label,
            ];
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frequency  $frequency
     * @return \Illuminate\Http\Response
     */
    public function show(Frequency $frequency)
    {
        return  $this->showOne($frequency, $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
