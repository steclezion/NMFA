<?php

namespace App\Http\Controllers;
use App\Models\applications;
use App\Models\User;
use App\Models\company_suppliers;
use App\Models\manufacturers;
use App\Models\medicinal_products;
use App\Models\agents_template; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\company_suppliers_template;
use App\Models\issue_query;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\TaskTracker;



class application_status extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *   
     */
public function __construct()
    {

      $this->middleware('auth')->except('index');
     //$this->middleware('auth')->only('index');
    $this->middleware('permission:application-list');
     $this->middleware('permission:application-status-list');
  


    }
     
    public function index()
    {
        
$company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

$applications = applications::where('applications.user_id',auth()->user()->id)
    //    ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
       ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
       ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
       ->leftjoin('users','users.id','applications.user_id')
       ->join('contacts','contacts.application_id','applications.application_id')
       ->select('medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
       'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*','contacts.first_name as cfirst_name',
       'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
       ->where('contacts.contact_type','=','Supplier')
       ->get();
     
       

  return view('application_reception.application_status',[
    'company_suppliers_template' =>$company_suppliers_template,
      'applications'=>$applications,
      ]);
  
    }



    public function dossier_sample_status()
    {
       

        $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
        @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard); 
        //dd($explode);

        $applications = applications::
        // join('manufacturers','manufacturers.application_id','applications.application_id')
        join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        //->join('users','users.id','applications.user_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->distinct()
        ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('applications.user_id',auth()->user()->id)
        ->orderBy('applications.application_id','ASC')
        ->get();
       
         //return view('dossier_status_sample.dossier_status',compact('applications'));

 
return view('dossier_status_sample.dossier_status',
 ['applications'=>$applications,
 'explode'=> $explode,
 ]);
  
    }


    public function app_update_deadline(Request $request)
    {

    try {
        $id = $request->input('task_id');
        $dead_line = $request->input('new_deadline');
        $reason = $request->input('extend_reason');
        $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$dead_line .' days'));
        $activity_status = 'Inprogress';
        TaskTracker::where('id', $id)->update(
            [
                'extention_days' => $dead_line,
                'end_time' => $end_time,
                'activity_status' =>$activity_status,
                'deadline_extended'=> true,
                'extention_reason' => $reason
            ]
        );
    }

catch (\Exception $e) {
        return Redirect()->back()->with('danger', 'Problem with Deadline Extension. ' . $e->getMessage());
    }
    return redirect()->back()->with('success', 'Deadline Extended Successfully');
    
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
