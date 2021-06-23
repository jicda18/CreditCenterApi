<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class ProvinceResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $country_uuid = $this->getRelationUuid('country');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'country_key' => $country_uuid,
            'province' => $this->name,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'country' => new CountryResource($this->whenLoaded('country')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('permissions.show', $this->resource),
                ],
                [
                    'rel' => 'country',
                    'href' => route('countries.show', $country_uuid),
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
            'province' => 'name',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
