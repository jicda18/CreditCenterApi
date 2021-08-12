<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class LogController extends Controller
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
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        // Todo: implement
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
