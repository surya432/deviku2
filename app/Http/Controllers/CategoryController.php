<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Yajra\Datatables\Datatables;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $query = \App\Category::all();
        //$query mempunyai isi semua data di table users, dan diurutkan dari data yang terbaru
        return Datatables::of($query)
            //$query di masukkan kedalam Datatables
            ->addColumn('action', function ($q) {
                //Kemudian kita menambahkan kolom baru , yaitu "action"
                return view('links', [
                    //Kemudian dioper ke file links.blade.php
                    'model'      => $q,
                    'url_edit'   => route('category.edit', $q->id),
                    'url_hapus'  => route('category.destroy', $q->id),
                    // 'url_detail' => route('tipe.show', $q->id),
                ]);
            })
            ->addIndexColumn()
            // ->rawColumns(['other-columns'])
            ->make(true);
    }
    public function index()
    {
        //
        return view("category.index");

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("category.create");

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
            'name' => 'required|unique:categories,name'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['url'] = $this->seoUrl($request->name);
        $input['cmp_id'] = auth::user()->cmp_id;
        $product = \App\Category::create($input);
        return $this->sendResponse($product->toArray(), 'created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $cmp_id = Auth::user()->cmp_id;
        $data = \App\Category::where(['id' => $id, "cmp_id" => $cmp_id])->first();
        return view("category.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:categories,name'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input['cmp_id'] = Auth::user()->cmp_id;
        $masterMirror = \App\Category::find($id);
        $masterMirror->name = $input['name'];
        $masterMirror->save();
        return $this->sendResponse($masterMirror->toArray(), 'created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return $this->sendResponse($category->toArray(), 'Product deleted successfully.');
    }
}
