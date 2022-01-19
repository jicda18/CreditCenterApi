<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class TransactionResource extends ApiResource
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
        $bank_uuid = $this->getRelationUuid('bank');
        $register_uuid = $this->getRelationUuid('register');
        $currency_uuid = $this->getRelationUuid('currency');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'loan_key' => $loan_uuid,
            'bank_key' => $bank_uuid,
            'cash' => $this->is_cash,
            'currency_key' => $currency_uuid,
            'register_key' => $register_uuid,
            'was_at' => $this->was_at,
            'amount' => $this->amount,
            'comments' => $this->comment,
            'loan_amount' => $this->loan_amount,
            'bank_amount' => $this->bank_amount,
            'loan_balance' => $this->loan_balance,
            'bank_balance' => $this->bank_balance,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'loan' => new LoanResource($this->whenLoaded('loan')),
            'bank' => new BankResource($this->whenLoaded('bank')),
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
            'register' => new UserResource($this->whenLoaded('register')),
            'fees' => FeeResource::collection($this->whenLoaded('fees')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $this->resource),
                ],
                [
                    'rel' => 'loan',
                    'href' => route('loans.show', $loan_uuid),
                ],
                [
                    'rel' => 'bank',
                    'href' => route('banks.show', $bank_uuid),
                ],
                [
                    'rel' => 'currency',
                    'href' => route('currencies.show', $currency_uuid),
                ],
                [
                    'rel' => 'register',
                    'href' => route('users.show', $register_uuid),
                ],
                [
                    'rel' => 'fees',
                    'href' => route('transactions.fees.index', $this->resource),
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
            'loan_key' => 'loan_id',
            'bank_key' => 'bank_id',
            'cash' => 'is_cash',
            'currency_key' => 'currency_id',
            'register_key' => 'register_id',
            'was_at' => 'was_at',
            'amount' => 'amount',
            'comments' => 'comment',
            'loan_amount' => 'loan_amount',
            'bank_amount' => 'bank_amount',
            'loan_balance' => 'loan_balance',
            'bank_balance' => 'bank_balance',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
