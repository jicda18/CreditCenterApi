<?php

namespace App\Http\Controllers;

use App\Http\Resources\GuarantorResource;
use App\Models\Company;
use App\Models\Guarantor;
use App\Models\Province;
use App\Rules\NotPresent;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuarantorController extends Controller
{
    use GenericController;

    private $relations = ['province', 'debtors'];

    public function __construct()
    {
        $this->resource = GuarantorResource::class;
        $this->default_key_sort = 'guarantor_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Guarantor::query(), $this->relations);

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
            'company_id' => [new NotPresent()],
            'fullname' => ['required', 'string'],
            'personal_id' => ['required', 'string'],
            'gender' => ['required', 'in:female,male'],
            'province_id' => ['required', Rule::exists('tbProvinces', 'uuid')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'address' => ['required', 'string'],
            'phone' => ['digits:10'],
            'occupation' => ['required', 'string'],
            'workplace' => ['required', 'string'],
            'salary' => ['required', 'numeric'],
            'other_income' => ['numeric'],
        ];

        return $this->change($request, $rules, new Guarantor(), true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function show(Guarantor $guarantor)
    {
        return  $this->showOne($guarantor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarantor $guarantor)
    {
        $rules = [
            'company_id' => [new NotPresent()],
            'fullname' => ['string'],
            'personal_id' => ['string'],
            'gender' => ['in:female,male'],
            'province_id' => [Rule::exists('tbProvinces', 'uuid')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'address' => ['string'],
            'phone' => ['digits:10'],
            'occupation' => ['string'],
            'workplace' => ['string'],
            'salary' => ['numeric'],
            'other_income' => ['numeric'],
        ];

        return $this->change($request, $rules, $guarantor, false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarantor $guarantor)
    {
        $guarantor->delete();

        return  $this->showOne($guarantor);
    }

    public function companiesGuarantorsIndex(Company $company)
    {
        return $this->modelRelationIndex($company, 'guarantors', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        $arr = $request->only(['fullname', 'personal_id', 'gender', 'address', 'phone', 'occupation', 'workplace', 'salary', 'other_income']);

        if ($store) {
            $arr['company_id'] = auth_company()->company_id;
        }

        if ($request->has('province_id')) {
            $arr['province_id'] = Province::where('uuid', '=', $request->province_id)->first()->province_id;
        }

        return $arr;
    }
}
