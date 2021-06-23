<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LogResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model = $this->model;
        $modelResource = (new $model())->getResourceClass();

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'entity' => $this->model,
            'relation_key' => $this->getRelationUuid('reference'),
            'ip_address' => $this->ip,
            'route_name' => $this->route,
            'before_values' => $this->old,
            'after_values' => $this->new,
            'inserted_at' => $this->at->toIso8601String(),
            'type' => new LogTypeResource($this->type),
            'user' => new UserResource($this->user),
            'reference' => new $modelResource($this->whenLoaded('reference')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('logs.show', $this->resource),
                ],

                [
                    'rel' => 'type',
                    'href' => route('types.show', $this->type),
                ],

                [
                    'rel' => 'user',
                    'href' => $this->user === null ? null : route('users.show', $this->user),
                ],

                [
                    'rel' => 'reference',
                    'href' => route(Str::plural(strtolower($this->model_name)) . '.show', $this->getRelationUuid('reference')),
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
            'entity' => 'model',
            'relation_key' => 'relation_id',
            'ip_address' => 'ip',
            'route_name' => 'route',
            'before_values' => 'old',
            'after_values' => 'new',
            'inserted_at' => 'at',
        ]);
    }
}
