<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class BlacklistController extends Controller
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
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function show(Blacklist $blacklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blacklist $blacklist)
    {
        //
    }

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
