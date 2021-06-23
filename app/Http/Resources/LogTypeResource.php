<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class LogTypeResource extends ApiResource
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
            'name' => $this->description,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'log' => LogResource::collection($this->whenLoaded('logs')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('types.show', $this->resource),
                ],

                [
                    'rel' => 'logs',
                    'href' => route('types.logs.index', $this->resource),
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
            'name' => 'description',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
