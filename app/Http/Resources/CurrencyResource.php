<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class CurrencyResource extends ApiResource
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
            'currency' => $this->name,
            'prefix' => $this->symbol,
            'root' => $this->default,
            'exchange' => $this->exchange_value,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('currencies.show', $this->resource),
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
            'currency' => 'name',
            'prefix' => 'symbol',
            'root' => 'default',
            'exchange' => 'exchange_value',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
