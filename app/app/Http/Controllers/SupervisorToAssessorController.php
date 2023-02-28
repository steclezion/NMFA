<?php

namespace App\Http\Controllers;

use App\Models\supervisor_to_assessor;
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\checklists;

class SupervisorToAssessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * 
     */

     public function all_applications()
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
 ->leftjoin('users','users.id','applications.assigned_To')
 ->join('contacts','contacts.application_id','applications.application_id')
 ->leftjoin('checklists','checklists.application_id','applications.application_id')
 ->select('checklists.*','checklists.application_id as check_app','applications.application_id',
 'medicinal_products.*',DB::raw('concat(users.first_name," ",users.middle_name," ",users.last_name) as fullname'),
 'medicinal_products.product_trade_name as t_name',
 'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
 'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
 'contacts.last_name as clast_name')
 ->where('contacts.contact_type','=','Supplier')
 ->orderBy('applications.application_number','ASC')
 // ->where('applications.user_id',auth()->user()->id)
//  ->where('applications.assigned_By','=',auth()->user()->id)
 ->get();




    return view('supervisor_check_progress_of_assessor.list_applications',[
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
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->join('users','users.id','applications.assigned_To')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->distinct()
        ->select('checklists.*','checklists.application_id as check_app','applications.application_id',
        'medicinal_products.*',DB::raw('concat(users.first_name," ",users.middle_name," ",users.last_name) as fullname'),
        'medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        // ->where('applications.user_id',auth()->user()->id)
        ->where('applications.assigned_By','=',auth()->user()->id)
        ->get();
       

      

           return view('supervisor_check_progress_of_assessor.checklist_assessor',[
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
     * @return \Illuminate\Http\Response
     */
    public function supervisor_track_application_status($id)
    {
        //


           //
           $check_list = DB::table('applications')
           ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
           ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
           ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
           ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
           ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
           ->leftjoin('checklists','checklists.application_id','applications.application_id')
           ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
           ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
            'medicinal_products.product_trade_name',
            'manufacturers.name as manufacturer_name', 
            'manufacturers.state as manufacturer_state',
            'manufacturers.postal_code as manufacturer_postal_code',
            'manufacturers.city as manufacturer_city',
            'manufacturers.addressline_one as manufacturer_address_line_one',
            'manufacturers.addressline_two as manufacturer_address_line_two',
            'manufacturers.activity as manufacturer_activity',
            'manufacturers.block as manufacturer_block',
            'manufacturers.unit as manufacturer_unit',
            'manufacturers.telephone as manufacturer_telephone',
            'company_suppliers.trade_name as tname',
            'company_suppliers.country_id as cid',
            'contacts.country_id as cont_country_id',
            'company_suppliers.state as company_supplier_state',
            'company_suppliers.address_line_one as company_supplier_address_line_one',
            'company_suppliers.address_line_two as company_supplier_address_line_two',
            'company_suppliers.email as cs_email',
            'company_suppliers.postal_code as cs_postal_code',
            'company_suppliers.webiste_url as cs_webiste_url',
            'contacts.first_name  as   con_first_name',
            'contacts.middle_name as   con_middle_name',
            'contacts.last_name   as   con_last_name',
            'contacts.position   as   con_position',
            'contacts.city   as   con_city',
            'contacts.address_line_one as contacts_address_line_one',
            'contacts.address_line_two as contacts_address_line_two',
            'contacts.telephone as contacts_telephone',
           'invoices.invoice_number','invoices.remark','invoices.amount')
           ->where('applications.application_id',$id)
           ->orderBy('invoices.invoice_number','ASC')
           ->get();
   
   $agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
                    ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                    ->select(
            'contacts.country_id as cont_country_id',
            'contacts.first_name     as    con_first_name',
            'contacts.middle_name    as   con_middle_name',
            'contacts.last_name      as   con_last_name',
            'contacts.position       as   con_position',
            'contacts.city           as   con_city',
            'contacts.address_line_one as con_address_line_one',
            'contacts.address_line_two as con_address_line_two',
            'contacts.telephone as con_telephone',
            'agents.trade_name as ag_trade_name',
            'agents.state as ag_state',
            'agents.country_id as ag_country_id',
            'agents.address_line_one as ag_address_line_one',
            'agents.address_line_two as ag_address_line_two',
            'agents.city as ag_city',
            'agents.postal_code as ag_postal_code',
            'agents.country_code  as ag_country_code',
            'agents.telephone as ag_telephone',
            'agents.webiste_url as ag_webiste_url',
            'agents.email as ag_email',

            )  ->where('contact_type','Agent')
               ->get();
                    
   $product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$id)->get();
   
   $receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();



   $company_supplier_info_country = DB::table('countries')->where('countries.id',$check_list[0]->cid)->get();
   $company_supplier_cont_country_country = DB::table('countries')->where('countries.id',$check_list[0]->cont_country_id)->get();
   
   
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
      
      $company_suppliers = DB::table('company_suppliers')->where('company_suppliers.application_id',$id)->get();

      $dosage_forms = DB::table('medicinal_products')
                                  ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                                  ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                                  ->select('dosage_forms.*','medicinal_products.*')
                                  ->get();
      
      $invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();
      
      $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();


      $product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select( 'medicinal_products.id as _id', 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name', 'route_administrations.name as route_name', 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id', 'route_administrations.id as route_id', 'dosage_forms.id as dosage_id', 'medicines.*', 
 'medicinal_products.*',
'route_administrations.*', 'dosage_forms.*',
  )
->where('medicinal_products.application_id',$id)
->get();
   
   

@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
            ->where('declarations.application_id',$id)
                            ->select('declarations.*','declarations.name as decname','documents.*')
                            ->get();


   return view('supervisor_check_progress_of_assessor.process_track_application_description',[
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
               'company_supplier_info_country' => $company_supplier_info_country,
               'company_supplier_cont_country_country' => $company_supplier_cont_country_country,
               'company_suppliers' => $company_suppliers,
               'product_details' =>  $product_details,
               'decleration_info' => $decleration_info,
               
               
              
           ]);
   
   

           



    }





    public function supervise_assessor_progress($id)
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


return view('supervisor_check_progress_of_assessor.process_check_list_partially_Saved_assessor',[
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


    
    public  function  app_deadline_index()
    {
        $locked_application_tasks = applications::join('main_tasks','main_tasks.related_id','applications.id')
           ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
           ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
           ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
           ->join('task_trackers','task_trackers.task_id','main_tasks.id')
           ->join('users','users.id','applications.assigned_To')
               ->where('task_trackers.activity_status','Locked')
               ->where('main_tasks.related_task','Application')
               ->where('applications.assigned_By',auth()->user()->id)
               ->select('task_trackers.*','task_trackers.id','users.first_name','users.middle_name','medicines.product_name','company_suppliers.trade_name','task_trackers.start_time','task_trackers.end_time','task_trackers.activity_status','task_trackers.deadline_extended')
               ->get();
   
        

        return view('supervisor.app_deadline_index',['locked_application_tasks'=>$locked_application_tasks]);

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
     * @param  \App\Models\supervisor_to_assessor  $supervisor_to_assessor
     * @return \Illuminate\Http\Response
     */
    public function show(supervisor_to_assessor $supervisor_to_assessor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\supervisor_to_assessor  $supervisor_to_assessor
     * @return \Illuminate\Http\Response
     */
    public function edit(supervisor_to_assessor $supervisor_to_assessor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\supervisor_to_assessor  $supervisor_to_assessor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, supervisor_to_assessor $supervisor_to_assessor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\supervisor_to_assessor  $supervisor_to_assessor
     * @return \Illuminate\Http\Response
     */
    public function destroy(supervisor_to_assessor $supervisor_to_assessor)
    {
        //
    }
}
