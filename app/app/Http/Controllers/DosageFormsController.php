<?php

namespace App\Http\Controllers;
use App\Models\DosageForms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use DataTables;


class DosageFormsController extends Controller
{
    /*** Display a listing of the resource.
     
    * @return \Illuminate\Http\Response

     */


    public function index(Request $request)
    {

        //dd($request->ajax());

if ($request->ajax()  )
{
    
$data = DosageForms::latest()->orderBy('name','DESC')->get();
return Datatables::of($data)
->addIndexColumn()->addColumn('action', function($row)
{
$btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editDosage"><i class="fas fa-edit"> </i></a> ';
$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedosage"><i class="fas fa-trash"> </i></a> ';
return $btn;
}
)




                    


->rawColumns(['action'])

->make(true);
}
      
       return view('settings.dosage_form');
     
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
        try{
        if($request->is_enlm == 'on') {$request->is_enlm='1';} else {$request->is_enlm='0';}
        if($request->is_approved == 'on') {$request->is_approved='1';} else {$request->is_approved='0';}

        $enlm = DosageForms::updateOrCreate(
       
             ['id' => $request->enlm_id], 
             [ 'product_name' => $request->product_name,
             'medicine_id' => $request->medicine_id, 
             'product_description' => $request->product_description,
             'is_enlm' => $request->is_enlm, 
             'is_approved' => $request->is_approved 
             ]
        );        

      // dd($enlm);
        return response()->json(['Message'=>true,'Data'=>$enlm]);
    }

    catch(Exception $e) 
    {
        return response()->json(['error'=>$e,]);

    }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enlm = DosageForms::find($id);
       
        return response()->json($enlm);
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
        $medicine = DosageForms::find($id)->delete();
     
        return response()->json($medicine);
    }
}
