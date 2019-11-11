<?php

namespace App\Http\Controllers;

use App\Content;
use App\MetaLink;
use Illuminate\Http\Request;
use Validator;
use Auth;

use Hash;
use Yajra\Datatables\Datatables;
class ContentController extends Controller
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
    public function json(Request $request)
    {
        if ($request->user()->hasRole('superadmin')) {
            $data = \App\Content::with('post')->orderBy('created_at', 'DESC')->get();
        } else {
            $data = \App\Content::where('cmp_id', Auth::user()->cmp_id)->with('post')->orderBy('created_at', 'DESC')->get();
        }
        //$query mempunyai isi semua data di table users, dan diurutkan dari data yang terbaru
        return Datatables::of($data)
            //$query di masukkan kedalam Datatables
            ->addColumn('action', function ($q) {
                //Kemudian kita menambahkan kolom baru , yaitu "action"
                return view('links', [
                    //Kemudian dioper ke file links.blade.php
                    'model'      => $q,
                    'url_edit'   => route('content.edit', $q->id),
                    'url_hapus'  => route('content.destroy', $q->id),
                    'url_view' => route('embed', $q->url),
                ]);
            })
            ->addIndexColumn()
            // ->rawColumns(['other-columns'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('content.create');
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
            $input['post_id'] = $request->input('post_id');
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
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        //
        $data=$content->with('links')->first();
        return view('content.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        //
        $content->name = $request->input('name');
        $content->save();
        if ($request->input('links')) {
            foreach ($request->input('links') as $a => $link) {
                if(isset($link['id'])){
                    $MetaLink = MetaLink::find($link['id']);
                    $MetaLink->kualitas = $link['kualitas'];
                    $MetaLink->link = $link['link'];
                    $MetaLink->save();
                }else{
                    $MetaLink = new MetaLink();
                    $MetaLink->kualitas = $link['kualitas'];
                    $MetaLink->link = $link['link'];
                    $content->links()->save($MetaLink);
                }

            }
        }
        return $this->sendResponse($content->with('links')->get(), 'created successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        //
        $content->links()->delete();
        $content->delete();
        return $this->sendResponse($content->toArray(), 'content deleted successfully.');
    }
}
