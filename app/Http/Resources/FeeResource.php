<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class FeeResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $loan_uuid = $this->getRelationUuid('loan');
        $transaction_uuid = $this->getRelationUuid('transaction');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'loan' => $loan_uuid,
            'transaction' => $transaction_uuid,
            'payment_at' => $this->payment_on,
            'amount' => $this->amount,
            'limit_at' => $this->limit_on,
            'paid_at' => $this->paid_at,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'loan' => new LoanResource($this->whenLoaded('loan')),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('fees.show', $this->resource),
                ],
                [
                    'rel' => 'loan',
                    'href' => route('loans.show', $loan_uuid),
                ],
                [
                    'rel' => 'transaction',
                    'href' => route('transactions.show', $transaction_uuid),
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
            'payment_at' => 'payment_on',
            'amount' => 'amount',
            'limit_at' => 'limit_on',
            'paid_at' => 'paid_at',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
