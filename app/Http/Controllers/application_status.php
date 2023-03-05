<?php

namespace App\Http\Controllers;
use App\Models\applications;
use App\Models\psur;
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
use App\Models\DecisionParticipant;
use App\Models\uploaded_documnts;


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
     $this->middleware('permission:application-list|application-status-list|assesor_roles|nmfa_director');
   // $this->middleware('permission:application-list');
  //$this->middleware('permission:application-status-list');
  


    }
     
    public function  registerd_application(Request $request)
    {

        $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
        @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
        //dd($explode);

        $applications = applications::
        // join('manufacturers','manufacturers.application_id','applications.application_id')
        join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->join('psurs','psurs.application_id','applications.application_id')
        ->select('psurs.*','applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('applications.application_type','=','Preliminary screening completed')
        ->where('contacts.contact_type','=','Supplier')
        
        ->orderBy('applications.application_number','ASC')
        ->get();

         //return view('dossier_status_sample.dossier_status',compact('applications'));
         dd($applications); 

    return view('application_reception.registered_application',
    ['applications'=>$applications,
    'explode'=> $explode,
    ]);

    }


    public function  nmfa_director_applications(Request $request)
    {

        $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
        @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
        //dd($explode);
        $applications_id = applications::select('application_id')->where('applications.application_status','=','Preliminary screening completed')->orderBy('application_id')->get();

        $array_applications_id= [];foreach($applications_id as $id) {  array_push($array_applications_id,$id->application_id);  }

//dump(  $array_applications_id);

        $applications = applications::
         join('medicinal_products','medicinal_products.application_id','applications.application_id')
         ->join('medicines','medicinal_products.medicine_id','medicines.id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->join('dossier_assignments','applications.id','dossier_assignments.application_id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('certifications','decisions.id','certifications.decision_id')
        ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*', 'medicines.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'certifications.*','certifications.registration_number as regnumber',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->whereIn('applications.application_id',$array_applications_id)
        ->orderBy('applications.application_number','ASC')
        ->get();


    return view('application_reception.unregistered_application',
    ['applications'=>$applications,
    'explode'=> $explode,
    ]);

    }




public function dossier_sample_status_renew(Request $request)
{
    
    $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
    @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard); 
    //dd($explode);

    $applications = applications::
    // join('manufacturers','manufacturers.application_id','applications.application_id')
    join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    //->join('users','users.id','applications.user_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
    ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
    ->leftjoin('certifications','decisions.id','certifications.decision_id')
    ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','medicines.product_name as med_name',
    'certifications.*','certifications.registration_number as regnumber',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.user_id',auth()->user()->id)
    ->where('applications.registration_type','Re-new')
    ->orderBy('applications.application_number','ASC')
    ->get();
   
     //return view('dossier_status_sample.dossier_status',compact('applications'));


return view('dossier_status_sample.dossier_status_renew',
['applications'=>$applications,
'explode'=> $explode,
]);


}
    public function application_reception_on_going(Request $request)
    {
    

        $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

        $applications = applications::where('applications.user_id',auth()->user()->id)
            //    ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
               ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
               ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
               ->leftjoin('users','users.id','applications.user_id')
               ->leftjoin('contacts','contacts.application_id','applications.application_id')
               ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
               ->select(
               'medicinal_products.*',
               'medicinal_products.product_trade_name as t_name',
               'company_suppliers.*',
               'medicines.*', 
               'medicines.product_name as pname', 
               'company_suppliers.trade_name as cs_tradename',
               'applications.*',
               'contacts.*',
               'contacts.first_name as cfirst_name', 
               'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
               ->distinct('applications.application_number')
               ->where('applications.application_status','!=','completed')
              //  ->where('applications.application_status','=','processing')
               ->where('applications.registration_type','=','New')
               ->where('contacts.contact_type','=','Supplier')
               ->orderBy('applications.application_number','ASC')
               ->get();
             
               
        
          return view('application_reception.in_process_on_going',[
            'company_suppliers_template' =>$company_suppliers_template,
              'applications'=>$applications,
              ]);

    }





    public function application_reception_on_going_renew(Request $request)
    {

        dd($request->all());
        
    
     
        $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

        $applications = applications::where('applications.user_id',auth()->user()->id)
            //  ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
               ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
               ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
               ->leftjoin('users','users.id','applications.user_id')
               ->leftjoin('contacts','contacts.application_id','applications.applicatio_id')
               ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
               ->select('medicinal_products.*','medicinal_products.product_trade_name as t_name',
                         'company_suppliers.*','medicines.*',
               'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*','contacts.first_name as cfirst_name',
               'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
               ->where('applications.application_status','=','processing')
               ->where('applications.registration_type','=','Re-new')
              // ->where('contacts.contact_type','=','Supplier')
               ->orderBy('applications.application_number','ASC')
               ->get();
             
               
        
        //   return view('application_reception.in_process_on_going_renew_application',[
        //     'company_suppliers_template' =>$company_suppliers_template,
        //       'applications'=>$applications,
        //       ]);

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
       ->where('applications.application_status','=','completed')
       ->orderBy('applications.application_number','ASC')
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
        ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        //->join('users','users.id','applications.user_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->distinct()
        ->select('applications.application_id','medicinal_products.*',
        'medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','medicines.product_name as med_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('applications.user_id',auth()->user()->id)
        ->where('applications.registration_type','New')
        ->orderBy('applications.application_number','ASC')
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
