<?php

namespace App\Http\Resources;

use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

abstract class ApiResource extends JsonResource
{
    /**
     * Get hash resource's keys to model's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public static function hash(): Collection;

    /**
     * Include history field to array if request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param array $array
     * @return array
     */
    protected function includeHistory($request, array $array): array
    {
        $headerKey = 'X-add-history';

        if ($request->hasHeader($headerKey) && $request->header($headerKey) === 'all') {
            Gate::authorize('admin-required', user());

            return array_push($array, [
                'create_user_key' => $this->created_user_id,
                'create_ip_address' => $this->created_from_ip,
                'create_endpoint' => $this->created_route_name,
                'update_user_key' => $this->updated_user_id,
                'update_ip_address' => $this->updated_from_ip,
                'update_endpoint' => $this->updated_route_name,
                'delete_user_key' => $this->deleted_user_id,
                'delete_ip_address' => $this->deleted_from_ip,
                'delete_endpoint' => $this->deleted_route_name,
                'logs' => LogResource::collection($this->changeLogs),
            ]);
        }

        return $array;
    }

    /**
     * get Uuid for a belongTo relationship.
     *
     * @param   string   $relationName
     * @return  string
     */
    protected function getRelationUuid(string $relationName): string
    {
        $property = 'uuid';

        $relation = $this->whenLoaded($relationName);
        if (!($relation instanceof MissingValue)) {
            return null_prevent_property($relation, $property) ?? '';
        }

        $relation = $this->resource->{$relationName}();

        if ($relation instanceof BelongsTo) {
            return null_prevent_property($relation->select($property)->first(), $property, '');
        }

        return '';
    }

    /**
     * get all resource's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getResourceKey(): Collection
    {
        return static::hash()->keys();
    }

    /**
     * Get hash model's keys to resource's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function modelHash(): Collection
    {
        return static::hash()->flip();
    }

    /**
     * get all model's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getModelKey(): Collection
    {
        // hash.values === modelHash.keys
        return static::hash()->values();
    }

    /**
     * If resource has key.
     *
     * @param   string $key
     * @return  bool
     */
    public static function has(string $key): bool
    {
        return static::hash()->has($key);
    }

    /**
     * If model has key.
     *
     * @param   string $key
     * @return  bool
     */
    public static function modelHas(string $key): bool
    {
        return static::modelHash()->has($key);
    }

    /**
     * Get model's key from resource's key.
     *
     * @param   string $key
     * @return  string|null
     */
    public static function toModelAttribute(string $key): ?string
    {
        $attributes = static::hash();

        if ($attributes->has($key)) {
            return $attributes->get($key);
        }

        return null;
    }

    /**
     * Get resource's key from model's key.
     *
     * @param   string $key
     * @return  string|null
     */
    public static function toResourceAttribute(string $key): ?string
    {
        $attributes = static::modelHash();

        if ($attributes->has($key)) {
            return $attributes->get($key);
        }

        return null;
    }

    /**
     * Convert all resource's Keys in resourceKeysArray to model's keys. Ignore keys in resourceKeysArray aren't resource's keys.
     *
     * @param   array $resourceKeysArray
     * @return  array
     */
    public static function toModelKeysArray(array $resourceKeysArray): array
    {
        $withModelsKeys = [];
        $hash = static::hash();

        foreach ($resourceKeysArray as $resourcesKey => $value) {
            if ($hash->has($resourcesKey)) {
                $withModelsKeys[$hash->get($resourcesKey)] = $value;
            } else {
                $withModelsKeys[$resourcesKey] = $value;
            }
        }

        return $withModelsKeys;
    }
}
