<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    use GenericController;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Todo: Implement
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SecurityLog  $securityLog
     * @return \Illuminate\Http\Response
     */
    public function show(SecurityLog $securityLog)
    {
        // Todo: Implement
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
