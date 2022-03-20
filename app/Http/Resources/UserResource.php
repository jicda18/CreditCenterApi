<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class UserResource extends ApiResource
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
            'user' => $this->username,
            'mail' => $this->email,
            'name' => $this->fullname,
            'administrator' => $this->admin,
            'avatar' => $this->profile_file,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $this->resource),
                ],
                [
                    'rel' => 'permissions',
                    'href' => route('users.permissions.index', $this->resource),
                ],
                [
                    'rel' => 'roles',
                    'href' => route('users.roles.index', $this->resource),
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
            'user' => 'username',
            'mail' => 'email',
            'name' => 'fullname',
            'avatar' => 'avatar',
            'password' => 'password',
            'old_password' => 'old_password',
            'administrator' => 'admin',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
