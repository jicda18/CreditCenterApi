<?php

namespace App\Http\Controllers;

use App\Models\LogType;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class LogTypeController extends Controller
{
    use GenericController;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Todo: implement
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogType  $logType
     * @return \Illuminate\Http\Response
     */
    public function show(LogType $logType)
    {
        // Todo: implement
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
