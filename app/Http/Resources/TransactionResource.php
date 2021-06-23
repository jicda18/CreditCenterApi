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
        $loan_uuid = $this->getResourceKey('loan');
        $bank_uuid = $this->getResourceKey('bank');
        $register_uuid = $this->getResourceKey('register');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'loan_key' => $loan_uuid,
            'bank_key' => $bank_uuid,
            'register_key' => $register_uuid,
            'was_at' => $this->was_at,
            'amount' => $this->amount,
            'comments' => $this->comment,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'loan' => new LoanResource($this->whenLoaded('loan')),
            'bank' => new BankResource($this->whenLoaded('bank')),
            'register' => new UserResource($this->whenLoaded('register')),
            'fees' => FeeResource::collection($this->whenLoaded('fees')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $this->resource),
                ],
                [
                    'rel' => 'loan',
                    'href' => route('loan.show', $loan_uuid),
                ],
                [
                    'rel' => 'bank',
                    'href' => route('bank.show', $bank_uuid),
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
            'was_at' => 'was_at',
            'amount' => 'amount',
            'comments' => 'comment',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
