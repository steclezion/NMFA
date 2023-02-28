<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\document_type;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $document_types=document_type::all();
        $breadcrumb_title='Documnet Categories';
        return view('document_type.index',['breadcrumb_title'=>$breadcrumb_title,'document_types'=>$document_types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $breadcrumb_title='Create New Document Category';
        return view('document_type.create',['breadcrumb_title'=>$breadcrumb_title]);
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
        $category_name=$request->input('category_name');
        $category_description=$request->input('category_description');

    document_type::insert([
            'document_type'=>$category_name,
            'description'=>$category_description
        ]);
        return redirect('/document_types')->with('success', 'Record Inserted Successfully.' );


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $document=document_type::find($id);
        $breadcrumb_title='Show Document Category';
        return view('document_type.show',['breadcrumb_title'=>$breadcrumb_title,'document'=>$document]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
