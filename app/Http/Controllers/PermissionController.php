<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\User;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = PermissionResource::class;
        $this->default_key_sort = 'permission_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Permission::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return  $this->showOne($permission, $this->relations);
    }

    public function usersPermissionsIndex(User $user)
    {
        return $this->modelRelationIndex($user, 'permissions', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
