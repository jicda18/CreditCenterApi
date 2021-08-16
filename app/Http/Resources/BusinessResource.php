<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class BusinessResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $province_uuid = $this->getRelationUuid('province');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'province_key' => $province_uuid,
            'business' => $this->name,
            'relation' => $this->type,
            'noe' => $this->number_of_employee,
            'phone_number' => $this->phone,
            'street' => $this->address,
            'comments' => $this->comment,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('businesses.show', $this->resource),
                ],
                [
                    'rel' => 'province',
                    'href' => route('provinces.show', $province_uuid),
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
            'province_key' => 'province_id',
            'business' => 'name',
            'relation' => 'type',
            'noe' => 'number_of_employee',
            'phone_number' => 'phone',
            'street' => 'address',
            'comments' => 'comment',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
