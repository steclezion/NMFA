<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
use App\Models\application_receipt_of_registration;
use App\Models\agents_template;
use App\Models\company_suppliers_template;
use App\Models\payment_configuration;
use App\Models\invoices;
use App\Models\declerations;
use App\Models\contacts;
use App\Models\fast_track_application;
use App\Models\User;
use App\Models\product_details;
use App\Models\DosageForms;
use App\Models\apis;
use App\Models\route_administrations;
use App\Models\company_suppliers;
use App\Models\agents;
use App\Models\medicinal_products;
use App\Models\manufacturers;
use App\Models\api_manufacturers;
use App\Models\product_composition;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\checklists;
use App\Models\Acknowledgement_letter;
use App\Models\issue_query;
use App\Notifications\RemindersNotification;

use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Events\ApplicationReceiptionEvent;

class check_list extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
//Acknowledgement_Letter

public function check_preliminary_screening(Request $request)
{

//dd($request->all());

$check_list = DB::table('applications')
->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
->leftjoin('invoices','applications.application_id','=','invoices.application_id')
->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
->leftjoin('checklists','checklists.application_id','applications.application_id')
->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
->where('applications.application_id',$request->application_id)
->orderBy('invoices.invoice_number','ASC')
->first();

$check_steps= explode(',',$check_list->supervisor_hold_assessor_progress);

if($check_list->application_type ==2){

if(in_array('1',$check_steps)) {$section_one=1;} else {$section_one=0;}
if(in_array('2',$check_steps)) {$section_two=1;} else {$section_two=0;}
if(in_array('3',$check_steps)) {$section_three=1;} else {$section_three=0;}
if(in_array('5',$check_steps)) {$section_five=1;} else {$section_five=0;}
$section_four=0;

if($section_one==1 && $section_two ==1 && $section_three==1 &&  $section_five==1) { $decision=1;} else {$decision=0;}

}

elseif($check_list->application_type ==1)
{

if(in_array('1',$check_steps)) {$section_one=1;} else {$section_one=0;}
if(in_array('2',$check_steps)) {$section_two=1;} else {$section_two=0;}
if(in_array('4',$check_steps)) {$section_four=1;} else {$section_four=0;}
if(in_array('5',$check_steps)) {$section_five=1;} else {$section_five=0;}
$section_three = 0;

if($section_one==1 && $section_two ==1 && $section_four==1 &&  $section_five==1) { $decision=1;} else {$decision=0;}

}


return response()->json([ 
    'section_one'=>$section_one,
    'section_two'=>$section_two,
    'section_three'=>$section_three,
    'section_four'=>$section_four,
    'section_five'=>$section_five,
    'final_button' => $decision,
    ]);

}
    


