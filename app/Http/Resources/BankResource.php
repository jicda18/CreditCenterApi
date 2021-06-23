<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class BankResource extends ApiResource
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
        $currency_uuid = $this->getRelationUuid('currency');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'company_key' => $company_uuid,
            'currency_key' => $currency_uuid,
            'bank' => $this->bank_name,
            'account' => $this->account_number,
            'nickname' => $this->alias,
            'summary' => $this->balance,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('banks.show', $this->resource),
                ],
                [
                    'rel' => 'company',
                    'href' => route('companies.show', $company_uuid),
                ],
                [
                    'rel' => 'currency',
                    'href' => route('currencies.show', $currency_uuid),
                ],
                [
                    'rel' => 'transactions',
                    'href' => route('banks.transactions.index', $this->resource),
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
            'bank' => 'bank_name',
            'account' => 'account_number',
            'nickname' => 'alias',
            'summary' => 'balance',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
