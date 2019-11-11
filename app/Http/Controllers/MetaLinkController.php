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

    public function create(Request $request)
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
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'post_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input['url'] = md5($input['name'].rand(0,9999999));
            $input['cmp_id'] = auth::user()->cmp_id;
            $input['name'] = $request->input('name');
            $input['createdBy'] = auth::user()->name;
            // $input['post_id'] = $request->input('post_id');
            $input['post_id'] =auth::user()->cmp_id;
            $Content = \App\Content::create($input);
            if($request->input('links')){
                foreach ($request->input('links') as $a => $link) {
                    $MetaLink = new MetaLink();
                    $MetaLink->kualitas = $link['kualitas'];
                    $MetaLink->link = $link['link'];
                    $MetaLink->cmp_id = auth::user()->cmp_id;
                    $Content->links()->save($MetaLink);
                }
            }

            return $this->sendResponse($Content->with('links')->get(), 'created successfully.');
        } catch (Exception $e) {
            return $this->sendError('Something Wrong!!', $e->getMessage());
        }
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
        $metaLink= MetaLink::find($id);
        $metaLink->delete();
        return $this->sendResponse($metaLink->toArray(), 'MetaLink deleted successfully.');
    }
}
