<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class GuarantorResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $province_uuid = $this->getRelationUuid('province');
        $company_uuid = $this->getRelationUuid('company');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'province_key' => $province_uuid,
            'company_key' => $company_uuid,
            'name' => $this->fullname,
            'gender' => $this->gender,
            'street' => $this->address,
            'phone_number' => $this->phone,
            'occupation' => $this->occupation,
            'workplace' => $this->workplace,
            'income' => $this->salary,
            'other_income' => $this->other_income,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'debtors' => DebtorResource::collection($this->whenLoaded('debtors')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('guarantors.show', $this->resource),
                ],
                [
                    'rel' => 'province',
                    'href' => route('provinces.show', $province_uuid),
                ],
                [
                    'rel' => 'company',
                    'href' => route('companies.show', $company_uuid),
                ],
                [
                    'rel' => 'debtors',
                    'href' => route('guarantors.debtors.index', $this->resource),
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
            'name' => 'fullname',
            'gender' => 'gender',
            'street' => 'address',
            'phone_number' => 'phone',
            'occupation' => 'occupation',
            'workplace' => 'workplace',
            'income' => 'salary',
            'other_income' => 'other_income',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
