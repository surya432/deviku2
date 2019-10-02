<?php

namespace App\Http\Controllers;

use App\Classes\MyAsianTv;
use App\Post;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Yajra\Datatables\Datatables;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('post.index');

    }
    public function json(Request $request)
    {
        if ($request->user()->hasRole('superadmin')) {
            $data = \App\Post::with('category')->orderBy('created_at', 'DESC')->get();
        } else {
            $data = \App\Post::where('cmp_id', Auth::user()->cmp_id)->with('category')->orderBy('created_at', 'DESC')->get();
        }
        //$query mempunyai isi semua data di table users, dan diurutkan dari data yang terbaru
        return Datatables::of($data)
            //$query di masukkan kedalam Datatables
            ->addColumn('action', function ($q) {
                //Kemudian kita menambahkan kolom baru , yaitu "action"
                return view('links', [
                    //Kemudian dioper ke file links.blade.php
                    'model'      => $q,
                    'url_edit'   => route('post.edit', $q->id),
                    'url_hapus'  => route('post.destroy', $q->id),
                    'url_view' => route('post.show', $q->id),
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
        $items = \App\Category::pluck('name', 'id');
        return view('post.create',compact('items'));
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
       try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'category_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input['url'] = $this->seoUrl($request->input('name'));
            $input['cmp_id'] = auth::user()->cmp_id;
            $input['createdBy'] = auth::user()->name;
            $input['sources'] = $request->input('sources');
            $input['category_id'] = $request->input('category_id');
            $product = \App\Post::create($input);
            return $this->sendResponse($product->toArray(), 'created successfully.');
       } catch (Exception $e) {
            return $this->sendError('Something Wrong!!', $e->getMessage());
       }
    
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        // $myasiantv  = new MyAsianTv();
        // $data = $myasiantv->getDetailDrama("https://myasiantv.to/drama/doctor-john/?utm_source=top_day&utm_medium=sidebar&utm_campaign=tracking");
        // dd($data);
        return view('post.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        $data = $post;
        $items = \App\Category::pluck('name', 'id');
        return view('post.edit',compact('data','items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['cmp_id'] = Auth::user()->cmp_id;
        $masterMirror =$post;
        $masterMirror->name = $input['name'];
        $masterMirror->category_id = $input['category_id'];
        $masterMirror->sources = $input['sources'];
        $masterMirror->createdBy = Auth::user()->name;
        $masterMirror->cmp_id = Auth::user()->cmp_id;
        $masterMirror->save();
        return $this->sendResponse($masterMirror->toArray(), 'created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();
        return $this->sendResponse($post->toArray(), 'Product deleted successfully.');
    }
}
