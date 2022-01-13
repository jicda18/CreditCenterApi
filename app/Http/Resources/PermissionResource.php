<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class PermissionResource extends ApiResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('permissions.show', $this->resource),
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
            'id' => 'id',
            'name' => 'name',
            'description' => 'description',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