public function index()
    {
        //
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        $route_administrations = route_administrations::all()->sortBy('name');
        $agents = agents::all()->sortBy('trade_name');
        $company_suppliers = company_suppliers::all()->sortBy('trade_name');
        $product_details =  product_details::all()->sortBy('product_name');

    
               
           
        $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
        ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->leftjoin('application_receipt_of_registrations','application_receipt_of_registrations.application_id','applications.application_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('documents', 'documents.id', '=', 'application_receipt_of_registrations.document_id')
        ->distinct()
        ->select( 'medicines.*','documents.*','application_receipt_of_registrations.*' ,
         'application_receipt_of_registrations.application_number as app_receipt_number',
         'checklists.*','checklists.application_id as check_app',
         'applications.application_id','medicinal_products.*',
         'medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        // ->where('applications.user_id',auth()->user()->id)
        ->where('applications.assigned_To','=',auth()->user()->id)
        ->where('applications.registration_type','New')
        // ->orWhere('application_receipt_of_registrations.application_id','=',$id)
        ->orderBy('applications.application_number','ASC')
        ->get();
       

      

           return view('checklist.checklist',[
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_details,
            'applications' =>  $applications,
         
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */


     public function process_checklist_register(Request $request,$id)
     {

        $check_list = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$id)
        ->orderBy('invoices.invoice_number','ASC')
        ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
                 ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                 ->where('contact_type','Agent')
                 ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
                 ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                 ->where('contact_type','Supplier')
                 ->get();

$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                        ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                        ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                        ->where('applications.application_id',$id)
                        ->select('applications.*','medicines.*','medicinal_products.*')
                        ->get();


$dosage_forms = DB::table('medicinal_products')
                        ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                        ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                        ->select('dosage_forms.*','medicinal_products.*')
                        ->get();

$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

foreach($check_list as $check_lists ) {break;}

if( $check_lists->is_application_letter == 1)
{   $route_name = 'checklist.process_check_list_partially_Saved_re-register'; }
else {
$route_name = 'checklist.process_check_list_re-register';
}


return view($route_name,[
            'check_list' => $check_list,
            'agent_contact_info' => $agent_contact_info,
            'product_composition_info' => $product_composition_info,
            'api_manufacturers_info' => $api_manufacturers_info,
            'product_enlm_list' => $product_enlm_list,
            'receipts_info' => $receipts_info,
            'dosage_forms' => $dosage_forms,
            'applicant_contact_info' => $applicant_contact_info,
            'invoice_number' => $invoice_number,
            'receipts_number' =>$receipts_number,


        ]);




     }


     public function checklist_renew()
     {

        //
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        $route_administrations = route_administrations::all()->sortBy('name');
        $agents = agents::all()->sortBy('trade_name');
        $company_suppliers = company_suppliers::all()->sortBy('trade_name');
        $product_details =  product_details::all()->sortBy('product_name');

    
         $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
        ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        //->join('users','users.id','applications.user_id')
        ->leftjoin('application_receipt_of_registrations','application_receipt_of_registrations.application_id','applications.application_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')

          ->leftjoin('documents', 'documents.id', '=', 'application_receipt_of_registrations.document_id')
        ->distinct()
        ->select('checklists.*','documents.*',
        'application_receipt_of_registrations.application_number as app_receipt_number',
        'checklists.application_id as check_app','applications.application_id',
        'medicinal_products.*', 'medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename',
        'applications.*','contacts.*',
        'contacts.first_name as cfirst_name',
        'contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('applications.assigned_To','=',auth()->user()->id)
        ->where('applications.registration_type','Re-new')
        ->orderBy('applications.application_number','ASC')
        ->get();
       

      

           return view('checklist.checklist_re-register',[
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_details,
            'applications' =>  $applications,
         
        ]);
     }


public function Acknowledgement_of_Receipt_of_Registration_Application($id)
{


$check_list = DB::table('applications')
->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
->leftjoin('invoices','applications.application_id','=','invoices.application_id')
->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
->leftjoin('checklists','checklists.application_id','applications.application_id')
->leftjoin('contacts','contacts.application_id','applications.application_id')
->leftjoin('application_receipt_of_registrations','application_receipt_of_registrations.application_id','applications.application_id')
->select('application_receipt_of_registrations.*','checklists.*', 
  DB::raw('concat(contacts.first_name,"  ",contacts.last_name) as fullname_contact'),
  'manufacturers.state as mstate', 'applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 
  'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
  ->where('applications.application_id',$id)
  ->where('contacts.contact_type','Supplier')
  ->orderBy('invoices.invoice_number','ASC')
  ->get();


  $Receipt_of_Registration_Application = DB::table('application_receipt_of_registrations')->where('application_id', $id)->first();

  if(  @$Receipt_of_Registration_Application->application_number != '')
  {
     $select_document_id = DB::table('documents')->where('id', $$Receipt_of_Registration_Application->document_id)->first();
      @$path = $select_document_id->path;
}
  else
  {
      $path='';
    
  }



$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$id)
                            ->select('applications.*','medicines.*','medicinal_products.*')
                            ->first();


$dosage_forms = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->where('medicinal_products.dosage_form_id','=',$product_enlm_list->dosage_form_id)
                            ->select('dosage_forms.*','medicinal_products.*')
                            ->get();



$application_receipt_of_registration = DB::table('application_receipt_of_registrations')
                            ->where('application_receipt_of_registrations.application_id','=',$id)
                            ->select('application_receipt_of_registrations.*')
                            ->get();


 $application_receipt_of_registrations =  new application_receipt_of_registration;
                            $t=time();
                            $year = Date('Y');
                            $count = application_receipt_of_registration::where('Reference_number', '<>', null)->count();
                            $count_sequence = $count + 1;
                            $zero_filled_counter = sprintf('%04d', $count_sequence);
                            $squential_Reference_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;



return view('Acknowledgement_of_Receipt_of_Registration_Application.Acknowledgement_of_Receipt_of_Registration_Application',

[
    'check_list' =>  $check_list ,
    'application_receipt_of_registrations' => $application_receipt_of_registrations,
    'application_receipt_of_registration'=> $application_receipt_of_registration,
    'dosage_forms' => $dosage_forms,
    'product_enlm_list' => $product_enlm_list ,
    'squential_Reference_number' =>  $squential_Reference_number,
    'path' =>  $path,
]);




}


public function reject_Acknowledgement_Letter_preliminary_screening_application($id)
{


    $check_list = DB::table('applications')
    ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
    ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
    ->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
    ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
    'medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name',
     DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
    'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount','dosage_forms.name as dname')
    ->where('applications.application_id',$id)
    ->where('contacts.contact_type','Supplier')
    ->orderBy('invoices.invoice_number','ASC')
    ->get();

       //dd($check_list);

    $count = Acknowledgement_letter::where('RL_squential_number', '<>', null)->count();
    $count_sequence = $count + 1;
    $year = Date('Y');
    $zero_filled_counter = sprintf('%04d', $count_sequence);  
    $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;
    $country_contact_info = DB::table('countries')->where('id',$check_list[0]->country_id)
     ->Orwhere('id',68)
      ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
      ->get();

$Assessor_generated_Acknowledgemet_letter = DB::table('acknowledgement_letters')->where('application_id', $id)->get();

if(  @$Assessor_generated_Acknowledgemet_letter[0]->application_number != '')
{
   $select_document_id = DB::table('documents')->where('id', $Assessor_generated_Acknowledgemet_letter[0]->document_id)->get();
    @$path = $select_document_id[0]->path;
    $number_days_receipts = $Assessor_generated_Acknowledgemet_letter[0]->number_days_receipts;

}
else
{
    $path='';
    $number_days_receipts  = '';
}


return view('Acknowledgement_Letter.reject_Acknowledgement_Letter_preliminary_screening_application',

[
    'check_list' =>  $check_list ,
    'country_contact_info' => $country_contact_info,
    'random_application_RL_squential_number'=> $random_application_RL_squential_number,
    'path' => $path,
    'number_days_receipts' => $number_days_receipts ,
]);




}


    public function Acknowledgement_Letter($id)
    {
        $check_list = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
        'medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name',
         DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount','dosage_forms.name as dname')
        ->where('applications.application_id',$id)
        ->where('contacts.contact_type','Supplier')
        ->orderBy('invoices.invoice_number','ASC')
        ->get();


           //dd($check_list);

           
        $count = Acknowledgement_letter::where('RL_squential_number', '<>', null)->count();
        $count_sequence = $count + 1;
        $year = Date('Y');
        $zero_filled_counter = sprintf('%04d', $count_sequence);  
        $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;
        $country_contact_info = DB::table('countries')->where('id',$check_list[0]->country_id)
         ->Orwhere('id',68)
          ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
          ->get();

 $Assessor_generated_Acknowledgemet_letter = DB::table('acknowledgement_letters')->where('application_id', $id)->get();

    if(  @$Assessor_generated_Acknowledgemet_letter[0]->application_number != '')
    {
       $select_document_id = DB::table('documents')->where('id', $Assessor_generated_Acknowledgemet_letter[0]->document_id)->get();
        @$path = $select_document_id[0]->path;
        $number_days_receipts = $Assessor_generated_Acknowledgemet_letter[0]->number_days_receipts;

    }
    else
    {
        $path='';
        $number_days_receipts  = '';
    }


return view('Acknowledgement_Letter.Acknowledgement_Letter_preliminary_screening',

    [
        'check_list' =>  $check_list ,
        'country_contact_info' => $country_contact_info,
        'random_application_RL_squential_number'=> $random_application_RL_squential_number,
        'path' => $path,
        'number_days_receipts' => $number_days_receipts ,
    ]);
    
    
    }


    ////////////////////////////////////////////////////
    public function create()
    {
        //
    }

//submit_section_four

public function submit_section_four(Request $request)
{

    //  dd($request->all());
try{
    $fetch_section_four_to_supervisor= DB::table('checklists')
    ->where('application_id', $request->application_id)
    ->get();

    if(   $fetch_section_four_to_supervisor[0]->supervisor_hold_assessor_progress == '' &&  $fetch_section_four_to_supervisor[0]->Availability_of_Product_Sample=='')
    {  
        return response()->json([ 'Message'=>'incorrect','application_id'=> $request->application_id ]);
    }

    else {
        $update = $fetch_section_four_to_supervisor[0]->supervisor_hold_assessor_progress.$request->supervisor_hold_assessor_progress;
        $submit_section_foursupervisor  = DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
            ['supervisor_hold_assessor_progress' =>   $update  ]
        );
    }

    $dataa = User::orderBy('id','ASC')->get();


    foreach ($dataa as $key => $user)
    {
    if(!empty($user->getRoleNames())) 
    {
    foreach($user->getRoleNames() as $v)
    {
    if($v=='Supervisor')
    {
    $new_notification=[];
    $new_notification['type'] = 'Notification';
    $new_notification['subject'] ='Application Screening Report';
    $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
    $new_notification['data']=' Assesor submited screening report section four.'; 
    $new_notification['related_document'] = null;
    $new_notification['related_id'] = $request->application_id;
    $new_notification['alert_level'] = null;
    $new_notification['remark'] = null;
    // ::send($users, new ($invoice));
    
    
    Notification::send($user, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->id, 'Assesor submited screening report section four for application No: '.$request->application_id ));
            
    
    
    
    }
    }
    }
    }


    $application=applications::where('application_id',$request->application_id)->first();

            $duration_days = 10;

            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time =  date('Y-m-d H:i:s');
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Screening';
            $task_activity_title = 'Sample Details';
            $content_details = 'Check list step four saved';
            $route_link = '';
            $activity_status = 'Section four completed';
            $uploaded_document_id = null;

            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category,
            $task_activity_title,
            $content_details,
            $route_link, $activity_status,
            $uploaded_document_id);


      return response()->json([ 'Message'=>true,'application_id'=> $request->application_id


      ]);


     

    }

    catch(Exception $e)
{
           return response()->json(['Message'=>false,'item'=>'error'.$e]);
}




}
    public function submit_section_three(Request $request)
    {
//  dd($request->all());
try{
    $fetch_section_three_to_supervisor= DB::table('checklists')
    ->where('application_id', $request->application_id)
    ->get();

    //dd($fetch_section_three_to_supervisor[0]->supervisor_hold_assessor_progress );

    if(   $fetch_section_three_to_supervisor[0]->supervisor_hold_assessor_progress == '' &&   $fetch_section_three_to_supervisor[0]->Presence_valid_marketing_authorization=='')
    {  
        return response()->json([ 'Message'=>'incorrect','application_id'=> $request->application_id ]);
    }

    else {
        $update = $fetch_section_three_to_supervisor[0]->supervisor_hold_assessor_progress.$request->supervisor_hold_assessor_progress;
        $submit_section_three_supervisor  = DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
            ['supervisor_hold_assessor_progress' =>   $update  ]
        );
    }


    
  

    $dataa = User::orderBy('id','ASC')->get();


    foreach ($dataa as $key => $user)
    {
    if(!empty($user->getRoleNames())) 
    {
    foreach($user->getRoleNames() as $v)
    {
    if($v=='Supervisor')
    {
      $new_notification=[];
      $new_notification['type'] = 'Notification';
      $new_notification['subject'] ='Application Screening Report';
      $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
      $new_notification['data']=' Assesor submited screening Report Section Three'; 
      $new_notification['related_document'] = null;
      $new_notification['related_id'] = $request->application_id;
      $new_notification['alert_level'] = null;
      $new_notification['remark'] = null;
      // ::send($users, new ($invoice));
    
    
    Notification::send($user, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->id, 'Assesor submited screening Report Section Three for Application no : '.$request->application_id ));
            
    
    
    
    }
    }
    }
    }


    $application=applications::where('application_id',$request->application_id)->first();

    

            $main_task = $this->get_main_task_id($application->id,'Application');
   
            $end_time = date('Y-m-d H:i:s');
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Screening';
            $task_activity_title = 'Specific requirements for fast-track registration';
            $content_details = 'Check list step three saved';
            $route_link = '';
            $activity_status = 'Section three completed';
            $uploaded_document_id = null;

            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category,
            $task_activity_title,
            $content_details,
            $route_link, $activity_status,
            $uploaded_document_id);


      return response()->json([ 'Message'=>true,'application_id'=> $request->application_id


      ]);


      



    }

    catch(Exception $e)
{
           return response()->json(['Message'=>false,'item'=>'error'.$e]);
}



    }


    public function submit_section_two(Request $request)
    {
        //  dd($request->all());
        try{
             $fetch_section_one_two_to_supervisor= DB::table('checklists')
            ->where('application_id', $request->application_id)
            ->get();

            if(  $fetch_section_one_two_to_supervisor[0]->supervisor_hold_assessor_progress == '')
            {  
                $submit_section_one_two_to_supervisor= DB::table('checklists')
                ->where('application_id', $request->application_id)
                ->update(
                    ['supervisor_hold_assessor_progress' => $request->supervisor_hold_assessor_progress ]
                );
            }

            else {
                $update = $fetch_section_one_two_to_supervisor[0]->supervisor_hold_assessor_progress.$request->supervisor_hold_assessor_progress;
                $submit_section_one_two_to_supervisor= DB::table('checklists')
                ->where('application_id', $request->application_id)
                ->update(
                    ['supervisor_hold_assessor_progress' =>   $update  ]
                );
            }

            $dataa = User::orderBy('id','ASC')->get();


            foreach ($dataa as $key => $user)
            {
            if(!empty($user->getRoleNames())) 
            {
            foreach($user->getRoleNames() as $v)
            {
            if($v=='Supervisor')
            {
                $new_notification=[];
                $new_notification['type'] = 'Notification';
                $new_notification['subject'] ='Application Screening Report';
                $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
                $new_notification['data']=' Assesor submited screening report section two for application No:'.$request->application_id; 
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $request->application_id;
                $new_notification['alert_level'] = null;
                $new_notification['remark'] = null;
                // ::send($users, new ($invoice));
            
            
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'Assesor submited screening report section two for application No : '.$request->application_id ));
            
            
            
            
            }
            }
            }
            }


            $application=applications::where('application_id',$request->application_id)->first();

           

            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time = date('Y-m-d H:i:s');
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Screening';
            $task_activity_title = 'General requirements check list';
            $content_details = ' check list step two saved';
            $route_link = '';
            $activity_status = 'Section one and two completed';
            $uploaded_document_id = null;

            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category,
            $task_activity_title,
            $content_details,
            $route_link, $activity_status,
            $uploaded_document_id);


              return response()->json([ 'Message'=>true,'application_id'=> $request->application_id
       
        
              ]);






            }
        
            catch(Exception $e)
    {
                   return response()->json(['Message'=>false,'item'=>'error'.$e]);
   }

    
    }



    private function get_main_task_id($application_id, $related_type = 'Application')
    {
        $main_task = MainTask::where('related_id', $application_id)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {

            return 0; //means false
        }
    }

    public function save_section_two(Request $request)
    {
        //  dd($request->all());

        try{
        $checkList  = new checklists;
        $checkList->application_number = '-';
        $checkList->application_id = $request->application_id;
        $checkList->is_application_letter = $request->application_letter;
        $checkList->is_local_agent = $request->local_agent;
        $checkList->is_manufacturer_inforamation = $request->manufacturer_information;
        $checkList->is_enlm = $request->enml;
        $checkList->submitted_dossier_in_CTD_format = $request->dossier_ctd;
        $checkList->is_module_one= $request->module_one;
        $checkList->is_module_two = $request->module_two;
        $checkList->is_module_three = $request->module_three;
        $checkList->is_module_four = $request->module_four;
        $checkList->is_module_five = $request->module_five;
        $checkList->Remark_step_two = $request->Remark_step_two;
        $checkList_check = $checkList->save();
      return response()->json([ 'Message'=>true,'checklistinfo'=>$checkList_check
       
        
        ]);

    }
        
         catch(Exception $e)
            {
                return response()->json(['Message'=>$e,'item'=>'error'.$e]);
             }

    }



    public function update_section_five(Request $request)
    {

        $update_supervisor = DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->first();

        $update_supervisor_added=    $update_supervisor->supervisor_hold_assessor_progress.",5";
 $affected_section_five_checklist= DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
            [
              'is_invoice_number_generated' => $request->Generated_Invoice_Number,
              'is_application_payment_fee' => $request->checking_application_fee,
              'is_application_receipt_number' => $request->Application_Receipt_Number,
              'remark_section_five' => $request->Remark_section_five,
              'over_all_comment' => $request->over_all_comment,
              'supervisor_hold_assessor_progress'=> $update_supervisor_added,
           ]

              );



              if($affected_section_five_checklist == true)
              {



                $application=applications::where('application_id',$request->application_id)->first();



            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time =   date('Y-m-d H:i:s');
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Screening';
            $task_activity_title = 'Payment Details';
            $content_details = 'Check list step five saved';
            $route_link = '';
            $activity_status = 'Section five completed';
            $uploaded_document_id = null;

            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category,
            $task_activity_title,
            $content_details,
            $route_link, $activity_status,
            $uploaded_document_id);



                 return response()->json([
                'section_five_update'=>1,
                'Application_ID'=>$request->application_id ]);
              }
              else
              {
                    return response()->json([
                    'section_five_update'=>0,
                    'Application_ID'=>$request->application_id ]);
                  }



    }





    public function update_section_four(Request $request)
    {



 $affected_section_four_checklist= DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
               [
               'Availability_of_Product_Sample' => $request->sample_product,
               'Number_of_samples_received'  => $request->Number_of_sample_received,
               'Number_of_sample_sent_conforms_with_the_sampling_schedule'  => $request->sample_scheduled,
               'Labelling_Information' => $request->Remark_step_four,
               'Sample_net_volume_or_weight' => $request->net_sample_weight,
               'Sample_net_volume_or_weight_remark' => $request->Sample_net_volume_or_weight_remark,
               'availability_packages' => $request->availability_packages,
               'manufactured_in_the_same_manufacturing_premises' => $request->manufacturing_premises,
               'Availability_of_an_official_of_analysis' => $request->availability_certificate_analysis,
               'Availability_of_an_official_of_analysis_remark' => $request->Availability_of_an_official_of_analysis_remark,
               'Samples_have_at_least_60_perecent' => $request->sample_shelf_life,
               'summernote_Remark_section_four'=> $request->summernote_Remark_section_four,
               'sample_received_date'=> $request->sample_received_date,
                ]
                
              );



              if($affected_section_four_checklist == true)
              {
                 return response()->json([
                'section_four_update'=>1,
                'Application_ID'=>$request->application_id ]);
              }
              else
              {
                    return response()->json([
                    'section_four_update'=>0,
                    'Application_ID'=>$request->application_id ]);
                  }



    }




    public function update_section_three(Request $request)
    {
     
        

          $affected_section_three_checklist= DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
            [
              'Presence_valid_marketing_authorization' => $request->valid_marketing_authorization,
              'Presence_of_the_Quality_Information_Summary' => $request->qis_prequalified_products,
              'Presence_of_full_assessment_report_from_the_reference_authority' => $request->Presence_of_full_assessment_report,
              'Presence_of_the_full_inspection' => $request->Presence_of_the_full_inspection_reports,
              'Presence_of_the_Summary_Product_Characteristics' => $request->Product_Characteristics,
              'Presence_of_the_Patient_information_leaflet' => $request->information_patient_user,
              'Remark_step_three' => $request->Remark_step_three
             ]
              );



              if($affected_section_three_checklist == true)
              {
                return response()->json([
                'section_three_update'=>1,
                'Application_ID'=>$request->application_id ]);
              }
              else
              {
            
                
                    return response()->json([
                    'section_three_update'=>0,
                    'Application_ID'=>$request->application_id ]);
                  }



                }
    


 public function update_section_two(Request $request)
    {
        
     
$affected_section_two_checklist= DB::table('checklists')
        ->where('application_id', $request->application_id)
        ->update(
            [   
              'is_application_letter' => $request->application_letter,
              'is_local_agent' => $request->local_agent,
              'is_manufacturer_inforamation' => $request->manufacturer_information,
              'is_enlm' => $request->enml,
              'is_module_one' => $request->module_one,
              'is_module_two' => $request->module_two,
              'is_module_three' => $request->module_three,
              'is_module_four' => $request->module_four,
              'is_module_five'=> $request->module_five,
              'Remark_step_two' => $request->Remark_step_two,
              'submitted_dossier_in_CTD_format' => $request->dossier_ctd

            ]
              );
           
    if($affected_section_two_checklist == true)
  {
    return response()->json([
    'section_two_update'=>1,
    'Application_ID'=>$request->application_id ]);
  }
  else{

    {
        return response()->json([
        'section_two_update'=>0,
        'Application_ID'=>$request->application_id ]);
      }
  }


            }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function get_checked_values($id)
    {
        //
            $check_list = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->leftjoin('checklists','checklists.application_id','applications.application_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->select('checklists.*','applications.*','invoices.*','contacts.*', 
            'medicines.product_name','medicinal_products.product_trade_name',
             'manufacturers.name as manufacturer_name',
             'company_suppliers.trade_name',
             'invoices.invoice_number',
             'invoices.remark',
             'invoices.amount')
            ->where('applications.application_id',$id)
            ->orderBy('invoices.invoice_number','ASC')
            ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
                     ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                     ->where('contact_type','Agent')
                     ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
                     ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                     ->where('contact_type','Supplier')
                     ->get();
                     
$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$id)
                            ->select('applications.*','medicines.*','medicinal_products.*')
                            ->get();


