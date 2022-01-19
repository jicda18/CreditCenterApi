<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class LoanResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $debtor_uuid = $this->getRelationUuid('debtor');
        $currency_uuid = $this->getRelationUuid('currency');
        $frequency_uuid = $this->getRelationUuid('frequency');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'debtor_key' => $debtor_uuid,
            'currency_key' => $currency_uuid,
            'frequency_key' => $frequency_uuid,
            'fee_amount' => $this->fee_amount,
            'start_at' => $this->start_on,
            'thanks_days' => $this->thanks_days,
            'next_at' => $this->next_on,
            'status' => $this->status,
            'summary' => $this->balance,
            'interest_summary' => $this->interest_balance,
            'amount' => $this->amount,
            'type' => $this->type,
            'interest' => $this->interest,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'debtor' => new DebtorResource($this->whenLoaded('debtor')),
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
            'frequency' => new FrequencyResource($this->whenLoaded('frequency')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'fees' => FeeResource::collection($this->whenLoaded('fees')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('loans.show', $this->resource),
                ],
                [
                    'rel' => 'debtor',
                    'href' => route('debtors.show', $debtor_uuid),
                ],
                [
                    'rel' => 'currency',
                    'href' => route('currencies.show', $currency_uuid),
                ],
                [
                    'rel' => 'frequency',
                    'href' => route('frequencies.show', $frequency_uuid),
                ],
                [
                    'rel' => 'transactions',
                    'href' => route('loans.transactions.index', $this->resource),
                ],
                [
                    'rel' => 'fees',
                    'href' => route('loans.fees.index', $this->resource),
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
            'debtor_key' => 'debtor_id',
            'currency_key' => 'currency_id',
            'frequency_key' => 'frequency_id',
            'fee_amount' => 'fee_amount',
            'amount' => 'amount',
            'start_at' => 'start_on',
            'thanks_days' => 'thanks_days',
            'next_at' => 'next_on',
            'status' => 'status',
            'type' => 'type',
            'interest' => 'interest',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',

            'fees' => 'fees',
            'type' => 'type',
            'interest' => 'interest',

            'bank_key' => 'bank_id',
            'currency_key' => 'currency_id',
            'balance' => 'balance',
            'cash' => 'is_cash',
        ]);
    }
}
