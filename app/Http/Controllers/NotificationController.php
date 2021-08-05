<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use GenericController;

    private $relations = [];

    public function __construct()
    {
        $this->resource = NotificationResource::class;
        $this->default_key_sort = 'notification_id';

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->includeRelations(Notification::query(), $this->relations);

        return $this->idx($query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        return  $this->showOne($notification);
    }

    public function usersNotificationsIndex(User $user)
    {
        return $this->modelRelationIndex($user, 'notifications', $this->relations);
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
