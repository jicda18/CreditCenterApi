<?php

namespace App\Http\Controllers;

use App\Models\Whitelist;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class WhitelistController extends Controller
{
    use GenericController;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Whitelist  $whitelist
     * @return \Illuminate\Http\Response
     */
    public function show(Whitelist $whitelist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Whitelist  $whitelist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Whitelist $whitelist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Whitelist  $whitelist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Whitelist $whitelist)
    {
        //
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