$dosage_forms = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                            ->select('dosage_forms.*','medicinal_products.*')
                            ->get();

$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

foreach($check_list as $check_lists ) {break;}

if( $check_lists->is_application_letter == 1)
 {   $route_name = 'checklist.process_check_list_partially_Saved'; } 
 else {
    $route_name = 'checklist.process_check_list';
} 


  return view($route_name,[
                'check_list' => $check_list,
                'agent_contact_info' => $agent_contact_info,
                'product_composition_info' => $product_composition_info,
                'api_manufacturers_info' => $api_manufacturers_info,
                'product_enlm_list' => $product_enlm_list,
                'receipts_info' => $receipts_info,
                'dosage_forms' => $dosage_forms,
                'applicant_contact_info' => $applicant_contact_info,
                'invoice_number' => $invoice_number,
                'receipts_number' =>$receipts_number,
                
               
            ]);

    }


    public function get_checked_partially_Saved($id)
    {

        //
        $check_list = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$id)
        ->orderBy('invoices.invoice_number','ASC')
        ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
                 ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                 ->where('contact_type','Agent')
                 ->get();
                 
$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                        ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                        ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                        ->where('applications.application_id',$id)
                        ->select('applications.*','medicines.*')
                        ->get();



                        $applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
                        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                        ->where('contact_type','Supplier')
                        ->get();
                        
   $product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();
   
   $receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();
   
   
   $api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();
   
   
   $product_enlm_list = DB::table('applications')
                               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                               ->where('applications.application_id',$id)
                               ->select('applications.*','medicines.*','medicinal_products.*')
                               ->get();
   
   
   $dosage_forms = DB::table('medicinal_products')
                               ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                               ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                               ->select('dosage_forms.*','medicinal_products.*')
                               ->get();
   

   $invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

   
   $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();


   $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();



