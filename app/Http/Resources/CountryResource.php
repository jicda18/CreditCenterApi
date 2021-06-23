<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class CountryResource extends ApiResource
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
            'country' => $this->name,
            'abbr' => $this->abbreviation,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'provinces' => ProvinceResource::collection($this->whenLoaded('provinces')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('countries.show', $this->resource),
                ],
                [
                    'rel' => 'provinces',
                    'href' => route('countries.provinces.index', $this->resource),
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
            'country' => 'name',
            'abbr' => 'abbreviation',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
