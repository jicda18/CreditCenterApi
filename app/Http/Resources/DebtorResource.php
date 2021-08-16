<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class DebtorResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $company_uuid = $this->getRelationUuid('company');
        $guarantor_uuid = $this->getRelationUuid('guarantor');
        $business_uuid = $this->getRelationUuid('business');
        $province_uuid = $this->getRelationUuid('province');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'company_key' => $company_uuid,
            'guarantor_key' => $guarantor_uuid,
            'business_key' => $business_uuid,
            'province_key' => $province_uuid,
            'name' => $this->fullname,
            'nick_name' => $this->nickname,
            'personal_id' => $this->personal_id,
            'gender' => $this->gender,
            'street' => $this->address,
            'dob' => $this->date_of_birth,
            'phone_number' => $this->phone,
            'marital_status' => $this->marital_status,
            'occupation' => $this->occupation,
            'income' => $this->salary,
            'other_income' => $this->other_income,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'guarantor' => new GuarantorResource($this->whenLoaded('guarantor')),
            'business' => new BusinessResource($this->whenLoaded('business')),
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'loans' => LoanResource::collection($this->whenLoaded('loans')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('debtors.show', $this->resource),
                ],
                [
                    'rel' => 'company',
                    'href' => route('companies.show', $company_uuid),
                ],
                [
                    'rel' => 'guarantor',
                    'href' => route('guarantors.show', $guarantor_uuid),
                ],
                [
                    'rel' => 'business',
                    'href' => route('businesses.show', $business_uuid),
                ],
                [
                    'rel' => 'province',
                    'href' => route('provinces.show', $province_uuid),
                ],
                [
                    'rel' => 'loans',
                    'href' => route('debtors.loans.index', $this->resource),
                ],
            ],
        ]);
    }

    /**
     * Get hash resource's keys to model's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hash(): Collection
    {
        return collect([
            'key' => 'uuid',
            'guarantor_key' => 'guarantor_id',
            'business_key' => 'business_id',
            'province_key' => 'province_id',
            'name' => 'fullname',
            'nick_name' => 'nickname',
            'personal_id' => 'personal_id',
            'gender' => 'gender',
            'street' => 'address',
            'dob' => 'date_of_birth',
            'phone_number' => 'phone',
            'marital_status' => 'marital_status',
            'occupation' => 'occupation',
            'income' => 'salary',
            'other_income' => 'other_income',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
