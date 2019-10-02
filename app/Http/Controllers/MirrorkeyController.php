<?php

namespace App\Http\Controllers;

use App\mirrorkey;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Yajra\Datatables\Datatables;
class MirrorkeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $query = \App\mirrorkey::join('master_mirrors', 'master_mirrors.id',"=", 'mirrorkeys.master_mirror_id')->select('mirrorkeys.*','master_mirrors.name')->get();
        //$query mempunyai isi semua data di table users, dan diurutkan dari data yang terbaru
        return Datatables::of($query)
            //$query di masukkan kedalam Datatables
            ->addColumn('action', function ($q) {
                //Kemudian kita menambahkan kolom baru , yaitu "action"
                return view('links', [
                    //Kemudian dioper ke file links.blade.php
                    'model'      => $q,
                    'url_edit'   => route('mirrorkey.edit', $q->id),
                    'url_hapus'  => route('mirrorkey.destroy', $q->id),
                    'url_detail' => route('mirrorkey.show', $q->id),
                ]);
            })
            ->addIndexColumn()
            // ->rawColumns(['other-columns'])
            ->make(true);
    }
    public function index()
    {
        //
        
        return view("mirrorkey.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //       

        $cmp_id = Auth::user()->cmp_id; 
        $readyMirrorkey = \App\mirrorkey::where("cmp_id",$cmp_id)->select("id")->get();
        $items = \App\MasterMirror::pluck('name', 'id');
        return view("mirrorkey.create",compact("items"));

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
        $input = $request->all();
        $validator = Validator::make($input, [
            'keys' => 'required',
            'master_mirror_id' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['cmp_id'] = Auth::user()->cmp_id;

        $product = \App\mirrorkey::create($input);


        return $this->sendResponse($product->toArray(), 'created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mirrorkey  $mirrorkey
     * @return \Illuminate\Http\Response
     */
    public function show(mirrorkey $mirrorkey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mirrorkey  $mirrorkey
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $cmp_id = Auth::user()->cmp_id;
        $readyMirrorkey = \App\mirrorkey::where("cmp_id", $cmp_id)->select("id")->get();
        $items = \App\MasterMirror::pluck('name', 'id');
        $data = \App\mirrorkey::find($id);
        return view("mirrorkey.edit", compact("data","items"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mirrorkey  $mirrorkey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'keys' => 'required',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['cmp_id'] = Auth::user()->cmp_id;

        $masterMirror = \App\mirrorkey::find($id);
        $masterMirror->keys = $input['keys'];
       
        $masterMirror->save();


        return $this->sendResponse($masterMirror->toArray(), 'created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mirrorkey  $mirrorkey
     * @return \Illuminate\Http\Response
     */
    public function destroy(mirrorkey $mirrorkey)
    {
        //
        $mirrorkey->delete();
        return $this->sendResponse($mirrorkey->toArray(), 'Product deleted successfully.');
    }
}
