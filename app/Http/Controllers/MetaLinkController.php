<?php

namespace App\Http\Controllers;

use App\MetaLink;
use Illuminate\Http\Request;

class MetaLinkController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  \App\MetaLink  $metaLink
     * @return \Illuminate\Http\Response
     */
    public function show(MetaLink $metaLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MetaLink  $metaLink
     * @return \Illuminate\Http\Response
     */
    public function edit(MetaLink $metaLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MetaLink  $metaLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MetaLink $metaLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MetaLink  $metaLink
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,MetaLink $metaLink)
    {
        //
        $metaLink= MetaLink::find($id);
        $metaLink->delete();
        return $this->sendResponse($metaLink->toArray(), 'MetaLink deleted successfully.');
    }
}
