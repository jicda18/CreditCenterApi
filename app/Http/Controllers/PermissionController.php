<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\User;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = PermissionResource::class;
        $this->default_key_sort = 'id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        required_permission('permission.view');

        $query = $this->includeRelations(Permission::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        required_permission('permission.view');

        return $this->forSelect(Permission::all(), function (Permission $permission) {
            return [
                'value' => $permission->id,
                'label' => $permission->description,
            ];
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        required_permission('permission.view');

        if ($this->request->has($this->with_key) && count($this->relations) === 0) {
            $relations = collect(explode(static::WITH_DELIMITER, $this->request->get($this->with_key)))
                ->flip()
                ->only($this->relations)
                ->keys()
                ->all();


            foreach ($relations as $relation) {
                $permission->{$relation};
            }
        }

        return (new $this->resource($permission))->response();
    }

    /**
     * Display a listing of the resource.
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function rolesPermissionIndex(Role $role)
    {
        required_permission('permission.view');

        $query = $this->includeRelations($role->permissions()->getQuery(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display a listing of the resource.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersPermissionIndex(User $user)
    {
        required_permission('permission.view');

        $query = $this->includeRelations($user->permissions()->getQuery(), $this->relations);

        return $this->idx($query);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
