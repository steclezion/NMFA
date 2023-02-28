<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\route_administrations;
use Illuminate\Http\Request;

use DataTables;

class route_administrations_controller extends Controller
{
    /*** Display a listing of the resource.
     
    * @return \Illuminate\Http\Response

     */


    public function index(Request $request)
    {

        //dd($request->ajax());

if ($request->ajax()  )
{
    
$data = route_administrations::latest()->orderBy('name','DESC')->get();
return Datatables::of($data)
->addIndexColumn()->addColumn('action', function($row)
{
$btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editdosage"><i class="fas fa-edit"> </i></a> ';
$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedosage"><i class="fas fa-trash"> </i></a> ';
return $btn;
}
)




                    


->rawColumns(['action'])

->make(true);
}
      
       return view('settings.route_of_administration');
     
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
       // dd($request->all());
        try{
      
        $dosage= route_administrations::updateOrCreate(
       
             ['id' => $request->dosage_id], 
             [ 'name' => $request->name,
               'description' => $request->description,
          
             ]
        );        

      // dd($enlm);
        return response()->json(['Message'=>true,'Data'=>$dosage]);
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
        $enlm = route_administrations::find($id);
       
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
        $route = route_administrations::find($id)->delete();
     
        return response()->json($route);
    }


}
