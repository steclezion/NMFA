<?php

namespace App\Http\Controllers;

use App\Models\country_list;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;

class CountryListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{ 

if ($request->ajax()  )
{
$data = country_list::latest()->orderBy('country_name','DESC')->get(); return Datatables::of($data)->addIndexColumn()->addColumn('action', function($row)
{
$btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editcountries"><i class="fas fa-edit"> </i></a> ';
$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletcountries"><i class="fas fa-trash"> </i></a> ';
return $btn;
}

)
->rawColumns(['action'])
->make(true);
}

return view('settings.countries');
     

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
      
        $country = country_list::updateOrCreate(
            ['id' => $request->country_id], 

              ['country_name' => $request->name,
             'country_code' => $request->code,
             'International_dialing' => $request->dialing_number,
              ]
        );        

      // dd($enlm);
        return response()->json(['Message'=>true,'Data'=>$country ]);
    }

    catch(Exception $e) 
    {
        return response()->json(['error'=>$e,]);

    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\country_list  $country_list
     * @return \Illuminate\Http\Response
     */
    public function show(country_list $country_list)
    {
        //
    }



    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\country_list  $country_list
     * @return \Illuminate\Http\Response
     */
    public function edit(country_list $country_list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\country_list  $country_list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, country_list $country_list)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\country_list  $country_list
     * @return \Illuminate\Http\Response
     */
    public function destroy(country_list $country_list)
    {
        //
    }
}
