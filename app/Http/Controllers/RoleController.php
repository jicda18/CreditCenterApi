<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\User;
use App\Traits\GenericController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = RoleResource::class;
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
        required_permission('role.view');

        $query = $this->includeRelations(Role::query(), $this->relations);

        return $this->idx($query);
    }

    public function forSelectList()
    {
        required_permission('role.view');

        return $this->forSelect(Role::all(), function (Role $role) {
            return [
                'value' => $role->id,
                'label' => $role->description,
            ];
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        required_permission('role.view');

        if ($this->request->has($this->with_key) && count($this->relations) === 0) {
            $relations = collect(explode(static::WITH_DELIMITER, $this->request->get($this->with_key)))
                ->flip()
                ->only($this->relations)
                ->keys()
                ->all();


            foreach ($relations as $relation) {
                $role->{$relation};
            }
        }

        return (new $this->resource($role))->response();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersRoleIndex(User $user)
    {
        required_permission('role.view');

        $query = $this->includeRelations($user->roles()->getQuery(), $this->relations);

        return $this->idx($query);
    }

    // Todo: add store and update
    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
