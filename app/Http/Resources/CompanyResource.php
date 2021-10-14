<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class CompanyResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'company' => $this->name,
            'image' => $this->image,
            'ico' => $this->ico,
            'front_end' => $this->frontend,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'banks' => BankResource::collection($this->whenLoaded('banks')),
            'debtors' => DebtorResource::collection($this->whenLoaded('debtors')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('companies.show', $this->resource),
                ],
                [
                    'rel' => 'users',
                    'href' => route('companies.users.index', $this->resource),
                ],
                [
                    'rel' => 'banks',
                    'href' => route('companies.banks.index', $this->resource),
                ],
                [
                    'rel' => 'debtors',
                    'href' => route('companies.debtors.index', $this->resource),
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
            'company' => 'name',
            'image' => 'image',
            'ico' => 'ico',
            'front_end' => 'frontend',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
