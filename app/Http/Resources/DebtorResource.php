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
        $province_uuid = $this->getRelationUuid('province');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'guarantor' => $this->guarantor,
            'guarantor_email' => $this->guarantor_email,
            'guarantor_phone' => $this->guarantor_phone,
            'guarantor_personal_id' => $this->guarantor_personal_id,
            'business' => $this->business,
            'province_key' => $province_uuid,
            'name' => $this->fullname,
            'nick_name' => $this->nickname,
            'personal_id' => $this->personal_id,
            'gender' => $this->gender,
            'street' => $this->address,
            'dob' => $this->date_of_birth,
            'phone_number' => $this->phone,
            'mail' => $this->email,
            'marital_status' => $this->marital_status,
            'occupation' => $this->occupation,
            'income' => $this->salary,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'loans' => LoanResource::collection($this->whenLoaded('loans')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('debtors.show', $this->resource),
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
            'guarantor' => 'guarantor',
            'guarantor_email' => 'guarantor_email',
            'guarantor_phone' => 'guarantor_phone',
            'guarantor_personal_id' => 'guarantor_personal_id',
            'business' => 'business',
            'province_key' => 'province_id',
            'name' => 'fullname',
            'nick_name' => 'nickname',
            'personal_id' => 'personal_id',
            'gender' => 'gender',
            'street' => 'address',
            'dob' => 'date_of_birth',
            'phone_number' => 'phone',
            'mail' => 'email',
            'marital_status' => 'marital_status',
            'occupation' => 'occupation',
            'income' => 'salary',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',

            'currency_key' => 'currency_id',
            'frequency_key' => 'frequency_id',
            'fees' => 'fees',
            'start_at' => 'start_on',
            'thanks_days' => 'thanks_days',
            'type' => 'type',
            'interest' => 'interest',

            'bank_key' => 'bank_id',
            'balance' => 'balance',
            'cash' => 'is_cash',
        ]);
    }
}
