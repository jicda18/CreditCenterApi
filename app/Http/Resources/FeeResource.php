<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

        $now = Carbon::now();
        $payment_on = $this->payment_on->copy()->startOfDay();
        $limit_on = $this->limit_on->copy()->startOfDay();
        $status = $this->paid_at !== null ? 'paid' : ($payment_on->gt($now) ? 'in-time' : ($limit_on->gt($now) ? 'in-thanks' : 'overdue'));

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'loan_key' => $loan_uuid,
            'payment_at' => $this->payment_on,
            'amount' => $this->amount,
            'interest' => $this->interest,
            'capital' => $this->capital,
            'late_payment' => $this->late_payment,
            'paid_interest' => $this->paid_interest,
            'paid_capital' => $this->paid_capital,
            'paid_late_payment' => $this->paid_late_payment,
            'pending_interest' => $this->pending_interest,
            'pending_capital' => $this->pending_capital,
            'pending_late_payment' => $this->pending_late_payment,
            'balance' => $this->balance,
            'paid' => $this->paid,
            'pending' => $this->pending,
            'extra' => $this->extra,
            'limit_at' => $this->limit_on,
            'paid_at' => $this->paid_at,
            'status' => $status,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'loan' => new LoanResource($this->whenLoaded('loan')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('fees.show', $this->resource),
                ],
                [
                    'rel' => 'loan',
                    'href' => route('loans.show', $loan_uuid),
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
            'interest' => 'interest',
            'capital' => 'capital',
            'late_payment' => 'late_payment',
            'paid_interest' => 'paid_interest',
            'paid_capital' => 'paid_capital',
            'paid_late_payment' => 'paid_late_payment',
            'pending_interest' => 'pending_interest',
            'pending_capital' => 'pending_capital',
            'pending_late_payment' => 'pending_late_payment',
            'balance' => 'balance',
            'paid' => 'paid',
            'pending' => 'pending',
            'extra' => 'extra',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