return view('checklist.process_check_list_partially_Saved',[
            'check_list' => $check_list,
            'agent_contact_info' => $agent_contact_info,
            'product_composition_info' => $product_composition_info,
            'api_manufacturers_info' => $api_manufacturers_info,
            'product_enlm_list' => $product_enlm_list,
            'receipts_info' => $receipts_info,
            'dosage_forms' => $dosage_forms,
            'applicant_contact_info' => $applicant_contact_info,
            'invoice_number' => $invoice_number,
            'receipts_number' =>$receipts_number,


        ]);



    }




    public function get_checked_partially_Saved_re($id)
    {

        //
        $check_list = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$id)
        ->orderBy('invoices.invoice_number','ASC')
        ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
                 ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                 ->where('contact_type','Agent')
                 ->get();

$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                        ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                        ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                        ->where('applications.application_id',$id)
                        ->select('applications.*','medicines.*')
                        ->get();



                        $applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
                        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                        ->where('contact_type','Supplier')
                        ->get();

   $product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

   $receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


   $api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


   $product_enlm_list = DB::table('applications')
                               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                               ->where('applications.application_id',$id)
                               ->select('applications.*','medicines.*','medicinal_products.*')
                               ->get();


   $dosage_forms = DB::table('medicinal_products')
                               ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                               ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                               ->select('dosage_forms.*','medicinal_products.*')
                               ->get();


   $invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();


   $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();


   $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();



