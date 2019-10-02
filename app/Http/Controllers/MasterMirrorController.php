<?php

namespace App\Http\Controllers;

use App\MasterMirror;
use Illuminate\Http\Request;
use Validator;

use Yajra\Datatables\Datatables;

class MasterMirrorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function json()
    {
        $query = \App\MasterMirror::orderBy('id', 'asc');
        //$query mempunyai isi semua data di table users, dan diurutkan dari data yang terbaru
        return Datatables::of($query)
            //$query di masukkan kedalam Datatables
            ->addColumn('action', function ($q) {
                //Kemudian kita menambahkan kolom baru , yaitu "action"
                return view('links', [
                    //Kemudian dioper ke file links.blade.php
                    'model'      => $q,
                    'url_edit'   => route('master-mirror.edit', $q->id),
                    'url_hapus'  => route('master-mirror.destroy', $q->id),
                    // 'url_detail' => route('mirrorkey.show', $q->id),
                ]);
            })
            ->addIndexColumn()
            // ->rawColumns(['other-columns'])
            ->make(true);
    }
    public function index()
    {
        //
        return view("master-mirror.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("master-mirror.create");
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
            'name' => 'required|unique:master_mirrors,name',
            'status' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $product = \App\MasterMirror::create($input);


        return $this->sendResponse($product->toArray(), 'created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterMirror  $masterMirror
     * @return \Illuminate\Http\Response
     */
    public function show(MasterMirror $masterMirror)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterMirror  $masterMirror
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $mirror = \App\MasterMirror::findOrFail($id);
        return view("master-mirror.edit", compact('mirror'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterMirror  $masterMirror
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();


        $validator = Validator::make($input, [
            'name' => 'required',
            'status' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $masterMirror= \App\MasterMirror::find($id);
        $masterMirror->name = $input['name'];
        $masterMirror->status = $input['status'];
        $masterMirror->save();


        return $this->sendResponse($masterMirror->toArray(), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterMirror  $masterMirror
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterMirror $masterMirror)
    {
        $masterMirror->delete();
        return $this->sendResponse($masterMirror->toArray(), 'Product deleted successfully.');
    }
}
