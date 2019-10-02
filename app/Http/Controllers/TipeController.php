<?php

namespace App\Http\Controllers;

use App\Tipe;
use Illuminate\Http\Request;


class TipeController extends Controller
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
     * @param  \App\Tipe  $tipe
     * @return \Illuminate\Http\Response
     */
    public function show(Tipe $tipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipe  $tipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //
        $cmp_id = Auth::user()->cmp_id;

        $data = \App\Tipe::where(['id'=> $id,"cmp_id"=> $cmp_id])->first();
        return view("tipe.edit",compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipe  $tipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:tipes,name'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input['cmp_id'] = Auth::user()->cmp_id;
        $masterMirror = \App\Tipe::find($id);
        $masterMirror->name = $input['name'];
        $masterMirror->save();
        return $this->sendResponse($masterMirror->toArray(), 'created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipe  $tipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipe $tipe)
    {
        //
        $tipe->delete();
        return $this->sendResponse($tipe->toArray(), 'Product deleted successfully.');
    }
}