return view('checklist.process_check_list_partially_Saved_re-register',[
            'check_list' => $check_list,
            'agent_contact_info' => $agent_contact_info,
            'product_composition_info' => $product_composition_info,
            'api_manufacturers_info' => $api_manufacturers_info,
            'product_enlm_list' => $product_enlm_list,
            'receipts_info' => $receipts_info,
            'dosage_forms' => $dosage_forms,
            'applicant_contact_info' => $applicant_contact_info,
            'invoice_number' => $invoice_number,
            'receipts_number' =>$receipts_number,

           
        ]);



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function print_process_check_list(Request $request,$id)
    {

 //
 $check_list = DB::table('applications')
 ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
 ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
 ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
 ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
 ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
 ->leftjoin('checklists','checklists.application_id','applications.application_id')
 ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
 ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
 ->where('applications.application_id',$id)
 ->orderBy('invoices.invoice_number','ASC')
 ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
          ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
          ->where('contact_type','Agent')
          ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
          ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
          ->where('contact_type','Supplier')
          ->get();
          
$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                 ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                 ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                 ->where('applications.application_id',$id)
                 ->select('applications.*','medicines.*','medicinal_products.*')
                 ->get();


$dosage_forms = DB::table('medicinal_products')
                 ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                 ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                 ->select('dosage_forms.*','medicinal_products.*')
                 ->get();

$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

foreach($check_list as $check_lists ) {break;}

if( $check_lists->is_application_letter == 1)
{   $route_name = 'checklist.print_checklist'; } 
else {
$route_name = 'checklist.print_checklist';
} 


return view($route_name,[
     'check_list' => $check_list,
     'agent_contact_info' => $agent_contact_info,
     'product_composition_info' => $product_composition_info,
     'api_manufacturers_info' => $api_manufacturers_info,
     'product_enlm_list' => $product_enlm_list,
     'receipts_info' => $receipts_info,
     'dosage_forms' => $dosage_forms,
     'applicant_contact_info' => $applicant_contact_info,
     'invoice_number' => $invoice_number,
     'receipts_number' =>$receipts_number,
     
    
 ]);


    }




  



    public function print_process_check_list_re(Request $request,$id)
    {

 //
 $check_list = DB::table('applications')
 ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
 ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
 ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
 ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
 ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
 ->leftjoin('checklists','checklists.application_id','applications.application_id')
 ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
 ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
 ->where('applications.application_id',$id)
 ->orderBy('invoices.invoice_number','ASC')
 ->get();

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
          ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
          ->where('contact_type','Agent')
          ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
          ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
          ->where('contact_type','Supplier')
          ->get();

$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


$product_enlm_list = DB::table('applications')
                 ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                 ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                 ->where('applications.application_id',$id)
                 ->select('applications.*','medicines.*','medicinal_products.*')
                 ->get();


$dosage_forms = DB::table('medicinal_products')
                 ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                 ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                 ->select('dosage_forms.*','medicinal_products.*')
                 ->get();

$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

foreach($check_list as $check_lists ) {break;}

if( $check_lists->is_application_letter == 1)
{   $route_name = 'checklist.print_checklist_re'; }
else {
$route_name = 'checklist.print_checklist_re';
}


return view($route_name,[
     'check_list' => $check_list,
     'agent_contact_info' => $agent_contact_info,
     'product_composition_info' => $product_composition_info,
     'api_manufacturers_info' => $api_manufacturers_info,
     'product_enlm_list' => $product_enlm_list,
     'receipts_info' => $receipts_info,
     'dosage_forms' => $dosage_forms,
     'applicant_contact_info' => $applicant_contact_info,
     'invoice_number' => $invoice_number,
     'receipts_number' =>$receipts_number,


 ]);


    }

}
