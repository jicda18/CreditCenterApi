<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessResource;
use App\Models\Business;
use App\Models\Province;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    use GenericController;

    private $relations = ['province'];

    public function __construct()
    {
        $this->resource = BusinessResource::class;
        $this->default_key_sort = 'business_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Business::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        return $this->forSelect(Business::all(), function (Business $business) {
            return [
                'value' => $business->uuid,
                'label' => $business->name,
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
        $rules = [
            'name' => ['required', 'string'],
            'type' => ['required', 'in:own,rent'],
            'number_of_employee' => ['integer'],
            'phone' => ['required', 'digits:10'],
            'province_id' => ['required', 'exists:' . Province::class],
            'address' => ['required', 'string'],
            'comment' => ['string'],
        ];

        return $this->change($request, $rules, new Business(), true, $this->relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        return  $this->showOne($business, $this->relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        $rules = [
            'name' => ['string'],
            'type' => ['in:own,rent'],
            'number_of_employee' => ['integer'],
            'phone' => ['digits:10'],
            'province_id' => ['exists:' . Province::class],
            'address' => ['string'],
            'comment' => ['string'],
        ];

        return $this->change($request, $rules, $business, false, $this->relations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return  $this->showOne($business, []);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        $arr = $request->only(['name', 'type', 'phone', 'address', 'province_id']);

        if ($store) {
            $arr['number_of_employee'] = $request->number_of_employee ?? 1;
            $arr['comment'] = $request->comment ?? '';
        } else {
            if ($request->has('number_of_employee')) {
                $arr['number_of_employee'] = $request->number_of_employee;
            }

            if ($request->has('comment')) {
                $arr['comment'] = $request->comment;
            }
        }

        return $arr;
    }
}
