<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\medicines;
use App\Models\applications;

use DataTables;

class medicinesController extends Controller
{
    /*** Display a listing of the resource.
     
    * @return \Illuminate\Http\Response

     */


    public function index(Request $request)
    {

        //($request->ajax());

if ($request->ajax())
{
    
    $data = medicines::latest()->orderBy('product_name','DESC')->get();
return Datatables::of($data)
->addIndexColumn()->addColumn('action', function($row)
{
$btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editEnlm"><i class="fas fa-edit"> </i></a> ';
$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteenlm"><i class="fas fa-trash"> </i></a> ';
return $btn;
}
)

->addIndexColumn()->addColumn('Is Enlm', function($row)
{
    if($row->is_enlm == '1') {$row->is_enlm='Yes';} else {$row->is_enlm='No';}

    //if($row->is_approved == '1') {$row->is_approved='Yes';} else {$row->is_approved='No';}
return $row->is_enlm;
}
)

->addIndexColumn()->addColumn('Is Approved', function($row)
{
     if($row->is_approved == '1') {$row->is_approved='Yes';} else {$row->is_approved='No';}

return $row->is_approved;
}
)
                    


->rawColumns(['action','Is Enlm','Is Approved'])

->make(true);
}
      
       return view('settings.enml');
     
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

        $enlm = medicines::updateOrCreate(
       
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
        $enlm = medicines::find($id);
       
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
        $medicine = medicines::find($id)->delete();
     
        return response()->json($medicine);
    }
}
