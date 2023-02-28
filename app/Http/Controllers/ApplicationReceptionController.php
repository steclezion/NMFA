<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FPDF;
use App\Models\certification;

use App\Models\applications;
use App\Models\Country;
use App\Models\receipt;
use App\Models\agents_template;
use App\Models\company_suppliers_template;
use App\Models\payment_configuration;
use App\Models\invoices;
use App\Models\declerations;
use App\Models\Variation;
use App\Models\check_re_registered_application;
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
use App\Models\medicines;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Events\ApplicationReceiptionEvent;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Models\TaskTracker;
use App\Models\documents;

use PDF;
use DataTables;


class ApplicationReceptionController extends Controller
{
    // It uses to give a retrieval for all request come from the view section

    public $return_data,$Email,$i,$tele,$code,$Url;

    function __construct()

    {

          $this->middleware('permission:application-list|application-status-list|assesor_roles|nmfa_director|supervisor_roles');

          $this->middleware('permission:role-list|role-create|role-edit|role-delete|nmfa_director', ['only' => ['index','store']]);

          $this->middleware('permission:role-create', ['only' => ['create','store']]);
  
          $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
  
          $this->middleware('permission:role-delete', ['only' => ['destroy']]);


    }

 


    public function dec()
    {

      return view('application_reception.Application_form_for_registration_of_a_medicine_in_Eritrea');

    }


    public function delete_file_swift_payment(Request $request)
    {

  //dd($request->all());
  $documents  = invoices::join('documents','documents.id','invoices.uploaded_swift_document_id')
  ->select('documents.*','invoices.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('invoices.application_id','=',$request->application_id)->where('documents.document_type','=',36)
  ->first();

  $path = public_path('storage/app/public/Upload_swift_payments/'.$documents->dname);
  Storage::delete('public/Upload_swift_payments/'.$documents->dname);



  $doc =DB::table('documents')->where('id', '=', $request->document_id)->delete();
  
  $update_applications = DB::table('invoices')
  ->where('invoices.application_id', $request->application_id)
  ->update([
  'uploaded_swift_document_id'=>  0
  
  ]);


  $return_data='';
   
  $doc_upload_swift_payment = invoices::join(
    'documents','documents.id','invoices.uploaded_swift_document_id')
    ->select('documents.*','invoices.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
    ->where('invoices.application_id','=',$request->application_id)
    ->where('documents.document_type','=',36)
    ->get();

  


$i=1;   $return_data='';
foreach( $doc_upload_swift_payment as $user_upload)
{

  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR '   id='Download_File' >   ".$user_upload->dname."</a>
  </td>";
  
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
  data-document_id='$user_upload->did' 
  data-id='$request->application_id' 
  data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>"; 


}
      

return response()->json(['Data_returned'=>$return_data ]);


    }

    public function fetch_file_swift_payment(Request $request)
    {


      $doc_upload_swift_payment = invoices::join(
        'documents','documents.id','invoices.uploaded_swift_document_id')
        ->select('documents.*','invoices.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
        ->where('invoices.application_id','=',$request->application_id)
        ->where('documents.document_type','=',36)
        ->get();


$i=1;   $return_data='';
foreach($doc_upload_swift_payment as $user_upload)

{

$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td id='seqence_number_$user_upload->id' >
<a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR '   id='Download_File' >   ".$user_upload->dname."</a>
</td>";

$return_data .= "<td>".$user_upload->uploaded_Date."</td>";
$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-document_id='$user_upload->did' 
data-id='$request->application_id' 
data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    

}



return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);


    }

    public function upload_file_swift_payment(Request $request)
    {
//dd($request->all());
 try
 {
$validatedData = $request->validate([
// 'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:204800',
'file' => 'required|mimes:pdf,docx,doc|max:204800',
// Validate that an uploaded file is exactly 512 kilobytes...
// 'file' => 'file<size:512'

]);


$name = "SWIFT_PAYMENT_ATTACH_".strtoupper($request->file('file')->getClientOriginalName());


$time=time();

// $path = $request->file('file')->store('public/images');

$path = public_path('storage/Upload_swift_payments/');

// <--- folder to store the pdf documents into the server;
$fileName =  $name."-".$request->app_name.$time.".".$request->file('file')->extension();  // <--giving the random filename,

$reference_number = $name."-".$request->app_name;
$filePath = $request->file('file')->storeAs('Upload_swift_payments//', $fileName, 'public');
$generated_pdf_link = Storage::url('public/Upload_swift_payments/'.$fileName);



//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '36';
$documents->ref_num = $reference_number;
$documents->description = 'Upload swift payment for requested invoice';
$documents->save();


//dd($request->application_id);


$application=applications::where('application_id', $request->application_id)->first();


$user=User::where('id', $application->assigned_To)->first();
//$user = User::find($application->assigned_To);



$new_notification=[];
$new_notification['type'] = 'Notification';
$new_notification['subject'] ='Application Swift payment has been uploaded ';
$new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
$new_notification['data']=' Application swift payment for application number   '. $request->application_number.' ' ; 
$new_notification['related_document'] = $documents->ref_num;
$new_notification['related_id'] = $request->application_id;
$new_notification['alert_level'] = null;
$new_notification['remark'] = null;

Notification::send($user, new ApplicationReceiptionNotification($new_notification));
  event(new ApplicationReceiptionEvent($user->id, 'Application Swift payment has been uploaded for application number: '.$request->application_number ));


$update_applications = DB::table('invoices')
->where('invoices.application_id', $request->application_id)
->update([
'uploaded_swift_document_id'=>  $documents->id
]);



$application=applications::where('application_id',$request->application_id)->first();




$update_main_task_status = DB::table('main_tasks')
->where('related_id', $application->id)
->where('related_task', 'Application')
->update([
'task_status' => 'Applicant uploaded Swift payment',
]);



$doc_upload_swift_payment = invoices::join(
'documents','documents.id','invoices.uploaded_swift_document_id')
->select('documents.*','invoices.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
->where('invoices.application_id','=',$request->application_id)
->where('documents.document_type','=',36)
->get();

//dd( $issue_queries);


$i=1;   $return_data='';
foreach($doc_upload_swift_payment as $user_upload)

{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td id='seqence_number_$user_upload->id' >
<a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR '   id='Download_File' >   ".$user_upload->dname."</a>
</td>";
$return_data .= "<td>".$user_upload->uploaded_Date."</td>";
$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-document_id='$user_upload->did' 
data-id='$request->application_id' 
data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";

}

return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);


 }

 catch(Exception $e)
 {

    return response()->json(['Message'=>false,'item'=>'error'.$e]);

 }


    }


    public function  attach_payment_swift(Request $request)
    {


  $attach_payment = DB::table('receipts')
          ->join('invoices','receipts.invoice_id','=','invoices.id')
          // ->leftjoin('contacts','contacts.application_id','receipts.application_id')
          ->leftjoin('medicinal_products', 'receipts.application_id', '=', 'medicinal_products.application_id')
          ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
          ->join('documents','receipts.invoice_document_id','=','documents.id')
          ->join('applications','applications.application_id','=','receipts.application_id')
          ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
          ->select('applications.*','receipts.*','invoices.*',
                     'documents.*','medicinal_products.*',    
                    //  DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
                     'medicinal_products.*',
                     'medicinal_products.product_trade_name as t_name',
                     'company_suppliers.trade_name as cs_tradename',
                     'medicines.*')
          //->whereNull('receipt_document_id')
          ->where('applications.user_id','=',auth()->user()->id)
          ->get();

          return view(
                      'Attach_payment_swift.attach_payment_swift',
                      [

                      'attach_payment' => $attach_payment,        
                      
                      ]);


    }



    public function Application_general_print($id)
    {

      $check_list = DB::table('applications')
      ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
      ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
      ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
      ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
      ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
      ->leftjoin('checklists','checklists.application_id','applications.application_id')
      ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
      ->select('medicinal_products.*','checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
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
       'company_suppliers.telephone as cs_telephone',
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
       'contacts.fax as contacts_fax',
      'invoices.invoice_number','invoices.remark','invoices.amount')
      ->where('applications.application_id',$id)
      ->where('contact_type','Supplier')
      ->orderBy('invoices.invoice_number','ASC')
      ->get();

    //  dd($check_list );
$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
        ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
        ->select(
       'contacts.country_id as cont_country_id',
       'contacts.first_name     as    con_first_name',
       'contacts.middle_name    as   con_middle_name',
       'contacts.last_name      as   con_last_name',
       'contacts.position       as   con_position',
       'contacts.city           as   con_city',
       'contacts.email          as   con_email',
       'contacts.fax as contacts_fax_ag',
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
       'agents.email as ag_email'
        )
       ->where('contact_type','Agent')->get();

         // dd($agent_contact_info);
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
                             ->join('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
                             ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                             ->select('dosage_forms.*','medicinal_products.*','route_administrations.name as route_name')
                             ->get();

 $invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

 $receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

 $manufacturers_info = DB::table('manufacturers')->where('manufacturers.application_id',$id)
 ->select('manufacturers.name as manufacturer_name','manufacturers.name as manufacturer_name',
 'manufacturers.state as manufacturer_state',
 'manufacturers.postal_code as manufacturer_postal_code',
 'manufacturers.city as manufacturer_city',
 'manufacturers.addressline_one as manufacturer_address_line_one',
 'manufacturers.addressline_two as manufacturer_address_line_two',
 'manufacturers.activity as manufacturer_activity',
 'manufacturers.block as manufacturer_block',
 'manufacturers.unit as manufacturer_unit',
 'manufacturers.telephone as manufacturer_telephone','manufacturers.*')
 ->get();

//dd($manufacturers_info);

 $product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select( 'medicinal_products.id as _id', 'medicinal_products.medicine_id as medicine_id',
'medicinal_products.product_trade_name as product_trade_namme',
'medicines.product_name as medicine_product_name', 'route_administrations.name as route_name', 'dosage_forms.name as dosage_name',
'medicines.id as medicinal_id', 'route_administrations.id as route_id', 'dosage_forms.id as dosage_id', 'medicines.*',
'medicinal_products.*',
'route_administrations.*', 'dosage_forms.*'
)
->where('medicinal_products.application_id',$id)
->get();



@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
       ->where('declarations.application_id',$id)
                       ->select('declarations.*','declarations.name as decname','documents.*')
                       ->get();

$manufacturers_info_for_declaration = DB::table('manufacturers')->where('manufacturers.application_id',$id)
->select('manufacturers.name as manufacturer_name',
'manufacturers.state as manufacturer_state',
'manufacturers.postal_code as manufacturer_postal_code',
'manufacturers.city as manufacturer_city',
'manufacturers.addressline_one as manufacturer_address_line_one',
'manufacturers.addressline_two as manufacturer_address_line_two',
'manufacturers.activity as manufacturer_activity',
'manufacturers.block as manufacturer_block',
'manufacturers.unit as manufacturer_unit',
'manufacturers.telephone as manufacturer_telephone','manufacturers.*')
->orderBy('created_at','ASC')
->first();



return view('application_reception.Application_form_for_registration_of_a_medicine_in_Eritrea_print',[

          'check_list' => $check_list,
          'manufacturers_info' => $manufacturers_info,
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
          'manufacturers_info_for_declaration'=>$manufacturers_info_for_declaration
          ]);






    }

    public function completeApplicationStatus($id)
    {

           $check_list = DB::table('applications')
           ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
           ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
           ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
           ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
           ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
           ->leftjoin('checklists','checklists.application_id','applications.application_id')
           ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
           ->select('medicinal_products.*','checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
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



$manufacturers_info = DB::table('manufacturers')->where('manufacturers.application_id',$id)
->select('manufacturers.name as manufacturer_name','manufacturers.name as manufacturer_name',
'manufacturers.state as manufacturer_state',
'manufacturers.postal_code as manufacturer_postal_code',
'manufacturers.city as manufacturer_city',
'manufacturers.addressline_one as manufacturer_address_line_one',
'manufacturers.addressline_two as manufacturer_address_line_two',
'manufacturers.activity as manufacturer_activity',
'manufacturers.block as manufacturer_block',
'manufacturers.unit as manufacturer_unit',
'manufacturers.telephone as manufacturer_telephone','manufacturers.*')
->get();


$manufacturers_info_for_declaration = DB::table('manufacturers')->where('manufacturers.application_id',$id)
->select('manufacturers.name as manufacturer_name',
'manufacturers.state as manufacturer_state',
'manufacturers.postal_code as manufacturer_postal_code',
'manufacturers.city as manufacturer_city',
'manufacturers.addressline_one as manufacturer_address_line_one',
'manufacturers.addressline_two as manufacturer_address_line_two',
'manufacturers.activity as manufacturer_activity',
'manufacturers.block as manufacturer_block',
'manufacturers.unit as manufacturer_unit',
'manufacturers.telephone as manufacturer_telephone','manufacturers.*')
->orderBy('created_at','ASC')
->first();


@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
            ->where('declarations.application_id',$id)
                            ->select('declarations.*','declarations.name as decname','documents.*')
                            ->get();


   return view('application_reception.Application_form_for_registration_of_a_medicine_in_Eritrea',[
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
               'manufacturers_info' => $manufacturers_info,
               'manufacturers_info_for_declaration' => $manufacturers_info_for_declaration,

               ]);
}





public function application_futher_process(Request $request,$id)
{
  //dd($request->all());

  $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

  $applications = applications::where('applications.user_id',auth()->user()->id)
      //    ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
         ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
         ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
         ->leftjoin('users','users.id','applications.user_id')
         ->join('contacts','contacts.application_id','applications.application_id')
         ->select('medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
         'company_suppliers.trade_name as cs_tradename','applications.*','applications.id as Id_of_application','contacts.*','contacts.first_name as cfirst_name',
         'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
         ->where('applications.application_id',$id)
         ->where('contacts.contact_type','=','Supplier')
          ->orderBy('applications.application_number','ASC')
         ->get();


   @$applications_re_register_only_applicant_type = applications::where('applications.user_id',auth()->user()->id)
             // ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
                ->leftjoin('check_re_registered_applications','check_re_registered_applications.application_id','applications.application_id')
                ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
                ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
                ->leftjoin('users','users.id','applications.user_id')
                ->leftjoin('contacts','contacts.application_id','applications.application_id')
                ->select('check_re_registered_applications.application_id as appid','medicinal_products.*',
                'medicinal_products.product_trade_name as t_name','company_suppliers.*',
                'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
                'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
                'contacts.last_name as clast_name')
                ->distinct()
                 ->where('check_re_registered_applications.old_id','=',$id)
               // ->where('contacts.contact_type','like','Supplier%')
                 ->where('applications.registration_type','=','Re-new')
                 ->orderBy('applications.re_registration_number','DESC')
                 ->get();



  @$applications_re_register = applications::where('applications.user_id',auth()->user()->id)
  // ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
     ->leftjoin('check_re_registered_applications','check_re_registered_applications.application_id','applications.application_id')
     ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
     ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
     ->leftjoin('users','users.id','applications.user_id')
     ->leftjoin('contacts','contacts.application_id','applications.application_id')
     ->select('check_re_registered_applications.application_id as appid','medicinal_products.*',
     'medicinal_products.product_trade_name as t_name','company_suppliers.*',
     'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
     'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
     'contacts.last_name as clast_name')
     ->distinct()
      ->where('check_re_registered_applications.old_id','=',$id)
     ->where('contacts.contact_type','like','Supplier%')
      ->where('applications.registration_type','=','Re-new')
      ->orderBy('applications.re_registration_number','DESC')
      ->get();
             

      


       if(@$applications_re_register_only_applicant_type[0]->cfirst_name == null) {$route =$applications_re_register_only_applicant_type; }
       if(@$applications_re_register_only_applicant_type[0]->cfirst_name != null) {$route =$applications_re_register; }


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
          'agents.email as ag_email'

          )->where('contact_type','Agent')
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
                                 'medicines.id as medicinal_id', 'route_administrations.id as route_id', 'dosage_forms.id as dosage_id', 'medicines.*', 'medicinal_products.*',
                'route_administrations.*', 'dosage_forms.*'
)
->where('medicinal_products.application_id',$id)
->get();
 
// dd($id);


$application_withdrawal = applications::join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
->join('dossier_assignments','dossier_assignments.application_id','applications.id')
->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
->leftjoin('certifications','certifications.decision_id','decisions.id')
->select('applications.id', 'applications.application_id' ,'applications.application_number','market_status',
    'medicinal_products.product_trade_name', 'certifications.registration_number',
    'medicines.product_name','applications.application_id', 'company_suppliers.trade_name', 'company_suppliers.trade_name')
    ->where('applications.application_id','=',$id)
->first();



@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
->where('declarations.application_id',$id)->select('declarations.*','declarations.name as decname','documents.*')->get();

        // Start ===================================================================================================================================

//added by Release 2 Team to enable/disable  Re-register button in list of completed applications

    // fetch registration status of application with application_id
    // If status = reregistration_open, enable button re-register (allow applicant to register), else disable button re-register
    try{
    $certification = DB::table('certifications')
        ->join('decisions', 'certifications.decision_id', 'decisions.id')
       ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
        ->join('applications', 'dossier_assignments.application_id', 'applications.id')
        ->where('applications.application_id',$id)
         ->select('certifications.*')
        ->first();

    // compute time-count-down from now to expiry date.

    if ($certification)
        $remaining_time = Carbon::now()->diff(Carbon::create($certification->expiry_date));
    else
        return Redirect()->back()->with('info', 'No Certified Applications Found.');

    $remaining_time_formatted = $this->format_countdown_timer($remaining_time);

    $reregistration_dashboard = array();

    if ($certification->status == 'reregistration_closed') {  // = registration_active


        $status_theme = 'small-box bg-success';
        $status_text = 'Active';
        $tooltip_message = 'Registration is active for 5 years since date of certification (' . $certification->certified_date . ')';

        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;

    } elseif ($certification->status == 'reregistration_open') {


        $status_theme = 'small-box bg-warning';
        $status_text = 'Reregistration Open';
        $tooltip_message = 'Reregistration is open until Date of Certificate Expiry. 
                The Remaining Time is displayed on the Countdown Timer';


        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;

    } elseif ($certification->status == 'reregistration_expired') {

        $remaining_time_formatted = 0;
        $status_theme = 'small-box bg-danger';
        $status_text = 'Expired';

        if($certification->reregister_requested_deadline != null) {  //this expiry is after renewal_request
            $tooltip_message = 'The Reregistration opportunity allowed as per your renewal request has expired. 
            Reregistration has been disabled permanently.';
        }else{ //this expiry is after regular period has expired
        $tooltip_message = 'Reregistration period has expired. 
            You may request extension if still interested in Marketing the product.';
        }

        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;

    } elseif ($certification->status == 'renewal_requested') {

        $remaining_time_formatted = 0;
        $status_theme = 'small-box bg-warning';
        $status_text = 'Renewal Requested';
        $tooltip_message = 'Re-registration Renewal Request has been sent. Please wait for approval decision.';

        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;

    } elseif ($certification->status == 'renewal_request_accepted') {

        $remaining_time = Carbon::now()->diff(Carbon::create($certification->reregister_extended_deadline));
       // $remaining_time_formatted = $remaining_time->format('%yy %mm %dd %Hhr %Imin');


        $remaining_time_in_minutes = Carbon::now()->diffInMinutes(Carbon::create($certification->reregister_extended_deadline), false);
        if ($remaining_time_in_minutes < 0) {

            $remaining_time_formatted = 0;  //Extended period for renewal has expired
        }

        $status_theme = 'small-box bg-info';
        $status_text = 'Renewal Request Accepted';
        $tooltip_message = 'Re-registration Renewal Request has been accepted. 
            Please Click RENEW button below to initiate Reregistration. 
            See the Countdown timer for Reregistration expiry information.';

        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;

    } elseif ($certification->status == 'reregistration_initiated') {

        $remaining_time_formatted = 0;
        $status_theme = 'small-box bg-info';
        $status_text = 'Reregistration Initiated';
        $tooltip_message = 'The application is undergoing re-registration, which will follow its own evaluation procedure.';

        $reregistration_dashboard['status_theme'] = $status_theme;
        $reregistration_dashboard['status_text'] = $status_text;
        $reregistration_dashboard['tooltip_message'] = $tooltip_message;
        $reregistration_dashboard['remaining_time_formatted'] = $remaining_time_formatted;


    } else {

        //unknown status
        return Redirect()->back()->with('danger', 'Error caused due to Unknown Registration Status. Check Table certifications.');

    }
    }catch (Exception $e){
        return Redirect()->back()->with('danger', 'FILE: '. $e->getFile(). ' LINE: '. $e->getLine(). ' ERROR: '. $e->getMessage());
    }


    // END ===================================================================================================================================




    $application = DB::table('certifications')->where('certifications.id',$id)
        ->join('decisions','decisions.id','certifications.decision_id')
        ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('applications','applications.id','dossier_assignments.application_id')
        ->select('applications.*')
        ->first();


       $variations=Variation::where('variations.certificate_id',$id)
       ->join('certifications','certifications.id','variations.certificate_id')
       ->leftjoin('variation_decisions','variation_decisions.variation_id','variations.id')
       ->select('variations.*','certifications.certificate_number','variation_decisions.decision_status')
       ->get();
       
      //  return view('variations.index',[
      //      'variations'=>$variations,
      //      'application'=>$application,
      //      'certification'=>$certification

      //      ]);
      //dd($application);

    return view('application_reception.application_further_processing',[
      'company_suppliers_template' =>$company_suppliers_template,
        'applications'=>$applications,
        'application'=>$application_withdrawal,
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
               'applications_re_register'=> $route,
                'application_id' => $id,
                'certification' => $certification,
            'remaining_time_formatted' => $remaining_time_formatted,
            'reregistration_dashboard' => $reregistration_dashboard
        ]);



}


public function generate_new_application(Request $request)
{
//dd($request->all());

try
{
$applications =  new applications;
$t=time();
$year = Date('Y');
$count = applications::where('application_number', '<>', '')->count();
$count_sequence = $count + 1;
$zero_filled_counter = sprintf('%04d', $count_sequence);

$squential_application_number= 'NMFA/AR/'.$year."/".$zero_filled_counter;

$squential_application_number_dossier = 'NMFA_AR_'.$year."_".$zero_filled_counter;

// $select_id_from_medicinal_products = medicinal_products::where('application_id',$request->application_id)->first();

// $select_id_from_medicines = medicines::where('id',$select_id_from_medicinal_products->medicine_id )->first();


$select_id_from_medicines = medicinal_products::join('medicines','medicines.id','medicinal_products.medicine_id')
                                                         ->where('medicinal_products.application_id',$request->application_id)
                                                         ->select('medicines.*','medicinal_products.*')
                                                         ->first();

if( ($select_id_from_medicines->is_enlm) == 0  )
{

$applications = applications::where('application_id',$request->application_id)->first();
$main_task = $this->get_main_task_id($applications->id,'Application');
$end_time = date('Y-m-d H:i:s');
$issued_datetime = date('Y-m-d H:i:s');
$task_category = 'Applying';
$task_activity_title = 'Product is outside of ENML';
$content_details = 'Submitted product is outside of the ENML for application number'.$applications->application_number;
$route_link = '';
$activity_status = 'Locked';
$uploaded_document_id = null;
MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);


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
                $new_notification['subject'] ='Submitted product is outside of the ENML';
                $new_notification['from_user'] = 'System Reminder';
                $new_notification['data'] = 'Product outside ENML';
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $request->application_id;
                $new_notification['alert_level'] = null;
                $new_notification['remark'] = null;
              // ::send($users, new ($invoice));   
              Notification::send($user, new ApplicationReceiptionNotification($new_notification));
              event(new ApplicationReceiptionEvent($user->id, 'Submitted product is outside of the ENML :'. $applications->application_number ));
              }
            }
          }
        }
}



$update_application_number = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'application_number' =>$squential_application_number,
  'created_at' => now(),
  ]
);



//Update Hold Progress Bar Report

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

//Create a NEw Directory using the Specified Application number
// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

// // Uses to insert data in to the Acknowledgment Letter

//Create directory For Storing dossier files
$path = public_path('dossiers')."/".$squential_application_number_dossier;

$generated_dossier_path_application_number = $squential_application_number_dossier;

$Dossier_File_creation_directory = mkdir($path,0777);
$change_mode =  chmod($path,0777);

$update_applications = DB::table('applications')
->where('applications.application_id', $request->application_id)
->update([
   'dossier_actual_path' => $generated_dossier_path_application_number ,
   're_registration_number' => 'R0'
   ]);





   $applications = applications::where('application_id',$request->application_id)->first();
   $main_task = $this->get_main_task_id($applications->id,'Application');
   $end_time = date('Y-m-d H:i:s');
   $issued_datetime = date('Y-m-d H:i:s');
   $task_category = 'Applying';
   $task_activity_title = 'Application submitted successfully';
   $content_details = 'Application with application number='.$squential_application_number."registered successfully";
   $route_link = '';
   $activity_status = 'Locked';
   $uploaded_document_id = null;

   //MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
   

   Tasktracker::where('task_id', $main_task->id)->where('task_category', 'Applying')->where('activity_status', 'Inprogress')->update(['activity_status' => 'Completed' ]);
   
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
                   $new_notification['subject'] ='Application has been submitted successfully';
                   $new_notification['from_user'] = 'System Reminder';
                   $new_notification['data'] = 'Application has been submitted successfully';
                   $new_notification['related_document'] = null;
                   $new_notification['related_id'] = $request->application_id;
                   $new_notification['alert_level'] = null;
                   $new_notification['remark'] = null;
                 // ::send($users, new ($invoice));
   
   
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                 event(new ApplicationReceiptionEvent($user->id, 'Application has been submitted successfully'. $applications->application_number ));
   
   
                 }
               }
             }
           }


return response()->json(['Message'=>true,'application_number'=> $squential_application_number]);
}


catch(Exception $e)
{
return response()->json(['Message'=>$e,'item'=>'error'.$e]);
}


}





public function generate_re_new_application(Request $request)
{
  try
  {
$applications =  new applications;

$getRegister_number = check_re_registered_application::where('application_id', '=', $request->application_id)->first();

$t=time();
$year = Date('Y');
$count = applications::where('application_number', '<>', '')->count();
$count_sequence = $count + 1;
$zero_filled_counter = sprintf('%04d', $count_sequence);

// $squential_application_number= 'NMFA/AR/'.$year."/".$zero_filled_counter;

// $squential_application_number_dossier = 'NMFA_AR_'.$year."_".$zero_filled_counter;



$update_application_number = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
 
  're_registration_number' => $getRegister_number->re_registration_number,
  ]
);








//Update Hold Progress Bar Report

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

//Create a NEw Directory using the Specified Application number

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

 // Uses to string i replace the / with _ 
$squential_re_registration_number_dossier  =  str_ireplace("/","_",$getRegister_number->re_registration_number);



//Create directory For Storing dossier files
$path = public_path('dossiers')."/".$squential_re_registration_number_dossier;
// $generated_dossier_path = Storage::url('public/dossiers/'.$squential_application_number_dossier);
$generated_dossier_path_application_number = $squential_re_registration_number_dossier;

//dd(Storage::deleteDirectory($path));

$Dossier_File_creation_directory = mkdir($path,0777);
$change_mode =  chmod($path,0777);



$update_applications = DB::table('applications')
->where('applications.application_id', $request->application_id)
->update([ 'dossier_actual_path' => $generated_dossier_path_application_number ]);




return response()->json(['Message'=>true,'re_registration__number'=> $getRegister_number->re_registration_numberr]);


   }
catch(Exception $e)
{
return response()->json(['Message'=>$e,'item'=>'error'.$e]);
}


}





public function application_reception_re_registration(Request $request,$id)
{

 

    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    $route_administrations = route_administrations::all()->sortBy('name');
    $agents = agents::all()->sortBy('trade_name');
    $agents_template = agents_template::all()->sortBy('trade_name');
    $company_suppliers = company_suppliers::all()->sortBy('trade_name');
    $company_suppliers_template = DB::select('select * from  company_supplier_template  where (is_Approved_By_NMFA = 1 )  order by trade_name  ASC');
    $product_details = DB::select('select * from  medicines where (is_enlm =1 and is_approved=1)  order by product_name ASC');


    // $application_details = DB::select(" select * from  applications where (application_id = $id ) ");
       $application_details= DB::table('applications')->where('applications.application_id',$id)->get();

       $requestregistered_applications = DB::table('check_re_registered_applications')->where('check_re_registered_applications.old_id',$id)->orderBy('check_re_registered_applications.old_id','DESC')->first();
       //dd($requestregistered_applications);

       $requestregistered_applications_count = DB::table('check_re_registered_applications')->where('check_re_registered_applications.old_id',$id)->count();

      // $request->application_id= $requestregistered_applications->application_id;
   //   dd($requestregistered_applications[0]->application_id);

    $application_check_wizard  = applications::where('application_id',$id)->get();
    $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
    // $application_details = DB::select(" select * from  applications where (application_id = $id ) ");
       $application_details= DB::table('applications')->where('applications.application_id',$id)->get();

       $check_re_registered_applications = DB::table('check_re_registered_applications')->where('check_re_registered_applications.old_id',$id)->orderBy('check_re_registered_applications.old_id','DESC')->first();
       //dd($check_re_registered_applications);

       $check_re_registered_applications_count = DB::table('check_re_registered_applications')->where('check_re_registered_applications.old_id',$id)->count();
       
      // $request->application_id= $check_re_registered_applications->application_id;


       $countries = Country::all()->sortBy('country_name');;
       $fast_track_applications =  fast_track_application::all()->sortBy('name');;
       $dosage_forms  = DosageForms::all()->sortBy('name');;
       $apis  = apis::all()->sortBy('api_name');;  
       $route_administrations = route_administrations::all()->sortBy('name');
       $agents = agents::all()->sortBy('trade_name');
       $company_suppliers = company_suppliers::all()->sortBy('trade_name');
       $product_detailss =  product_details::all()->sortBy('product_name');
       $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name'); 

      //  if(in_array('8',$explode)){ $view_application_re_registration= 'application_reception.view_completed_application_re_registration';}
      //  else if( in_array('0',$explode) || $application_check_wizard[0]->hold_progress_wizard ==''    ){$view_application_re_registration = 'application_reception.application_reception_re_registration';}
      //  else { $view_application_re_registration='application_reception.application_reception_re_registration_update';}

      $view_application_re_registration = ($requestregistered_applications_count == 1) ? 'application_reception.application_reception_re_registration_update' : 'application_reception.application_reception_re_registration';

      if($requestregistered_applications_count == 1){      $request= $requestregistered_applications;    }

      elseif($requestregistered_applications_count == 0 ){  $request= $request;                           }
      //dd($requestregistered_applications_count);

       $application_check_wizard  = applications::where('application_id',$request->application_id)->get();

       $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
       $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
       $agents_template = agents_template::all()->sortBy('trade_name');
   
   
        $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
        ->where('contact_type','Supplier')
        ->get();
   

        @$country_contact_info_supplier_info = DB::table('countries')->where('id',$contact_detail_per_applicant_supplier[0]->$contact_detail_per_applicant_supplier[0]->country_id)
        ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
        ->get();



        $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
        ->where('contact_type','Supplier')
        ->get();
   
        $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();
   
   
        $applications_status = DB::table('applications')
               ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
               ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
               ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
               ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
               ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
               ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
               ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
               ->select('countries.*','countries.id as countryid','applications.*',
                'invoices.*','contacts.*', 'invoices.amount',
                 'medicines.product_name',
                'medicinal_products.product_trade_name',
                'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
                'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
                'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
                'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
                'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
                'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
                'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
                'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
                'contacts.last_name as cont_last_name','contacts.id as cont_id', 
                'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
                'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email', 'contacts.fax as cont_fax', 
                'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
                'contacts.position as cont_position','contacts.city as cont_city'
                 )
               ->where('applications.application_id',$request->application_id)
               ->orderBy('invoices.invoice_number','ASC')
               ->get();
   
               if(empty($applications_status[0]->cont_id) )
               {
   $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
                           ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                           ->get();
   
               }
               else if(!empty($applications_status[0]->cont_id) )
               {
   
              $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
                  //->Orwhere('id',68)
                  ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                  ->get();
   
   
               }

   
   
   $agent_contact_info = DB::table('applications')
                         ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                         ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                         ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                         ->select('agents.*','contacts.*','agents.trade_name as business_name',
                         'agents.state as agent_state','agents.address_line_one as agent_address_line_one',
                         'agents.address_line_two as  agent_address_line_two','agents.city as agent_city',
                         'agents.postal_code as agent_postal_code','agents.telephone as agent_telephone',
                         'agents.webiste_url as agent_webiste_url','agents.email as agent_email',
                         'agents.country_code as agent_country_code','contacts.id as cont_id',
                         'agents.id as agent_id','contacts.first_name as cont_first',
                         'contacts.middle_name as cont_middle','contacts.last_name as cont_last',
                         'contacts.position as cont_position','contacts.city as cont_city',
                         'contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two',
                         'contacts.postal_code as cont_postal_code',
                         'contacts.telephone as cont_tele','contacts.fax as cont_fax',
                         'contacts.email as cont_email')
                          ->where('contact_type','Agent')
                          ->where('agents.application_id',$request->application_id)
                          ->get();
   
   
   
    $agent_contact_County_info = DB::table('countries')->where('id','68')
                            ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                            ->get();
   
   
   //Retriving  value form the medicical product Details
   
   $product_details = DB::table('medicinal_products')
   ->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
   ->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
   ->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
   ->select(
     'medicinal_products.id as _id',
    'medicinal_products.medicine_id as medicine_id',
    'medicinal_products.product_trade_name as product_trade_namme',
    'medicines.product_name as medicine_product_name',
    'route_administrations.name as route_name',
    'dosage_forms.name as dosage_name',
    'medicines.id as medicinal_id',
    'route_administrations.id as route_id',
    'dosage_forms.id as dosage_id',
    'medicines.*',
    'medicinal_products.*',
    'route_administrations.*',
                'dosage_forms.*'
     )
   ->where('medicinal_products.application_id',$request->application_id)
   ->get();
   
   
   
   $product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();
   
   $receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();
   
   $product_enlm_list = DB::table('applications')
                               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                               ->where('applications.application_id',$request->application_id)
                               ->select('applications.*','medicines.*')
                               ->get();
   
   
   $product_manufacturers_info = DB::table('manufacturers')
                                        ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                        ->where('manufacturers.application_id',$request->application_id)
                                        ->select('manufacturers.id as mmid','manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                        'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                        ->get();
   
    $api_manufacturers_info = DB::table('api_manufacturers')
                                  ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                                 ->where('api_manufacturers.application_id',$request->application_id)
                                 ->select(   'api_manufacturers.*','countries.*',
                                             'api_manufacturers.manufacturer_name as api_name',
                                             'api_manufacturers.id as api_id')
                                 ->get();
   
    @$decleration_info = DB::table('declarations')
    ->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
                ->where('declarations.application_id',$request->application_id)
                                ->select('declarations.*','documents.*','declarations.name as decname')
                                ->get();
   
   
     @$docu_id = DB::table('documents')
      ->where('documents.id',$decleration_info[0]->document_attachment_id)
      ->select('documents.*')
      ->get();






       

//application_reception.application_reception_re_registration    $view_application_re_registration


         return view('application_reception.application_reception_re_registration',[
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'company_suppliers_template' =>  $company_suppliers_template,
            'country_contact_info_supplier_info' => $country_contact_info_supplier_info ,
            'agents'=>$agents,
            'agents_template'=>$agents_template,
            'medicines'=>$product_details,
            'application_details'=>$application_details,
            'app_old_id'=>$id,
            'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
            'decleration_info'=> $decleration_info,
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_detailss,
            'application_check_wizard'=>$application_check_wizard,
            'explode' =>  $explode,
            'company_supplier_per_applicant'=> $company_supplier_per_applicant,
            'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
            'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
            'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
            'applications_status'  => $applications_status,
            'country_contact_info' => $country_contact_info,
            'agent_contact_County_info'=>$agent_contact_County_info,
            'agent_contact_info' => $agent_contact_info,
            'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
            'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
            'med_id'=> (@$product_details[0]->medicine_id =='')?0:1,
            'product_details_general'=> $product_details,
            'product_composition_info'=>$product_composition_info,
            'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
            'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
            'product_manufacturers_info'=>$product_manufacturers_info,
            'company_suppliers_template' =>$company_suppliers_template,
            'agents_template'=>$agents_template ,
            'api_manufacturers_info' => $api_manufacturers_info,
            'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
            'docu_id' =>  @$docu_id,
        ]);

      }




    public function Request_for_all()
    {
        $countries = Country::all()->sortBy('country_name');;
        $fast_track_applications =  fast_track_application::all()->sortBy('name');;
        $dosage_forms  = DosageForms::all()->sortBy('name');;
        $apis  = apis::all()->sortBy('api_name');;
        //$route_administrations = route_administrations::all();
        $route_administrations = route_administrations::all()->sortBy('name');

        $agents = agents::all()->sortBy('trade_name');

        $agents_template = agents_template::all()->sortBy('trade_name');

        $company_suppliers = company_suppliers::all()->sortBy('trade_name');

        $company_suppliers_template = DB::select('select * from  company_supplier_template  where (is_Approved_By_NMFA = 1 )  order by trade_name  ASC');


        $product_details = DB::select('select * from  medicines where (is_enlm =1 and is_approved=1)  order by product_name ASC');
       // $product_details =  product_details::all()->sortBy('product_name');
      
 
       
       
    

             return view('application_reception.application_reception',[
                'countries' => $countries,
                'fast_track_applications' =>$fast_track_applications,
                'dosage_forms'=>  $dosage_forms,
                'apis'=>  $apis,
                'route_administrations'=>$route_administrations ,
                'company_suppliers'=> $company_suppliers,
                'company_suppliers_template' =>  $company_suppliers_template,
                'agents'=>$agents,
                'agents_template'=>$agents_template,
                'medicines'=>$product_details,
            ]);

          }



          public function company_supplier(Request $request)
          {
            try{

                $trade_name=$request->trade_name;

                $check_trade_name = company_suppliers_template::where('trade_name',$trade_name)
                ->join('countries','countries.id','company_supplier_template.country_id')

                ->get();

                return response()->json([

                        "id" => $check_trade_name[0]->id,
                        "trade_name" => $check_trade_name[0]->trade_name,
                        "country_id" => $check_trade_name[0]->country_id,
                        "city" => $check_trade_name[0]->city,
                        "state" => $check_trade_name[0]->state,
                        "address_line_one" => $check_trade_name[0]->address_line_one,
                        "address_line_two" => $check_trade_name[0]->address_line_two,
                        "country_code" => $check_trade_name[0]->country_code,
                        "telephone" => $check_trade_name[0]->telephone,
                        "email" => $check_trade_name[0]->email,
                        "postal_code" => $check_trade_name[0]->postal_code,
                        "webiste_url" => $check_trade_name[0]->webiste_url,
                        "International_dialing" => $check_trade_name[0]->International_dialing,
                        //"contacts_id" => $check_trade_name[0]->contacts_id,
                        "created_at" => $check_trade_name[0]->created_at,
                        "updated_at" => $check_trade_name[0]->updated_at,
                        "country_name" => $check_trade_name[0]->country_name,
                        "is_active" => $check_trade_name[0]->is_active

                                       ]);
                             }

            catch(Exception $e)
               {
                return response()->json(['trade'=>$e,'item'=>'error'.$e]);
                }


        }


        public function get_tele_code(Request $request)
        {

            $countries = new Country;
            $Check_tele = DB::select('select * from  countries  where id = ?', [$request['tele']]);
           foreach($Check_tele as $tele)
           {
             $this->tele = $tele->country_name;
             $this->code = $tele->International_dialing;
           }

        return response()->json(['Code'=> $this->code ]);

        }






        public function agent_info(Request $request)
        {
               try{

              $trade_name=$request->trade_name;

              $check_trade_name = agents_template::where('trade_name',$trade_name)
              ->join('countries','countries.id','local_agent_template.country_id')

              ->get();

              return response()->json([

                      "id" => $check_trade_name[0]->id,
                      "trade_name" => $check_trade_name[0]->trade_name,
                      "country_id" => $check_trade_name[0]->country_id,
                      "city" => $check_trade_name[0]->city,
                      "state" => $check_trade_name[0]->state,
                      "address_line_one" => $check_trade_name[0]->address_line_one,
                      "address_line_two" => $check_trade_name[0]->address_line_two,
                      "country_code" => $check_trade_name[0]->country_code,
                      "telephone" => $check_trade_name[0]->telephone,
                      "email" => $check_trade_name[0]->email,
                      "postal_code" => $check_trade_name[0]->postal_code,
                      "webiste_url" => $check_trade_name[0]->webiste_url,
                      "International_dialing" => $check_trade_name[0]->International_dialing,
                      //"contacts_id" => $check_trade_name[0]->contacts_id,
                      "created_at" => $check_trade_name[0]->created_at,
                      "updated_at" => $check_trade_name[0]->updated_at,
                      "country_name" => $check_trade_name[0]->country_name,
                      "is_active" => $check_trade_name[0]->is_active

                                     ]);
                           }

          catch(Exception $e)
             {
              return response()->json(['trade'=>$e,'item'=>'error'.$e]);
              }


      }





        public function company_supplier_save(Request $request){
            //dd($request->session()->all());
          //dd($new_application_id);
         
    
     try
     {
       $contact = new contacts;
       $contact->first_name = $request->first_name;
       $contact->middle_name = $request->middle_name;
       $contact->last_name = $request->last_name;
       $contact->country_id = $request->country_id_contact;
       $contact->position = $request->position;
       $contact->city = $request->city_contact;
       $contact->address_line_one = $request->address_line_one_contact;
       $contact->address_line_two = $request->address_line_two_contact;
       $contact->postal_code = $request->postal_code;
       $contact->telephone = $request->telephone_contact;
       $contact->contact_type = $request->contact_type;
      // $contact->webiste_url = $request->webiste_url_contact;
       $contact->email= $request->email_contact;
       $contact->fax= $request->contact_Fax;;
       $contact->user_id = $request->user_id;
       $contact->application_id =  $request->application_id;

          $contact_check = $contact->save();
//For New Supplier
$Get_Contact_Supplier_ID= contacts::where('application_id',$request->application_id)
->where('contact_type', 'Supplier')
->get();

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;

$current_progress = $Application_ID[0]->hold_progress_wizard.",1";

$update_applications_details_hold_wizard = DB::table('applications')->where('application_id', $request->application_id)->update(['hold_progress_wizard' =>$current_progress,]);

$company_suppliers = new company_suppliers;

if( $request->trade_name == 'Other')
{
 $Other_trade =  DB::table('company_supplier_template')->insert(['user_id'=>auth()->user()->id,'trade_name'=>$request->trade_name_other,'country_id'=>$request->country_id, 'state'=>$request->state,'is_Approved_By_NMFA'=>0,'country_code'=>$request->country_code,'is_Registerd_company' => 'NEW','created_at'=>now(),'updated_at'=>now()]);
 $Trade_ID = company_suppliers_template::where('trade_name',$request->trade_name_other)->get();
$Trade_Name = $request->trade_name_other;
}
else
{
  $Trade_Name = $request->trade_name;
}


// $company_suppliers->trade_name = $request->trade_name;
$company_suppliers->trade_name = $Trade_Name;
$company_suppliers->country_id = $request->country_id;
$company_suppliers->city = $request->city;
$company_suppliers->state = $request->state;
$company_suppliers->address_line_one = $request->address_line_one;
$company_suppliers->address_line_two = $request->address_line_two;
$company_suppliers->country_code = $request->country_code;
$company_suppliers->telephone = $request->telephone;
$company_suppliers->contacts_id = $Get_Contact_Supplier_ID[0]->id;
$company_suppliers->webiste_url = $request->webiste_url;
$company_suppliers->email= $request->email;
$company_suppliers->postal_code= $request->postal_code;
$company_suppliers->user_id = $request->user_id;
$company_suppliers->application_id = $request->application_id;
$company_suppliers->postal_code =  $request->postal_code;

$company_suppliers_check =$company_suppliers->save();


$Supplier_ID  = company_suppliers::where('application_id',$request->application_id)->get();
$Supplier_ID[0]->id;

$update_applications_details = DB::table('applications')->where('application_id', $request->application_id)->update(['company_supplier_id' =>$Supplier_ID[0]->id,]);

$Supplier_ID  = company_suppliers::where('application_id',$request->application_id)->get();
$Supplier_ID[0]->id;


if(  ($contact_check == true)   &&  ( $company_suppliers_check ==true)  )
{
  if( $request->trade_name == 'Other') { $company_supplier_template_id=$Trade_ID[0]->id;  } else {$company_supplier_template_id=0;}

    return response()->json(['company_supplier_template_id'=>$company_supplier_template_id,'supplyInfo'=>$contact->save(),'Contact_ID'=>$Get_Contact_Supplier_ID[0]->id,'Supplier_ID'=>$Supplier_ID[0]->id]);

}

    }
     catch(Exception $e)
        {

          DB::table('company_suppliers')->where('application_id', '=',$request->application_id)->delete();
          DB::table('contacts')->where('application_id', '=',$request->application_id)->where('contact_type', '=','Supplier')->delete();
    
return response()->json(['supplyInfo'=>$e,'item'=>'error'.$e]);

         }


 }






 public function company_supplier_update(Request $request){

try{


  //dd($request->all());


$contact = new contacts;
$contact->id = $request->contact_id;



$affected_Contacts = DB::table('contacts')
              ->where('id', $request->contact_id)
              ->update(
                  [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'country_id' => $request->country_id_contact,
                    'position' => $request->position,
                    'city' => $request->city_contact,
                    'telephone' => $request->telephone_contact,
                    'address_line_one' => $request->address_line_one_contact,
                    'address_line_two' => $request->address_line_two_contact,
                    'postal_code' => $request->postal_code,
                    'email'=> $request->email_contact,
                    'contact_type'=>$request->contact_type,
                    'application_id' => $request->application_id,
                   'fax' => $request->contact_Fax
                  ]
                    );



if( $request->trade_name == 'Other')
{

  if($request->company_supplier_template_id !=0)
  {
$affected_company_supplier_template = DB::table('company_supplier_template')
->where('id', $request->company_supplier_template_id)
->update(
  [
  'trade_name' => $request->trade_name_other,
  'country_id' => $request->country_id,
  'state' => $request->state,
  'country_code' => $request->country_code
  ]
);



$affected_Company_supplier = DB::table('company_suppliers')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'trade_name' => $request->company_supplier_template_id,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'state' => $request->state,
                'address_line_one' => $request->address_line_one,
                'address_line_two' => $request->address_line_two,
                'telephone' =>    $request->telephone,
                'contacts_id' =>  $request->contact_id,
                'webiste_url' =>  $request->webiste_url,
                'email'=>         $request->email,
                'application_id' => $request->application_id,
                'country_code' =>   $request->country_code
                //'institutional_email' =>$company_suppliers->institutional_email
                ]
              );

   // dd($affected_Company_supplier);


}
else
{
  return response()->json([
    'supplyInfo_updated'=>-1,
    'Contact_ID'=>$request->contact_id,
    'Supplier_ID'=>$request->supplier_id,
    'company_supplier_template_id' => $request->company_supplier_template_id,
     ]);


}
}
else if( $request->trade_name != 'Other')
{
  $affected_company_supplier_template=0;
  $affected_Company_supplier = DB::table('company_suppliers')
  ->where('application_id', $request->application_id)
  ->update(
    [
    'trade_name' => $request->trade_name,
    'country_id' => $request->country_id,
    'city' => $request->city,
    'state' => $request->state,
    'address_line_one' => $request->address_line_one,
    'address_line_two' => $request->address_line_two,
    'telephone' =>    $request->telephone,
    'contacts_id' =>  $request->contact_id,
    'webiste_url' =>  $request->webiste_url,
    'email'=>         $request->email,
    'application_id' => $request->application_id,
    'country_code' =>   $request->country_code
    //'institutional_email' =>$company_suppliers->institutional_email
    ]
  );

// dd($affected_Company_supplier);
}







if($affected_Contacts == 1 ||   $affected_Company_supplier ==1  ||  $affected_company_supplier_template==1   )
  {
    return response()->json([
    'supplyInfo_updated'=>1,
    'Contact_ID'=>$request->contact_id,
    'Supplier_ID'=>$request->supplier_id,
    'company_supplier_template_id' => $request->company_supplier_template_id, ]);
  }


  else
  {
    return response()->json([
        'supplyInfo_updated'=>0,
        'Contact_ID'=>$request->contact_id,
        'Supplier_ID'=>$request->supplier_id,
        'company_supplier_template_id' => $request->company_supplier_template_id,
         ]);
  }

}

catch(Exception $e)
{


 return response()->json(['supplyInfo'=>$e,'item'=>'error'.$e]);

 }


}






 public function  agent_save(Request $request){

try
{

$contact = new contacts;
$contact->first_name = $request->first_name;
$contact->middle_name = $request->middle_name;
$contact->last_name = $request->last_name;
$contact->country_id = $request->country_id_contact;
$contact->position = $request->position;
$contact->city = $request->city_contact;
$contact->address_line_one = $request->address_line_one_contact;
$contact->address_line_two = $request->address_line_two_contact;
$contact->postal_code = $request->postal_code;
$contact->telephone = $request->telephone_contact;
$contact->fax =  $request->fax;
$contact->contact_type = $request->contact_type;

$contact->email= $request->email_contact;
$contact->user_id=$request->user_id;
$contact->application_id =  $request->application_id;

$contact_age_info = $contact->save();


//For New Agent
$Get_Contact_Agent_ID= contacts::where('email',$request->email_contact)->where('contact_type', 'Agent')->get();


$agent = new agents;
$agent->trade_name = $request->trade_name;
$agent->country_id = $request->country_id;
$agent->city = $request->city;
$agent->state = $request->state;
$agent->address_line_one = $request->address_line_one;
$agent->address_line_two = $request->address_line_two;
$agent->country_code = $request->country_code;
$agent->telephone = $request->telephone;
$agent->contacts_id = $Get_Contact_Agent_ID[0]->id;
$agent->webiste_url = $request->webiste_url;
$agent->email= $request->email;
$agent->postal_code= $request->postal_code;
$agent->user_id=$request->user_id;
$agent->application_id =  $request->application_id;

$agent_info = $agent->save();



$Agent_ID  = agents::where('application_id',$request->application_id)->get();
$Agent_ID[0]->id;

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",2";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

$update_applications_details = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'agent_id' =>$Agent_ID[0]->id,
  ]
);

if($contact_age_info == true &&   $agent_info==true)
{
return response()->json(['AgentInfo'=>$contact->save(),'Contact_ID'=>$Get_Contact_Agent_ID[0]->id,'Agent_ID'=>$Agent_ID[0]->id]);
}

else if( ($contact_age_info == true)  &&   ($agent_info==false)  )
{
  $Get_Contact_Agent_ID= contacts::where('application_id',$request->application_id)
  ->where('contact_type', 'Agent')
  ->get();
      $contact = new contacts;
      $contact = contacts::find($Get_Contact_Agent_ID[0]->id);
      $contact->delete();
}


else if( ($contact_age_info == false)  &&   ($agent_info==true)  )
{
  $Get_Contact_Agent_ID= agents::where('application_id',$request->application_id)
  ->get();
      $agent = new agents;
      $contact = agent::find($Get_Contact_Agent_ID[0]->id);
      $contact->delete();
}


}
catch(Exception $e)
{
  DB::table('agents')->where('application_id', '=',$request->application_id)->delete();
  DB::table('contacts')->where('application_id', '=',$request->application_id)->where('contact_type', '=','Agent')->delete();

return response()->json(['AgentInfo'=>$e,'item'=>'error'.$e]);

 }


}












public function  agent_update(Request $request){

  try
  
{

 // dd( $request->all()) ; 

$contact = new contacts;
$contact->first_name = $request->first_name;
$contact->middle_name = $request->middle_name;
$contact->last_name = $request->last_name;
$contact->country_id = $request->country_id_contact;
$contact->position = $request->position;
$contact->city = $request->city_contact;
$contact->address_line_one = $request->address_line_one_contact;
$contact->address_line_two = $request->address_line_two_contact;
$contact->postal_code = $request->postal_code;
$contact->telephone = $request->telephone_contact;
$contact->contact_type = $request->contact_type;
$contact->fax =  $request->fax;
$contact->email= $request->email_contact;
$contact->id = $request->contact_id;
$contact->application_id = $request->application_id;
//$contact->save();

$affected_Contacts = DB::table('contacts')
              ->where('application_id', $request->application_id)
              ->where('id',$request->contact_id)
              ->update(
                  [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'country_id' => $request->country_id_contact,
                    'position' => $request->position,
                    'city' => $request->city_contact,
                    'address_line_one' => $request->address_line_one_contact,
                    'address_line_two' => $request->address_line_two_contact,
                    'postal_code' => $request->postal_code,
                    'telephone' => $request->telephone_contact,
                    'email'=> $request->email_contact,
                    'contact_type'=>$request->contact_type,
                    'fax' => $request->fax
                  ]
              
                    );


$agent = new agents;
$agent->trade_name = $request->trade_name;
$agent->country_id = $request->country_id;
$agent->city = $request->city;
$agent->state = $request->state;
$agent->address_line_one = $request->address_line_one;
$agent->address_line_two = $request->address_line_two;
$agent->country_code = $request->country_code;
$agent->telephone = $request->telephone;
$agent->contacts_id = $request->contact_id;
$agent->webiste_url = $request->webiste_url;
$agent->email= $request->email;
$agent->postal_code= $request->postal_code;
$agent->id = $request->agent_id;
$agent->application_id = $request->id;
//$agent->save();



$affected_Agent = DB::table('agents')
              ->where('application_id', $request->application_id)
              ->where('id' , $agent->id)
              ->update(
                ['trade_name' => $request->trade_name,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'state' => $request->state,
                'address_line_one' => $request->address_line_one,
                'address_line_two' => $request->address_line_two,
                'country_code' => $request->country_code,
                'telephone' => $request->telephone,
                'contacts_id' => $agent->contacts_id,
                'webiste_url' => $request->webiste_url,
                'email'=>$request->email

                 ]
              );



if($affected_Agent == 1 ||   $affected_Contacts==1) {return response()->json(
       ['AgentupdateInfo'=>'Local Agent and contact person information have been updated successfully.',
       'message'=>true,
       'status'=>1
       ]
    );}
    else
    {

        return response()->json(
            ['AgentupdateInfo'=>'No update is made.',
            'message'=>false

            ]);
    }
}

catch(Exception $e)
{


 return response()->json(['AgentInfo'=>$e,'item'=>'error'.$e]);

 }


}





//Product Details Save Functions



public function product_details_save(Request $request){

    try{
              //  dd($request->all());
      $select_data_dosage_form= DB::table('dosage_forms')->where('id', $request->dosage_form_id)->get();
      @$dosage_form = $select_data_dosage_form[0]->name;

      $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
      @$strength_name = $select_data_strength[0]->product_name;

      $medicinal_details =  medicinal_products::create($request->all());

       $medicinal_details_ID  = medicinal_products::where('application_id',$request->application_id)->get();
       @$medicinal_details_ID[0]->id;

       $Application_ID  = applications::where('application_id',$request->application_id)->get();
       @$Application_ID[0]->hold_progress_wizard;

       @$current_progress = $Application_ID[0]->hold_progress_wizard.",3";

       $update_applications_details = DB::table('applications')
       ->where('application_id', $request->application_id)
       ->update(
         [
         'medical_product_id' =>$medicinal_details_ID[0]->id,
         'hold_progress_wizard' => $current_progress,
         ]
       );

       $productdetial_id  = medicinal_products::where('product_trade_name',$request->product_trade_name)->get();

       $dosage_form = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->join('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
                            ->where('medicinal_products.application_id','=',$request->application_id)
                            ->select('medicines.*','dosage_forms.*','medicinal_products.*','dosage_forms.name as dosage_form')
                            ->first();


    
        
        return response()->json(['Message'=>true,
        'productdetial_id'=>$productdetial_id[0]->id,
        'strength_name'=>$strength_name,
        'product_name' => $dosage_form->product_name,
        'dosage_form' => $dosage_form->dosage_form,

        ]);


    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}





public function product_details_update_other(Request $request)
{
  try{

    
    
    $select_id_from_medicines = medicines::where('product_name',$request->generic_approved_name_other)->first();

    if(@$select_id_from_medicines->id == null)
    {
     

      $Medicine_table =  DB::table('medicines')->insert(['product_name' => $request->generic_approved_name_other,
       'medicine_id' => $request->generic_approved_name_other, 'product_description' => '---', 'is_enlm' => 0, 'is_approved' => 0, 'created_at' => now() ,'updated_at'=> now() ]);
      
     $select_id_from_medicines = medicines::where('product_name',$request->generic_approved_name_other)->first();

     //dd($select_id_from_medicines);

       $medicinal_products = DB::table('medicinal_products')
            ->where('application_id', $request->application_id)
            ->update(
              [
              'user_id' => $request->user_id,
              'product_trade_name'=> $request->product_trade_name,
              'medicine_id' => $select_id_from_medicines->id,
              'dosage_form_id' => $request->dosage_form_id,
              'route_administration_id' => $request->route_administration_id,
              'description' => $request->description,
              'strength_amount_strength_unit' => $request->strength_amount_strength_unit,
              'pharmaco_therapeutic_classification' => $request->pharmaco_therapeutic_classification,
              'storage_condition' => $request->storage_condition,
              'shelf_life_amount' => $request->shelf_life_amount,
              'shelf_life_unit' => $request->shelf_life_unit,
              'proposed_shelf_life_amount' => $request->proposed_shelf_life_amount,
              'proposed_shelf_life_unit' => $request->proposed_shelf_life_unit,
              'proposed_shelf_life_after_reconstitution_amount' => $request->proposed_shelf_life_after_reconstitution_amount ,
              'proposed_shelf_life_after_reconstitution_unit' => $request->proposed_shelf_life_after_reconstitution_unit ,
              'visual_description' => $request->visual_description,
              'commercial_presentation' =>  $request->commercial_presentation ,
              'container' => $request->container,
              'packaging' => $request->packaging,
              'category_use' => $request->category_use,
              'application_id' => $request->application_id,
              

               ]
            );

    }

    else
    {
     $update_medicine_name = DB::table('medicines')
     ->where('id', $request->other_medicine_id)
     ->update(
       [
       'product_name' =>$request->generic_approved_name_other,
       'medicine_id' => ''
       ]
     );


     $medicinal_products = DB::table('medicinal_products')
     ->where('application_id', $request->application_id)
     ->update(
       [
       'user_id' => $request->user_id,
       'product_trade_name'=> $request->product_trade_name,
       'medicine_id' => $request->other_medicine_id,
       'dosage_form_id' => $request->dosage_form_id,
       'route_administration_id' => $request->route_administration_id,
       'description' => $request->description,
       'strength_amount_strength_unit' => $request->strength_amount_strength_unit,
       'pharmaco_therapeutic_classification' => $request->pharmaco_therapeutic_classification,
       'storage_condition' => $request->storage_condition,
       'shelf_life_amount' => $request->shelf_life_amount,
       'shelf_life_unit' => $request->shelf_life_unit,
       'proposed_shelf_life_amount' => $request->proposed_shelf_life_amount,
       'proposed_shelf_life_unit' => $request->proposed_shelf_life_unit,
       'proposed_shelf_life_after_reconstitution_amount' => $request->proposed_shelf_life_after_reconstitution_amount ,
       'proposed_shelf_life_after_reconstitution_unit' => $request->proposed_shelf_life_after_reconstitution_unit ,
       'visual_description' => $request->visual_description,
       'commercial_presentation' =>  $request->commercial_presentation ,
       'container' => $request->container,
       'packaging' => $request->packaging,
       'category_use' => $request->category_use,
       'application_id' => $request->application_id,
       

        ]
     );

    }

 
      $select_data_dosage_form= DB::table('dosage_forms')->where('id', $request->dosage_form_id)->get();
      $dosage_form = $select_data_dosage_form[0]->name;


      $dosage_form = DB::table('medicinal_products')
      ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
      ->join('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
      ->where('medicinal_products.application_id','=',$request->application_id)
      ->select('medicines.*','dosage_forms.*','medicinal_products.*','dosage_forms.name as dosage_form')
      ->first();
      

      $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
      $strength_name = $select_data_strength[0]->product_name;
      $productdetial_id  = medicinal_products::where('application_id',$request->application_id)->get();



      if( $medicinal_products==1)
      {
         return response()->json(
           ['Message'=>true,
          'productdetial_id'=>$productdetial_id[0]->id,
          'strength_name'=>$strength_name,
          'product_name' => $dosage_form->product_name,
          'dosage_form' => $dosage_form->dosage_form,
          'medicine_if_other'=> $select_id_from_medicines->id,
          ]);


      }
      else
      {

     
          return response()->json([
            'Message'=>false,
            'productdetial_id'=>$productdetial_id[0]->id,
            'ProductInfo'=>'No update is made.',
            'medicine_if_other'=> $Medicine_table->id
            ]);
      }



  }

  catch(Exception $e){
      return response()->json(['Message'=>$e,'item'=>'error'.$e]);
      }
      

}

public function product_details_save_other(Request $request )
{

  try{
     $Medicine_table =  DB::table('medicines')->insert(['product_name' => $request->generic_approved_name_other, 'medicine_id' => $request->generic_approved_name_other, 'product_description' => '---', 'is_enlm' => 0, 'is_approved' => 0, 'created_at' => now() ,'updated_at'=> now() ]);
     $Medicine_table = DB::table('medicines')->select('medicines.*')->where('product_name',$request->generic_approved_name_other)
    ->first();

$medicinal_details = new medicinal_products();
$medicinal_details->user_id = $request->user_id;
$medicinal_details->product_trade_name = $request->product_trade_name ;
$medicinal_details->medicine_id= $Medicine_table->id;
$medicinal_details->dosage_form_id= $request->dosage_form_id;
$medicinal_details->route_administration_id = $request->route_administration_id;
$medicinal_details->description = $request->description ;
$medicinal_details->strength_amount_strength_unit = $request->strength_amount_strength_unit;
$medicinal_details->pharmaco_therapeutic_classification = $request->pharmaco_therapeutic_classification;
$medicinal_details->storage_condition = $request->storage_condition;
$medicinal_details->shelf_life_amount = $request->shelf_life_amount;
$medicinal_details->shelf_life_unit = $request->shelf_life_unit;
$medicinal_details->proposed_shelf_life_amount= $request->proposed_shelf_life_amount;
$medicinal_details->proposed_shelf_life_unit= $request->proposed_shelf_life_unit;
$medicinal_details->proposed_shelf_life_after_reconstitution_amount=$request->proposed_shelf_life_after_reconstitution_amount;
$medicinal_details->proposed_shelf_life_after_reconstitution_unit =  $request->proposed_shelf_life_after_reconstitution_unit;
$medicinal_details->visual_description = $request->visual_description;
$medicinal_details->commercial_presentation = $request->commercial_presentation;
$medicinal_details->container = $request->container;
$medicinal_details->packaging = $request->packaging;
$medicinal_details->category_use = $request->category_use;
$medicinal_details->application_id= $request->application_id;
$medicinal_details->save();




    $medicinal_details_ID  = medicinal_products::where('application_id',$request->application_id)->get();
    $medicinal_details_ID[0]->id;

    $Application_ID  = applications::where('application_id',$request->application_id)->get();
    $Application_ID[0]->hold_progress_wizard;

    $current_progress = $Application_ID[0]->hold_progress_wizard.",3";

    $update_applications_details = DB::table('applications')
    ->where('application_id', $request->application_id)
    ->update(
      [
      'medical_product_id' =>$medicinal_details_ID[0]->id,
      'hold_progress_wizard' => $current_progress,
      ]
    );


     $productdetial_id  = medicinal_products::where('product_trade_name',$request->product_trade_name)->get();

     $select_data_dosage_form= DB::table('dosage_forms')->where('id', $request->dosage_form_id)->get();
     $dosage_form = $select_data_dosage_form[0]->name;


     $dosage_form = DB::table('medicinal_products')
     ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
     ->join('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
     ->where('medicinal_products.application_id','=',$request->application_id)
     ->select('medicines.*','dosage_forms.*','medicinal_products.*','dosage_forms.name as dosage_form')
     ->first();

     $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
     $strength_name = $select_data_strength[0]->product_name;

     return response()->json(
      [
    
      'Message'=>true,
      'productdetial_id'=>$productdetial_id[0]->id,
      'medicine_if_other'=> $Medicine_table->id,
            'strength_name'=>$strength_name,
          'product_name' => $dosage_form->product_name,
          'dosage_form' => $dosage_form->dosage_form,

     ]
    );


 }

 catch(Exception $e){
     return response()->json(['Message'=>$e,'item'=>'error'.$e]);
     }
}






public function product_details_update(Request $request){

    try{
      // dd($request->all());
       

       $medicinal_products = DB::table('medicinal_products')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'user_id' => $request->user_id,
                'product_trade_name'=> $request->product_trade_name,
                'medicine_id' => $request->medicine_id,
                'dosage_form_id' => $request->dosage_form_id,
                'route_administration_id' => $request->route_administration_id,
                'description' => $request->description,
                'strength_amount_strength_unit' => $request->strength_amount_strength_unit,
                'pharmaco_therapeutic_classification' => $request->pharmaco_therapeutic_classification,
                'storage_condition' => $request->storage_condition,
                'shelf_life_amount' => $request->shelf_life_amount,
                'shelf_life_unit' => $request->shelf_life_unit,
                'proposed_shelf_life_amount' => $request->proposed_shelf_life_amount,
                'proposed_shelf_life_unit' => $request->proposed_shelf_life_unit,
                'proposed_shelf_life_after_reconstitution_amount' => $request->proposed_shelf_life_after_reconstitution_amount ,
                'proposed_shelf_life_after_reconstitution_unit' => $request->proposed_shelf_life_after_reconstitution_unit ,
                'visual_description' => $request->visual_description,
                'commercial_presentation' =>  $request->commercial_presentation ,
                'container' => $request->container,
                'packaging' => $request->packaging,
                'category_use' => $request->category_use,
                'application_id' => $request->application_id,
                

                 ]
              );
       




        $dosage_form = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->join('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
                            ->where('medicinal_products.application_id','=',$request->application_id)
                            ->select('medicines.*','dosage_forms.*','medicinal_products.*','dosage_forms.name as dosage_form')
                            ->first();

        $select_data_strength= DB::table('medicines') ->where('id', $request->medicine_id)->get();
        $strength_name = $select_data_strength[0]->product_name;



        $productdetial_id  = medicinal_products::where('application_id',$request->application_id)->get();

        if( $medicinal_products==1)
        {

          //  return response()->json(['Message'=>true,'productdetial_id'=>$productdetial_id[0]->id]);
           return response()->json(
             ['Message'=>true,
            'productdetial_id'=>$productdetial_id[0]->id,
            'strength_name'=> $strength_name,
            'product_name' => $dosage_form->product_name,
            'dosage_form' => $dosage_form->dosage_form,
            ]);


        }
        else
        {

       
            return response()->json([
              'Message'=>false,
              'productdetial_id'=>$productdetial_id[0]->id,
              'ProductInfo'=>'No update is made.'
              ]);
        }



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}




public function product_manufacturer_update(Request $request)
{
          try{
       // dd($request->id);
        $manufacturers = manufacturers::find($request->id);
        $manufacturers->user_id = $request->user_id;
        $manufacturers->name=$request->name;
        $manufacturers->country_id=$request->country_id;
        $manufacturers->city= $request->city;
        $manufacturers->state=$request->state;
        $manufacturers->addressline_one=$request->addressline_one;
        $manufacturers->addressline_two=$request->addressline_two;
        $manufacturers->postal_code=$request->postal_code;
        $manufacturers->telephone = $request->telephone;
        $manufacturers->country_code =$request->country_code;
        $manufacturers->activity=$request->activity;
        $manufacturers->block=$request->block;
        $manufacturers->unit=$request->unit;
        $manufacturers->product_id=$request->product_id;
        $manufacturers->application_id=$request->application_id;
        //$manufacturers->id= $request->id;
        $manufacturers->save();

         $manufacturers = manufacturers::where('application_id',$request->application_id) ->orderBy('id', 'ASC')->get();;
         //->where('id',$request->id)
        // ->where('email',$request->email)
         //->join('countries','countries.id','manufacturers.country_id')


         $countries = Country::where('id',$request->country_id)
         // ->where('email',$request->email)
          //->join('countries','countries.id','manufacturers.country_id')
          ->get();

       $return_data = "";$i=1;
         foreach($manufacturers as $manufacturer)

         {


          $countries = Country::where('id',$manufacturer->country_id)->first();

          if($i ==1 ) { $style="color:skyblue";} else { $style="";  }

          $return_data .= "<tr style='$style' title='This is selected information that changes the effect of declaration'  ><td>".$i++."</td>";
          // $return_data .= "<td>".$manufacturer->application_id."</td>";
          $return_data .= "<td>".$manufacturer->name."</td>";
          $return_data .= "<td>".$countries->country_name."</td>";
          $return_data .= "<td>".$manufacturer->postal_code."</td>";
          $return_data .= "<td>".$manufacturer->country_code.$manufacturer->telephone."</td>";
          $return_data .= "<td>".$manufacturer->state."</td>";
          $return_data .= "<td>".$manufacturer->addressline_one."</td>";
          $return_data .= "<td>".$manufacturer->addressline_two."</td>";
          $return_data .= "<td>".$manufacturer->activity."</td>";
          $return_data .= "<td>".$manufacturer->block."</td>";
          $return_data .= "<td>".$manufacturer->unit."</td>";
          $return_data .= "<td>".$manufacturer->city."</td>";

//$return_data .= "<td>".$manufacturer->webiste_url."</td>";
//  $return_data .= "<td>".$manufacturer->country_code."</td>";
//$return_data .= "<td>".$manufacturer->email."</td>";
      
       $return_data.="<td>
       <button  class='btn btn-warning btn-sm editmanufacturer' data-di='".$manufacturer->id."'   value='".$manufacturer->id."'   ><i class='fas fa-pencil-alt'></i> </button>
       <br/><br/>
               <br/> <br/>
               
<button class='btn btn-danger btn-sm' value='".$manufacturer->id."'  onclick='Delete_Manufacturer($manufacturer->id)' ><i class='fas fa-trash'></i> </button>

               </td></tr> ";



         }


         //return response()->json(['renderd_manufacturer_table'=>$return_data,'Message'=>true,'Manufacturer_id'=>$manufacturer->id]);


         $manufacturers_is_current_address = manufacturers::where('application_id',$request->application_id)->orderBy('created_at', 'ASC')->first();


         return response()->json([

          'renderd_manufacturer_table'=>$return_data,
          'Message'=>true,
          'Manufacturer_id'=> $manufacturers[0]->id,
          'name_manufactures'=> $manufacturers_is_current_address->name,
          'addressline_one' => $manufacturers_is_current_address->addressline_one,
          'addressline_two' => $manufacturers_is_current_address->addressline_two,

          ]);


    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}

public function delete_manufacturer_elements(Request $request)
{

  $id = $request->id;
  $manufacturerss=DB::table('manufacturers')->where('id', '=', $request->id)->delete();

  try
  {
  $manufacturers = DB::table('manufacturers')
            ->select('manufacturers.*')
            ->where('application_id', '=', $request->application_id)
            ->get();


            foreach($manufacturers as $manufacturer){
              if($manufacturer->id != '' ){
        @$countries = Country::where('id', $manufacturers[0]->country_id)->get();$country = $countries[0]->country_name;}
             else
             {  $country = ''; $return_data ="";
             }

              }


    $return_data = "";$i=1;
  foreach($manufacturers as $manufacturer)
  {

      if($manufacturer->id != '' )
      {
        
 $countries = Country::where('id',$manufacturer->country_id)->first();
 if($i ==1 ) { $style="color:skyblue";} else { $style="";  }

 $return_data .= "<tr style='$style' title='This is selected information that changes the effect of declaration' ><td>".$i++."</td>";
          // $return_data .= "<td>".$manufacturer->application_id."</td>";
          $return_data .= "<td>".$manufacturer->name."</td>";
          $return_data .= "<td>".$countries->country_name."</td>";
          $return_data .= "<td>".$manufacturer->postal_code."</td>";
          $return_data .= "<td>".$manufacturer->country_code.$manufacturer->telephone."</td>";
          $return_data .= "<td>".$manufacturer->state."</td>";
          $return_data .= "<td>".$manufacturer->addressline_one."</td>";
          $return_data .= "<td>".$manufacturer->addressline_two."</td>";
          $return_data .= "<td>".$manufacturer->activity."</td>";
          $return_data .= "<td>".$manufacturer->block."</td>";
          $return_data .= "<td>".$manufacturer->unit."</td>";
          $return_data .= "<td>".$manufacturer->city."</td>";
 $return_data.="
   <td>
   <button  class='btn btn-warning btn-sm editmanufacturer' data-di='".$manufacturer->id."'   value='".$manufacturer->id."'   ><i class='fas fa-pencil-alt'></i> </button>
   <br/><br/>
  
  <button class='btn btn-danger btn-sm' onclick='Delete_Manufacturer($manufacturer->id)' ><i class='fas fa-trash'></i> </button>
  </td>

  </tr> ";

      }
      else
        {
        $return_data ="";
        }

}

$manufacturers_is_current_address = manufacturers::where('application_id',$request->application_id)->orderBy('created_at', 'ASC')->first();

return response()->json([
                        'renderd_product_manufacturer_table'=>$return_data,
                         'Message'=>true,
                        'Manufacturer_id'=>$manufacturer->id,
                        'name_manufactures'=> $manufacturers_is_current_address->name,
                        'addressline_one' => $manufacturers_is_current_address->addressline_one,
                        'addressline_two' => $manufacturers_is_current_address->addressline_two,

                        
                        ]);

  }

            catch(Exception $e){
              $return_data ="";
            return  response()->json(['Message'=>false,'renderd_product_manufacturer_table'=>$return_data,'Manufacturer_id'=>$request->id]);



              }


}



////////////////////////////////////////////////////////////
public function delete_manufacturer_elements_api(Request $request)
{

  $id = $request->id;
  $manufacturerss_api=DB::table('api_manufacturers')->where('id', '=', $request->id)->delete();

  try
  {
  $manufacturers_api = DB::table('api_manufacturers')
            ->select('*')
            ->where('application_id', '=', $request->application_id)
            ->orderby('id','ASC')
            ->get();


      

              //dd($manufacturers_api); 

   $return_data = "";$i=1;
  foreach($manufacturers_api as $manufacturer_api)
  {

      if($manufacturer_api->id != '' )
      {
        @$countries = Country::where('id', $manufacturer_api->country_id)->first();

        $return_data .= "<tr><td>".$i++."</td>";
        // $return_data .= "<td>".$manufacturer_api->application_id."</td>";
        $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
        $return_data .= "<td>".$manufacturer_api->api_name."</td>";
        $return_data .= "<td>".$countries->country_name."</td>";
        $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
        $return_data .= "<td>".$request->manu_api_response_tele.$manufacturer_api->telephone."</td>";
        $return_data .= "<td>".$manufacturer_api->city."</td>";
        $return_data .= "<td>".$manufacturer_api->state."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";
        //$return_data .= "<td>".$manufacturer_api->webiste_url."</td>";
        //$return_data .= "<td>".$manufacturer_api->email."</td>";
        $return_data .= "<td>".$manufacturer_api->unit."</td>";
        $return_data .= "<td>".$manufacturer_api->block."</td>";

        $return_data.="<td>
        <span class='btn btn-warning btn-sm editmanufacturer_api' data-di='".$manufacturer_api->id."' ><i class='fas fa-pencil-alt'></i>  </span>
        <br/> <br/>
        <span class='btn btn-danger btn-sm'  onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i> </span>
        </td></tr> ";
      }
      else
        {

        $return_data ="";

        }

}

return response()->json(
[

'renderd_manufacturer_api_table'=>$return_data,
'Message'=>true,


]);

  }

            catch(Exception $e){

            return  response()->json(['Message'=>false,'item'=>'error'.$e]);



              }


}


///////////////////////////////////////////////////////////////

public function product_manufacturer_save(Request $request)
{
          try{

            $product_manufacturer=  manufacturers::create($request->all());
            // dd($medicinal_details);
             $manufacturers = manufacturers::where('application_id',$request->application_id)->orderBy('id', 'ASC')


            // ->where('email',$request->email)
             //->join('countries','countries.id','manufacturers.country_id')
             ->get();

      

          $Application_ID  = applications::where('application_id',$request->application_id)->get();
          $Application_ID[0]->hold_progress_wizard;
          $current_progress = $Application_ID[0]->hold_progress_wizard.",5";
          $update_applications_details_hold_wizard = DB::table('applications')
          ->where('application_id', $request->application_id)
          ->update(
            [
            'hold_progress_wizard' => $current_progress,
            ]
          );


       $return_data = "";$i=1;
         foreach($manufacturers as $manufacturer)

         {

          $countries = Country::where('id',$manufacturer->country_id)->first();
          if($i ==1 ) { $style="color:skyblue";} else { $style="";  }

          $return_data .= "<tr style='$style' title='This is selected information that changes the effect of declaration' ><td>".$i++."</td>";
        // $return_data .= "<td>".$manufacturer->application_id."</td>";
        $return_data .= "<td>".$manufacturer->name."</td>";
        $return_data .= "<td>".$countries->country_name."</td>";
        $return_data .= "<td>".$manufacturer->postal_code."</td>";
        $return_data .= "<td>".$manufacturer->country_code.$manufacturer->telephone."</td>";
        $return_data .= "<td>".$manufacturer->state."</td>";
        $return_data .= "<td>".$manufacturer->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer->addressline_two."</td>";
        $return_data .= "<td>".$manufacturer->activity."</td>";
        $return_data .= "<td>".$manufacturer->block."</td>";
        $return_data .= "<td>".$manufacturer->unit."</td>";
        $return_data .= "<td>".$manufacturer->city."</td>";
        //  $return_data .= "<td>".$manufacturer->country_code."</td>";
        //$return_data .= "<td>".$manufacturer->webiste_url."</td>";
        // $return_data .= "<td>".$manufacturer->email."</td>";

         $return_data.="<td>
         <button  class='btn btn-warning btn-sm editmanufacturer' data-di='".$manufacturer->id."'   value='".$manufacturer->id."'   ><i class='fas fa-pencil-alt'></i> </button>
          <br/><br/>
          <button class='btn btn-danger btn-sm' value='".$manufacturer->id."'  onclick='Delete_Manufacturer($manufacturer->id)' ><i class='fas fa-trash'></i> </button>
          </td></tr> ";

         }

  $manufacturers_is_current_address = manufacturers::where('application_id',$request->application_id)->orderBy('created_at', 'ASC')->first();


         return response()->json([

          'renderd_manufacturer_table'=>$return_data,
          'Message'=>true,
          'Manufacturer_id'=> $manufacturers[0]->id,
          'name_manufactures'=> $manufacturers_is_current_address->name,
          'addressline_one' => $manufacturers_is_current_address->addressline_one,
          'addressline_two' => $manufacturers_is_current_address->addressline_two,

          ]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}


public function composition_retreive(Request $request)
{

  $product_compositions = product_composition::where('id',$request->id)->first();
  return response()->json([
  'Message'=>true,
  'product_composition'=>$product_compositions->composition_name,
  'quantity'=>$product_compositions->quantity,
  'reason'=>$product_compositions->reason,
  'reference_standard'=>$product_compositions->reference_standard,
  'type'=>$product_compositions->type,
  
  ]);


}


public function manufacturer_retreive_api(Request $request)
{
// dd( $request->all());
  $product_manufacture_api = api_manufacturers::where('id',$request->id)->first();

  $countries = Country::where('id',$product_manufacture_api->country_id)
  // ->where('email',$request->email)
   //->join('countries','countries.id','manufacturers.country_id')
   ->first();


  return response()->json([
  'Message'=>true,
  'name'=>$product_manufacture_api->manufacturer_name,
  'country_id'=>$product_manufacture_api->country_id,
  'city'=>$product_manufacture_api->city,
  'state'=>$product_manufacture_api->state,
  'addressline_one'=>$product_manufacture_api->addressline_one,
  'addressline_two'=>$product_manufacture_api->addressline_two,
  'postal_code'=>$product_manufacture_api->postal_code,
  'telephone'=>$product_manufacture_api->telephone,
  'country_code' => $countries->International_dialing,
  'api_name' =>$product_manufacture_api->api_name,
  'unit' =>$product_manufacture_api->unit,
  'block' =>$product_manufacture_api->block,


]);


}

public function manufacturer_retreive(Request $request)
{

  $product_manufacture = manufacturers::where('id',$request->id)->first();

  //dd($product_manufacture);
  return response()->json([
  'Message'=>true,
  'name'=>$product_manufacture->name,
  'country_id'=>$product_manufacture->country_id,
  'state'=>$product_manufacture->state,
  'addressline_one'=>$product_manufacture->addressline_one,
  'addressline_two'=>$product_manufacture->addressline_two,
  'postal_code'=>$product_manufacture->postal_code,
  'telephone'=>$product_manufacture->telephone,
  'country_code'=>$product_manufacture->country_code,
  'activity'=>$product_manufacture->activity,
  'block'=>$product_manufacture->block,
  'unit'=>$product_manufacture->unit,
  'city'=>$product_manufacture->city,
  
  ]);


}




public function product_composition_save(Request $request)
{
          try{
        //dd($request->all());

        $product_compositions =  product_composition::create($request->all());
        // dd($medicinal_details);
         $product_compositions = product_composition::where('application_id',$request->application_id)
         //->where('medical_product_id',$request->medical_product_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
         ->get();



$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",4";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);


       $return_data = "";$i=1;
         foreach($product_compositions as $product_composition)

         {

        // $return_data .= "<tr><td>".$product_composition->id."</td>";
        $return_data .= "<tr><td>".$i++."</td>";
        // $return_data .= "<td>".$product_composition->application_id."</td>";
        $return_data .= "<td>".$product_composition->type."</td>";
        $return_data .= "<td>".$product_composition->composition_name."</td>";
        $return_data .= "<td>".$product_composition->quantity."</td>";
        $return_data .= "<td>".$product_composition->reason."</td>";
        $return_data .= "<td>".$product_composition->reference_standard."</td>";
        $return_data.="<td>

        <button
        class='btn btn-warning btn-sm editcomposition' value='".$product_composition->application_id."'
      data-di= '".$product_composition->id."'  id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
        </button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>


         </td></tr> ";


         }


         return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>true,'Compostion_id'=>$product_composition->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}




 public function delete_composition_elements(Request $request)
{
 $id = $request->id;
 $message=0;
 //dd($id);
 //$product_composition = product_composition::find($id)->delete();

 $product_composition=DB::table('product_compositions')->where('id', '=', $request->id)->delete();

 $product_compositions = product_composition::where('application_id',$request->application_id)
 //->where('medical_product_id',$request->medical_product_id)
// ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
 ->get();


        $return_data = "";
        $i = 1;
 foreach($product_compositions as $product_composition)

 {
  if($product_composition->id !='')
  {
 // $return_data .= "<tr><td>".$product_composition->id."</td>";
  // $return_data .= "<td>".$product_composition->application_id."</td>";
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .= "<td>".$product_composition->type."</td>";
 $return_data .= "<td>".$product_composition->composition_name."</td>";
 $return_data .= "<td>".$product_composition->quantity."</td>";
 $return_data .= "<td>".$product_composition->reason."</td>";
 $return_data .= "<td>".$product_composition->reference_standard."</td>";


$return_data.="<td>
<button
class='btn btn-warning btn-sm editcomposition' value='".$product_composition->application_id."'
data-di= '".$product_composition->id."'  id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
</button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>

 </td></tr> ";
 $message=1;
  }
  else
  {
    $message=0;
    return $return_data='';
  }
 }


 return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>$message,'Compostion_id'=>@$product_composition->id]);



}


public function product_composition_update(Request $request)
{
          try{
        ///dd($request->name);

        $product_compositions = product_composition::find($request->id);
        $product_compositions->user_id = $request->user_id;


        $product_compositions->composition_name =$request->composition_name;
        $product_compositions->reason =  $request->reason;
        $product_compositions->reference_standard = $request->reference_standard;
        $product_compositions->type = $request->type;
        $product_compositions->medical_product_id = $request->medical_product_id;
        $product_compositions->quantity = $request->quantity;
        $product_compositions->composition_name = $request->composition_name;
        $product_compositions->application_id = $request->application_id ;
        $product_compositions->id = $request->id;
        $product_compositions->save();

         $product_compositions = product_composition::where('application_id',$request->application_id)
         //->where('medical_product_id',$request->medical_product_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
         ->get();

       $return_data = "";$i=1;
         foreach($product_compositions as $product_composition)

         {

          $return_data .= "<tr><td>".$i++."</td>";
          $return_data .= "<td>".$product_composition->type."</td>";
          $return_data .= "<td>".$product_composition->composition_name."</td>";
          $return_data .= "<td>".$product_composition->quantity."</td>";
          $return_data .= "<td>".$product_composition->reason."</td>";
          $return_data .= "<td>".$product_composition->reference_standard."</td>";
          $return_data.="<td>
        <button
        class='btn btn-warning btn-sm editcomposition' value='".$product_composition->application_id."'
      data-di= '".$product_composition->id."'  id='show_edit_composition' ><i class='fas fa-pencil-alt'></i>
        </button>
        <br/> <br/>
 <button
 class='btn btn-danger btn-sm' value='".$product_composition->application_id."'
 onclick='Delete_composition($product_composition->id)' ><i class='fas fa-trash'></i>
 </button>
         </td></tr> ";


         }


         return response()->json(['renderd_product_composition_table'=>$return_data,'Message'=>true,'Compostion_id'=>$product_composition->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}

//Api Product Manufacturer


public function save_product_manufacturer_api(Request $request)
{
          try{
        ///dd($request->name);

        $manufacturer_apis = api_manufacturers::create($request->all());
        // dd($medicinal_details);

        $manufacturer_apis = api_manufacturers::where('user_id',$request->user_id)
         ->where('application_id',$request->application_id)
        // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
        ->orderby('id','ASC')
         ->get();


        



$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",6";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);


       $return_data = "";$i=1;
         foreach( $manufacturer_apis as $manufacturer_api)

         {

          $countries = Country::where('id', $manufacturer_api->country_id)
          // ->where('email',$request->email)
           //->join('countries','countries.id','manufacturers.country_id')
           ->get();

        $return_data .= "<tr><td>".$i++."</td>";
        // $return_data .= "<td>".$manufacturer_api->application_id."</td>";
        $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
        $return_data .= "<td>".$manufacturer_api->api_name."</td>";
        $return_data .= "<td>".$countries[0]->country_name."</td>";
        $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
        $return_data .= "<td>".$request->manu_api_response_tele.$manufacturer_api->telephone."</td>";
        $return_data .= "<td>".$manufacturer_api->city."</td>";
        $return_data .= "<td>".$manufacturer_api->state."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
        $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";
        //$return_data .= "<td>".$manufacturer_api->webiste_url."</td>";
        //$return_data .= "<td>".$manufacturer_api->email."</td>";
        $return_data .= "<td>".$manufacturer_api->unit."</td>";
        $return_data .= "<td>".$manufacturer_api->block."</td>";

        
        $return_data.="<td>
        <span class='btn btn-warning btn-sm editmanufacturer_api' data-di='".$manufacturer_api->id."'  ><i class='fas fa-pencil-alt'></i> </span>
         <br/> <br/>

         <span
         class='btn btn-danger btn-sm' value='".$manufacturer_api->id."'
         onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i>
         </span>
         </td></tr> ";


         }


         return response()->json(['renderd_manufacturer_api_table'=>$return_data,'Message'=>true,'Manufacturer_api_id'=>$manufacturer_api->id]);



    }

    catch(Exception $e){
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}




public function update_product_manufacturer_api(Request $request)
{
          try{

            $manufacturer_update_api = DB::table('api_manufacturers')
            ->where('id', $request->id_for_update_api)
            ->where('application_id', $request->application_id)
            ->orderby('id','ASC')
            ->update(
                 [
                  'manufacturer_name' => $request->manufacturer_name,
                  'country_id' => $request->country_id,
                  'city' => $request->city,
                  'state' => $request->state,
                  'addressline_one' => $request->addressline_one,
                  'addressline_two' => $request->addressline_two,
                  'postal_code' => $request->postal_code,
                  'telephone' => $request->telephone,
                  'unit' => $request->unit,
                  'block' => $request->block,
                  'api_name' => $request->api_name,

                  ]
                  );


             ;


                  $manufacturer_apis = api_manufacturers::where('user_id',$request->user_id)
                  ->where('application_id',$request->application_id)
                 // ->join('medicinal_products','medicinal_products.id','product_composition.medical_product_id')
                  ->get();

         $Application_ID  = applications::where('application_id',$request->application_id)->get();
         $Application_ID[0]->hold_progress_wizard;
         $current_progress = $Application_ID[0]->hold_progress_wizard.",6";
         $update_applications_details_hold_wizard = DB::table('applications')
         ->where('application_id', $request->application_id)
         ->update(
           [
           'hold_progress_wizard' =>$current_progress,
           ]
         );


                $return_data = "";$i=1;
                  foreach( $manufacturer_apis as $manufacturer_api)

                  {

                    $countries = Country::where('id', $manufacturer_api->country_id)
                    // ->where('email',$request->email)
                     //->join('countries','countries.id','manufacturers.country_id')
                     ->get();


                     $return_data .= "<tr><td>".$i++."</td>";
                     // $return_data .= "<td>".$manufacturer_api->application_id."</td>";
                     $return_data .= "<td>".$manufacturer_api->manufacturer_name."</td>";
                     $return_data .= "<td>".$manufacturer_api->api_name."</td>";
                     $return_data .= "<td>".$countries[0]->country_name."</td>";
                     $return_data .= "<td>".$manufacturer_api->postal_code."</td>";
                     $return_data .= "<td>".$request->manu_api_response_tele.$manufacturer_api->telephone."</td>";
                     $return_data .= "<td>".$manufacturer_api->city."</td>";
                     $return_data .= "<td>".$manufacturer_api->state."</td>";
                     $return_data .= "<td>".$manufacturer_api->addressline_one."</td>";
                     $return_data .= "<td>".$manufacturer_api->addressline_two."</td>";
                     //$return_data .= "<td>".$manufacturer_api->webiste_url."</td>";
                     //$return_data .= "<td>".$manufacturer_api->email."</td>";
                     $return_data .= "<td>".$manufacturer_api->unit."</td>";
                     $return_data .= "<td>".$manufacturer_api->block."</td>";
             

                 $return_data.="<td>
               <span class='btn btn-warning btn-sm editmanufacturer_api'  data-di='".$manufacturer_api->id."'  ><i class='fas fa-pencil-alt'></i>  </span>
                 <br/> <br/>
               <span class='btn btn-danger btn-sm' onclick= 'Delete_manufacture_api($manufacturer_api->id)' ><i class='fas fa-trash'></i> </span>
                  </td></tr> ";


                  }


                  return response()->json(['renderd_manufacturer_api_table'=>$return_data,'Message'=>true,'Manufacturer_api_id'=>$manufacturer_api->id]);



             }

             catch(Exception $e){
                 return response()->json(['Message'=>$e,'item'=>'error'.$e]);
                 }

}



public function Get_country_id(Request $request)
{

  $countries = Country::where('country_name',$request->country_name)
  // ->where('email',$request->email)
   //->join('countries','countries.id','manufacturers.country_id')
   ->get();

   return response()->json(['CountryID'=>$countries[0]->id]);


}





public function random($length)
{


$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
$rendered_value='';
for($i=0;$i<$length;$i++)
{
    $rendered_value.=substr($chars,mt_rand(0,strlen($chars)),1);
}

return $rendered_value;
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
    }}
//Saving Application Status




public function application_save_re_register(Request $request)
{

  //dd($request->all());

    $applications =  new applications;


          try
           {
        $t=time();
        $year = Date('Y');
        $count = applications::where('id', '<>', null)->count();
        $count_sequence = $count + 1;
        $zero_filled_counter = sprintf('%04d', $count_sequence);
        $random_application_id= 'NMFA_'.$year."_".$zero_filled_counter; // $random_application_id= 'NMFA_'.date("Y-m-d",$t)."_".ApplicationReceptionController::random(12);
        $request['application_id'] = $random_application_id;


        //Count the check_re-registration section of particular application_id 

        $count_application_number = check_re_registered_application::where('old_id', '=',$request->old_application_id)->count();

        $re_registration_number = 'R'.$count_application_number++;

        
       // $applications = applications::create($request->all());

        applications::insert([
          'application_id'=> $request['application_id'],
          'user_id'=>$request->user_id,
          'application_type'=>$request->application_type,
          'fast_track_details'=>$request->fast_track_details,
          // 'application_number' => $request->application_number,
          'registration_type' => $request->registration_type,
          're_registration_number' => $re_registration_number ,
          'created_at' => now()
          ]);

          
      $request->session()->put('application_id_must_not_be_repeated', $random_application_id);



        $decleration = DB::table('declarations')->insert(['application_id' => $request['application_id'],]);
        $request->session()->put('new_application_id', $random_application_id);
        $application=applications::where('application_id',$random_application_id)->first();

            $duration_days = 30;
            $task_name = 'Application';
            $related_task = 'Application';
            $related_id =  $application->id;
            $start_time = date('Y-m-d H:i:s');
            $end_time = date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $stopping_reason = '';
            $task_duration_days_actual = null;
            $is_active = 1;
            $is_complete = 0;
            $is_archived = 0;
            $task_status = 'Inprogress';
            $deadline = $end_time;

            //notify before days
            $alert_before_days = 5;


            // alert('Main Task');
            // Log::alert('main receiption application');

           MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);

            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Applying';
            $task_activity_title = 'Application process';
            $content_details = 'New application initiated ';
            $route_link = '';
            $activity_status = 'Inprogress';
            $uploaded_document_id = null;
            // application_evaluation_progresses::insert([
            //  'application_id'=>$application->id,

            //]);
            //insert this into task tracker

           MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);


           $tasks = TaskTracker::where('task_id', $main_task->id)
            ->where('task_category','Applying')
            ->first();

            //->OrderBy('task_trackers.id', 'desc')

            application_evaluation_progresses::insert([
            'application_id'=>$application->id,
            'task_id' =>$tasks->id,
            ]);


        $user=User::where('id', $application->user_id)->first();


        $new_notification=[];
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='New Application';
        $new_notification['from_user'] = 'System Notification';
        $new_notification['data'] = 'New Appication has intiated by applicant';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $application->id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;

Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'New application has been initiated and  it should be completed before  deadline (30 days) '));

$applications = applications::where('user_id',$request->user_id)->where('application_id',$random_application_id)->get();

$Application_ID  = applications::where('application_id', $request['application_id'])->get();

$Application_ID[0]->hold_progress_wizard;
$current_progress = "0";


$update_applications_details_hold_wizard = DB::table('applications')->where('application_id', $request['application_id'])->update([ 'hold_progress_wizard' =>$current_progress,]);

//dd($request->re_registration_number);

$count_application_number = check_re_registered_application::where('old_id', '=',$request->old_application_id)->count();


if($request->re_registration_number== true  &&  $count_application_number=='')
{

  // $get_registration_number = applications::where('application_id', '=',$request->old_app_id)->first();
  // $explode_registration_number  = explode('/',$get_registration_number->re_registration_number);
  // $explode_third_element =  $explode_registration_number[2];
  // $explode_the_R0  = explode('R',$explode_third_element);
  // $count_the_R = "R".($explode_the_R0[1] + 1);
  // $Regisration_number = $explode_registration_number[0]."/".$explode_registration_number[1]."/".$count_the_R."/".$explode_registration_number[3]."/".$explode_registration_number[4];

  $requestregistered_applications =  DB::table('check_re_registered_applications')->insert([
  'application_id' => $request['application_id'],
  'application_number' =>'-',
  're_registration_number' => '-',
  'old_id' => $request->old_app_id,
  'user_id' => auth()->user()->id,
  'created_at'=>Now(),
  'updated_at' => Now(),

  ]);


  $get_application_id = certification::join('decisions', 'certifications.decision_id', 'decisions.id')
  ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
 ->join('applications', 'applications.id', 'dossier_assignments.application_id')
 ->join('check_re_registered_applications','check_re_registered_applications.old_id','applications.application_id')
 ->select('applications.*','applications.id as appid','certifications.id as cert_id')
 ->where('check_re_registered_applications.application_id',$request['application_id'])
  ->first();

  $update_applications_details_hold_wizard = DB::table('certifications')->
  where('id', $get_application_id->cert_id)->
  update([
    'status' =>'reregistration_initiated',
    ]);




}

else if($request->re_registration_number== true  &&  $count_application_number >=1 )
{

  $get_registration_number = check_re_registered_application::where('application_number', '=',$request->application_number)->orderBy('application_number','DESC')->first();
  // $explode_registration_number  = explode('/',$get_registration_number->application_re_registration_number);
  // $explode_third_element =  $explode_registration_number[2];
  // $explode_the_R0  = explode('R',$explode_third_element);
  // $count_the_R = "R".($explode_the_R0[1] + 1);
  // $Regisration_number = $explode_registration_number[0]."/".$explode_registration_number[1]."/".$count_the_R."/".$explode_registration_number[3]."/".$explode_registration_number[4];

  $requestregistered_applications =  DB::table('check_re_registered_applications')->insert([
    'application_id' => $request['application_id'],
    'application_number' =>'-',
  're_registration_number' => '-',
    'old_id' => $request->old_app_id,
    'user_id' => auth()->user()->id,
    'created_at'=>Now(),
    'updated_at' => Now(),
    ]);

    $get_application_id = certification::join('decisions', 'certifications.decision_id', 'decisions.id')
    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
   ->join('applications', 'applications.id', 'dossier_assignments.application_id')
   ->join('check_re_registered_applications','check_re_registered_applications.old_id','applications.application_id')
   ->select('applications.*','applications.id as appid','certifications.id as cert_id')
   ->where('check_re_registered_applications.application_id',$request['application_id'])
    ->first();

    $update_applications_details_hold_wizard = DB::table('certifications')->
    where('id', $get_application_id->cert_id)->
    update([
      'status' =>'reregistration_initiated',
      ]);


}


 return response()->json(['Message'=>true,'application_id'=> $random_application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}







public function application_save(Request $request)
{

  //dd($request->all());

    $applications =  new applications;


          try
           {
        $t=time();
        $year = Date('Y');
        $count = applications::where('id', '<>', null)->count();
        $count_sequence = $count + 1;
        $zero_filled_counter = sprintf('%04d', $count_sequence);
        $random_application_id= 'NMFA_'.$year."_".$zero_filled_counter; // $random_application_id= 'NMFA_'.date("Y-m-d",$t)."_".ApplicationReceptionController::random(12);
        $request['application_id'] = $random_application_id;

        $count_application_number = applications::where('application_number', '=',$request->application_number)->count();

       // $applications = applications::create($request->all());

        applications::insert([
          'application_id'=> $request['application_id'],
          'user_id'=>$request->user_id,
          'application_type'=>$request->application_type,
          'fast_track_details'=>$request->fast_track_details,
          'application_number' => $request->application_number,
          'registration_type' => $request->registration_type,
          ]);



        $decleration = DB::table('declarations')->insert(['application_id' => $request['application_id'],]);
        $request->session()->put('new_application_id', $random_application_id);
        $application=applications::where('application_id',$random_application_id)->first();

            $duration_days = 30;
            $task_name = 'Application';
            $related_task = 'Application';
            $related_id =  $application->id;
            $start_time = date('Y-m-d H:i:s');
            $end_time = date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $stopping_reason = '';
            $task_duration_days_actual = null;
            $is_active = 1;
            $is_complete = 0;
            $is_archived = 0;
            $task_status = 'Inprogress';
            $deadline = $end_time;

            //notify before days
            $alert_before_days = 5;


            // alert('Main Task');
            // Log::alert('main receiption application');

           MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);

            $main_task = $this->get_main_task_id($application->id,'Application');
            $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Applying';
            $task_activity_title = 'Application process';
            $content_details = 'New application initiated ';
            $route_link = '';
            $activity_status = 'Inprogress';
            $uploaded_document_id = null;
            // application_evaluation_progresses::insert([
            //  'application_id'=>$application->id,

            //]);
            //insert this into task tracker

           MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
          
          
           $tasks = TaskTracker::where('task_id', $main_task->id)
            ->where('task_category','Applying')
            ->first();
            
            //->OrderBy('task_trackers.id', 'desc')
    
            application_evaluation_progresses::insert([
            'application_id'=>$application->id,
            'task_id' =>$tasks->id,
            ]);



            //$user=User::where('id', $application->user_id)->first();


        $user=User::where('id', $application->user_id)->first();
           
           
        $new_notification=[];
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='New Application';
        $new_notification['from_user'] = 'System Notification';
        $new_notification['data'] = 'New Appication has intiated by applicant';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $application->id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
            

Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'New application has been initiated and  it should be completed before  deadline (30 days) '));
   
$applications = applications::where('user_id',$request->user_id)->where('application_id',$random_application_id)->get();

$Application_ID  = applications::where('application_id', $request['application_id'])->get();

$Application_ID[0]->hold_progress_wizard;
$current_progress = "0";


$update_applications_details_hold_wizard = DB::table('applications')->where('application_id', $request['application_id'])->update([ 'hold_progress_wizard' =>$current_progress,]);

//dd($request->re_registration_number);

$count_application_number = check_re_registered_application::where('old_id', '=',$request->application_number)->count();


if($request->re_registration_number== true  &&  $count_application_number==1)
{

  $get_registration_number = applications::where('application_id', '=',$request->old_app_id)->first();
  $explode_registration_number  = explode('/',$get_registration_number->re_registration_number);
  $explode_third_element =  $explode_registration_number[2];
  $explode_the_R0  = explode('R',$explode_third_element);
  $count_the_R = "R".($explode_the_R0[1] + 1);
  $Regisration_number = $explode_registration_number[0]."/".$explode_registration_number[1]."/".$count_the_R."/".$explode_registration_number[3]."/".$explode_registration_number[4];

  $requestregistered_applications =  DB::table('check_re_registered_applications')->insert([
  'application_id' => $request['application_id'],
  'application_number' => $request->application_number,
  're_registration_number' => $Regisration_number,
  'old_id' => $request->old_app_id,
  'user_id' => auth()->user()->id,
  'created_at'=>Now(),
  'updated_at' => Now(),
  
  ]);
}

else if($request->re_registration_number== true  &&  $count_application_number >=2 )
{

  $get_registration_number = check_re_registered_application::where('application_number', '=',$request->application_number)->orderBy('application_number','DESC')->first();

  $requestregistered_applications =  DB::table('check_re_registered_applications')->insert([
  'application_id' => $request['application_id'],
  'application_number' => $request->application_number,
    're_registration_number' => '-',
    'old_id' => $request->old_app_id,
  'user_id' => auth()->user()->id,
    'created_at'=>now(),
    'updated_at' => now(),
  ]);

}





       return response()->json(['Message'=>true,'application_id'=> $random_application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function application_update(Request $request)
{
// dd($request->all());

  $applications =  new applications;


  try
   {

$update_applications_details = DB::table('applications')
->where('application_id', $request['application_id'])
->update(
[
'application_type' =>    $request->application_type,
'fast_track_details' =>  $request['fast_track_details'],
'progress_percentage' => $request->progress_percentage,

]
);

return response()->json(['Message'=>true,'Application_Type'=> $request['fast_track_details']]);
    }
catch(Exception $e)
{
return response()->json(['Message'=>$e,'item'=>'error'.$e]);
}


}


public function dossier_sample_save(Request $request)
{

    $dossier_status =  new applications;

          try
           {
            $dossier_status = DB::table('applications')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'dossier_url' => $request->dossier_url,
                'flag_dossier_url' => $request->flag_dossier_url,

                ]
              );
        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();


$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";


$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

$main_task = $this->get_main_task_id($applications[0]->id,'Application');
$end_time = date('Y-m-d H:i:s');
$issued_datetime = date('Y-m-d H:i:s');
$task_category = 'Applying';
$task_activity_title = 'Dossier link created';
$content_details = 'New application dossier link created ';
$route_link = '';
$activity_status = 'Locked';
$uploaded_document_id = null;
MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);


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
                $new_notification['subject'] ='Dossier has been Created';
                $new_notification['from_user'] = 'System Reminder';
                $new_notification['data'] = 'Applicant  has created new dossier link';
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $request->application_id;
                $new_notification['alert_level'] = null;
                $new_notification['remark'] = null;
              // ::send($users, new ($invoice));


              Notification::send($user, new ApplicationReceiptionNotification($new_notification));
              event(new ApplicationReceiptionEvent($user->id, 'New application has been created and Dossier submitted for application number '. $applications[0]->application_number ));


              }
            }
          }
        }

       return response()->json(['Message'=>true,'application_id'=> $request->application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function dossier_sample_update(Request $request)
{

    $dossier_status =  new applications;

          try
           {
            $dossier_status = DB::table('applications')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'dossier_url' => $request->dossier_url,
                 'flag_dossier_url' => $request->flag_dossier_url,

                ]
              );
        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",8";


$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);

       return response()->json(['Message'=>true,'application_id'=> $request->application_id]);
            }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
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
          $new_notification['subject'] ='Dossier link Updated';
          $new_notification['from_user'] = 'System Reminder';
          $new_notification['data'] = 'Applicant  has updated new dossier link';
          $new_notification['related_document'] = null;
          $new_notification['related_id'] = $request->application_id;
          $new_notification['alert_level'] = null;
          $new_notification['remark'] = null;
        // ::send($users, new ($invoice));


        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
        event(new ApplicationReceiptionEvent($user->id, ' New application  Dossier Link Updated' ));




        }
        }
        }
        }

}

public function decleration_save(Request $request)
{
    $decleration =  new applications;

          try
           {
            $decleration = DB::table('declarations')
              ->where('application_id', $request->application_id)
              ->update(
                [
                'date' => $request->date,
                'position' => $request->position,
                'user_id'=> $request->user_id,
                'qualification'=> $request->qualification,
                 'name'=>$request->name,

                ]
              );

        $applications = applications::where('user_id',$request->user_id)->
        where('application_id',$request->application_id)
        ->get();


        $main_task = $this->get_main_task_id($applications[0]->id,'Application');
        $end_time = date('Y-m-d H:i:s');
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Applying';
        $task_activity_title = 'Declaration note saved successfully';
        $content_details = 'Declaration note saved successfully ';
        $route_link = '';
        $activity_status = 'Locked';
        $uploaded_document_id = null;
        
        //MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
        

$Application_ID  = applications::where('application_id',$request->application_id)->get();
$Application_ID[0]->hold_progress_wizard;
$current_progress = $Application_ID[0]->hold_progress_wizard.",7";
$update_applications_details_hold_wizard = DB::table('applications')
->where('application_id', $request->application_id)
->update(
  [
  'hold_progress_wizard' =>$current_progress,
  ]
);



$rendered_html_data = $this->rendered_html_data($request->application_id);
$pdf = PDF::loadHTML($rendered_html_data);
$pdf->setPaper ('A4', 'portrait');
// <--- load your view into theDOM wrapper;

$time=time();
$path = public_path('storage/DeclarationNote/');

// <--- folder to store the pdf documents into the server;


$fileName = "DeclarationNote"."-".$request->application_id.$time."-".'.pdf' ; 

// <--giving the random filename,

$pdf->save($path.$fileName);

$generated_pdf_link = Storage::url('public/DeclarationNote/'.$fileName);



$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '3';
$documents->ref_num = $request->application_id;
$documents->description = 'Declaration';
$documents->save();



$generated_url_link ="/print/$request->application_id/print/";



$update_application_number = DB::table('declarations')
->where('application_id', $request->application_id)
->update(
  [
  'document_attachment_id' =>$documents->id,
  ]
);





$documents->path =  $generated_pdf_link ;
return response()->json(['Message'=>true,'application_id'=> $request->application_id,'Download_Link'=>$generated_pdf_link,'Download_Link_two'=>$generated_url_link ] );

   }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

}



public function  decleration_rendered_html_data($application_id)
{



}













public function rendered_html_data($application_id)
{

   //dd($application_id);
  
  $path_header = "images/nmfa_header.png";
  $path_footer = "images/nmfa_footer.png";

  $decleration =  new applications;
  
$issue_declarations  =   declerations::where('application_id',$application_id)->get();


$Product_details= DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
  'medicinal_products.id as _id',
 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name',
 'route_administrations.name as route_name',
 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id',
 'route_administrations.id as route_id',
 'dosage_forms.id as dosage_id','dosage_forms.name as dname', 
 'medicines.*',
 'medicinal_products.*',
 'route_administrations.*',
                'dosage_forms.*'
  )
->where('medicinal_products.application_id',$application_id)
->get();


$company_supplier_per_applicant = company_suppliers::where('application_id',$application_id)->get();


// dd($company_supplier_per_applicant);
//$applications = applications::create($request->all());
$issue_declarations =   declerations::where('application_id',$application_id)->get();
 //dd($issue_declarations);
foreach($issue_declarations   as $checked_issue_declarations) {

  $namee =  $checked_issue_declarations['name'];
  $qualification =  $checked_issue_declarations['qualitification'];
  $position = $checked_issue_declarations['position'];
  $date = $checked_issue_declarations['date'];



}

$rendered_template = "
  <!DOCTYPE html>
  <html lang='en-US'>
      <head>
     </head>

     <!-- Main content -->
            <div class='invoice p-3 mb-3'>
              <!-- title row -->
         
                <div class='col-12'>
                  <h4>
<img src='".$path_header."'  alt='image' height='100' width='690'/>
                  </h4>
                </div>
   
              <p class='decleration'> 
              I, the undersigned certify that all the information in this form and all accompanying documentation submitted to Eritrea 
              for the registration of (".$Product_details[0]->product_trade_namme.", ".$Product_details[0]->medicine_product_name." and ".$Product_details[0]->dname.") manufactured at 
      
          (".$company_supplier_per_applicant[0]->trade_name." , ".$company_supplier_per_applicant[0]->address_line_one." and  ".$company_supplier_per_applicant[0]->address_line_two.")
          
          is true and correct. I further certify that I have examined the following statements and I attest to their correctness:- 
              </P>
              
              <p class='decleration'>
              1.	The current edition of the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products or equivalent guideline is applied in full in all premises involved in the manufacture of this medicine. 
              <br/>
              2.	The formulation per dosage form correlates with the master formula and with the batch manufacturing record. 
              <br/>
              3.	The manufacturing procedure is exactly as specified in the master formula and batch manufacturing record.
              <br/>
              4.	Each batch of all starting materials is either tested or certified (in accompanying certificate of analysis for that batch) against the full specifications in the accompanying documentation and must comply fully with those specifications before it is released for manufacturing purposes. 
              <br/>
              5.	All batches of the active pharmaceutical ingredient(s) are obtained from the source(s) specified in the accompanying documentation. 
              <br/>
              6.	No batch of active pharmaceutical ingredient(s) will be used unless a copy of the batch certificate established by the manufacturer is available. 
              <br/>
              7.	Each batch of the container/closure system is tested or certified against the full specifications in the accompanying documentation and complies fully with those specifications before released for the manufacturing purposes. 
              <br/>
              8.	Each batch of the finished product is either tested, or certified (in an accompanying certificate of analysis for that batch), against the full specifications in the accompanying documentation and complies fully with release specifications before released for sale. 
              <br/>
              9.	The person releasing the product is an authorized person as defined by the WHO Guidelines on good manufacturing Practices (GMP) for pharmaceuticals products
              <br/>
              10.	The procedures for control of the finished product have been validated. The assay method has been validated for accuracy, precision, specificity and linearity. 
              <br/>
              11.	All the documentation referred to in this application is available for review during GMP inspection. 
              <br/>
              12.	Clinical trials (where applicable) were conducted in accordance with ICH, WHO or equivalent guidelines for Good Clinical Practice, 
              <br/>
              I also agree that: 
              <br/>
              13.	As a holder of marketing authorization/registration of the product I will adhere to Eritrean National Pharmacovigilance Policy requirements for handling adverse reactions. 
              <br/>
              14.	As holder of registration I will adhere to Eritrean requirements for handling batch recalls of the products.
              <br/>
                          </p>
   <div class='col-12 col-sm-6'>
                     <p> Name: $namee </p>
                     <p> Qualification: $qualification </p>
                      <p> Position:$position </p>
                      <p>Date: $date</p>
                  </div>
                      </div>
  <p>
<img src='".$path_footer."'  height='100' width='690'/>       
</p>        
              </div>

         

  </body>
</html>
  ";

  return $rendered_template;
}


public function edit(application $application)
{
  // return  ($task->id).($task->description);
   //$tasks = Task::find($task->id);

  //dd($Todo_update_id);
  //return $Todo_update_id;
 // return view('todos.edit');
 return view('application_reception.update',compact('task'));

}





  public function application_reception_complete_wizard_control(Request $request)

  {

    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    $route_administrations = route_administrations::all()->sortBy('name');
    $agents = agents::all()->sortBy('trade_name');
    $company_suppliers = company_suppliers::all()->sortBy('trade_name');

    $product_detailss =  product_details::all()->sortBy('product_name');

    $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

    $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
    $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

     $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
     $agents_template = agents_template::all()->sortBy('trade_name');


     $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $country_contact_info_supplier_info = DB::table('countries')->where('id',$contact_detail_per_applicant_supplier[0]->country_id)
     ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
     ->get();

     $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
     ->where('contact_type','Supplier')
     ->get();

     $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


     $applications_status = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->select('countries.*','countries.id as countryid','applications.*',
             'invoices.*','contacts.*', 'invoices.amount',
              'medicines.product_name',
             'medicinal_products.product_trade_name',
             'manufacturers.addressline_one as manufacturer_addressline_one',
             'manufacturers.addressline_two as manufacturer_addressline_two',
             'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
             'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
             'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
             'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
             'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
             'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
             'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
             'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
             'contacts.last_name as cont_last_name','contacts.id as cont_id', 
             'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
             'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email', 'contacts.fax as cont_fax', 
             'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
             'contacts.position as cont_position','contacts.city as cont_city'
              )
              ->where('contact_type','Supplier')
            ->where('applications.application_id',$request->application_id)
            ->orderBy('invoices.invoice_number','ASC')
            ->get();

            if(empty($applications_status[0]->cont_id) )
            {
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
                        ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                        ->get();

            }
            else if(!empty($applications_status[0]->cont_id) )
            {

           $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
               //->Orwhere('id',68)
               ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
               ->get();


            }



$agent_contact_info = DB::table('applications')
                      ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                      ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                      ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                      ->select('agents.*','contacts.*','agents.trade_name as business_name','agents.state as agent_state','agents.address_line_one as agent_address_line_one','agents.address_line_two as  agent_address_line_two','agents.city as agent_city','agents.postal_code as agent_postal_code','agents.telephone as agent_telephone','agents.webiste_url as agent_webiste_url','agents.email as agent_email','agents.country_code as agent_country_code','contacts.id as cont_id','agents.id as agent_id','contacts.first_name as cont_first','contacts.middle_name as cont_middle','contacts.last_name as cont_last','contacts.position as cont_position','contacts.city as cont_city','contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two','contacts.postal_code as cont_postal_code','contacts.telephone as cont_tele','contacts.fax as cont_fax','contacts.email as cont_email')
                       ->where('contact_type','Agent')
                       ->where('agents.application_id',$request->application_id)
                       ->get();



 $agent_contact_County_info = DB::table('countries')->where('id','68')
                         ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                         ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
  'medicinal_products.id as _id',
 'medicinal_products.medicine_id as medicine_id',
 'medicinal_products.product_trade_name as product_trade_namme',
 'medicines.product_name as medicine_product_name',
 'route_administrations.name as route_name',
 'dosage_forms.name as dosage_name',
 'medicines.id as medicinal_id',
 'route_administrations.id as route_id',
 'dosage_forms.id as dosage_id',
 'medicines.*',
 'medicinal_products.*',
 'route_administrations.*',
                'dosage_forms.*'
  )
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$request->application_id)
                            ->select('applications.*','medicines.*')
                            ->get();


$product_manufacturers_info = DB::table('manufacturers')
                                     ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                     ->where('manufacturers.application_id',$request->application_id)
                                     ->select('manufacturers.id as mmid','manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                     'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                     ->get();

 $api_manufacturers_info = DB::table('api_manufacturers')
                               ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                              ->where('api_manufacturers.application_id',$request->application_id)
                              ->select(   'api_manufacturers.*','countries.*',
                                          'api_manufacturers.api_name as api_name',
                                          'api_manufacturers.id as api_id')
                              ->get();

 @$decleration_info = DB::table('declarations')
 ->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
             ->where('declarations.application_id',$request->application_id)
                             ->select('declarations.*','documents.*','declarations.name as decname')
                             ->get();


  @$docu_id = DB::table('documents')
   ->where('documents.id',$decleration_info[0]->document_attachment_id)
   ->select('documents.*')
   ->get();


$get_details_declarations = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('manufacturers', 'medicinal_products.application_id', '=', 'manufacturers.application_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select('medicinal_products.id as _id','medicinal_products.medicine_id as medicine_id','medicinal_products.product_trade_name as product_trade_namme','medicines.product_name as medicine_product_name','route_administrations.name as route_name','dosage_forms.name as dosage_name','medicines.id as medicinal_id','route_administrations.id as route_id','dosage_forms.id as dosage_id','medicines.*','medicinal_products.*','route_administrations.*','dosage_forms.*','manufacturers.*','manufacturers.addressline_one as manufacturer_addressline_one','manufacturers.addressline_two as manufacturer_addressline_two','manufacturers.name as manufacturer_name',)
->where('medicinal_products.application_id',$request->application_id)
->first();


//dd($get_details_declarations);

         return view('application_reception.update',[

            'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
            'decleration_info'=> $decleration_info,
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'country_contact_info_supplier_info' => $country_contact_info_supplier_info ,
            'company_suppliers'=> $company_suppliers,
            'agents'=>$agents,
            'medicines'=>$product_detailss,
            'application_check_wizard'=>$application_check_wizard,
            'explode' =>  $explode,
            'company_supplier_per_applicant'=> $company_supplier_per_applicant,
            'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
            'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
            'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
            'applications_status'  => $applications_status,
            'country_contact_info' => $country_contact_info,
            'agent_contact_County_info'=>$agent_contact_County_info,
            'agent_contact_info' => $agent_contact_info,
            'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
            'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
            'med_id'=> (@$product_details[0]->medicine_id =='')?0:1,
            'product_details_general'=> $product_details,
            'product_composition_info'=>$product_composition_info,
            'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
            'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
            'product_manufacturers_info'=>$product_manufacturers_info,
            'company_suppliers_template' =>$company_suppliers_template,
            'agents_template'=>$agents_template ,
            'api_manufacturers_info' => $api_manufacturers_info,
            'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
            'docu_id' =>  @$docu_id,
            'get_details_declarations' => $get_details_declarations,
        ]);


}




public function application_reception_complete_wizard_control_re(Request $request)
{

$countries = Country::all()->sortBy('country_name');;
$fast_track_applications =  fast_track_application::all()->sortBy('name');;
$dosage_forms  = DosageForms::all()->sortBy('name');;
$apis  = apis::all()->sortBy('api_name');;
$route_administrations = route_administrations::all()->sortBy('name');
$agents = agents::all()->sortBy('trade_name');
$company_suppliers = company_suppliers::all()->sortBy('trade_name');



$company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

$application_check_wizard  = applications::where('application_id',$request->application_id)->get();
$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

$company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
$agents_template = agents_template::all()->sortBy('trade_name');



$contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
->where('contact_type','Supplier')
->get();


$country_contact_info_supplier_info = DB::table('countries')->where('id',$contact_detail_per_applicant_supplier[0]->country_id)
     ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
     ->get();

$contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
->where('contact_type','Supplier')
->get();

$agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


$applications_status = DB::table('applications')
->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
->leftjoin('invoices','applications.application_id','=','invoices.application_id')
->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
->select('countries.*','countries.id as countryid','applications.*',
'invoices.*','contacts.*', 'invoices.amount',
 'medicines.product_name',
'medicinal_products.product_trade_name',
'medicinal_products.product_trade_name',
'manufacturers.addressline_one as manufacturer_addressline_one',
'manufacturers.addressline_two as manufacturer_addressline_two',
'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
'contacts.last_name as cont_last_name','contacts.id as cont_id',
'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email', 'contacts.fax as cont_fax',
'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
'contacts.position as cont_position','contacts.city as cont_city'
 )
->where('applications.application_id',$request->application_id)
->orderBy('invoices.invoice_number','ASC')
->get();

if(empty($applications_status[0]->cont_id) )
{
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
           ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
           ->get();

}
else if(!empty($applications_status[0]->cont_id) )
{

$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
  //->Orwhere('id',68)
  ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
  ->get();


}







$agent_contact_info = DB::table('applications')
         ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
         ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
         ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
         ->select('agents.*','contacts.*',
         'agents.trade_name as business_name',
         'agents.state as agent_state',
         'agents.address_line_one as agent_address_line_one',
         'agents.address_line_two as  agent_address_line_two',
         'agents.city as agent_city',
         'agents.postal_code as agent_postal_code',
         'agents.telephone as agent_telephone',
         'agents.webiste_url as agent_webiste_url',
         'agents.email as agent_email',
         'agents.country_code as agent_country_code',
         'contacts.id as cont_id',
         'agents.id as agent_id',
         'contacts.first_name as cont_first',
         'contacts.middle_name as cont_middle',
         'contacts.last_name as cont_last',
         'contacts.position as cont_position',
         'contacts.city as cont_city',
         'contacts.address_line_one as cont_line_one',
         'contacts.address_line_two as cont_line_two',
         'contacts.postal_code as cont_postal_code',
         'contacts.telephone as cont_tele',
         'contacts.fax as cont_fax',
         'contacts.email as cont_email')
          ->where('contact_type','Agent')
          ->where('agents.application_id',$request->application_id)
          ->get();



$agent_contact_County_info = DB::table('countries')->where('id','68')
            ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
            ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
'medicinal_products.id as _id',
'medicinal_products.medicine_id as medicine_id',
'medicinal_products.product_trade_name as product_trade_namme',
'medicines.product_name as medicine_product_name',
'route_administrations.name as route_name',
'dosage_forms.name as dosage_name',
'medicines.id as medicinal_id',
'route_administrations.id as route_id',
'dosage_forms.id as dosage_id',
'medicines.*',
'medicinal_products.*',
'route_administrations.*',
'dosage_forms.*',
)
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
               ->where('applications.application_id',$request->application_id)
               ->select('applications.*','medicines.*')
               ->get();


$product_manufacturers_info = DB::table('manufacturers')
                        ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                        ->where('manufacturers.application_id',$request->application_id)
                        ->select('manufacturers.id as mmid','manufacturers.*','countries.*','manufacturers.id as manufac_id',
                        'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                        ->get();

$api_manufacturers_info = DB::table('api_manufacturers')
                  ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                 ->where('api_manufacturers.application_id',$request->application_id)
                 ->select(   'api_manufacturers.*','countries.*',
                             'api_manufacturers.manufacturer_name as api_name',
                             'api_manufacturers.id as api_id')
                 ->get();

@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
->where('declarations.application_id',$request->application_id)
                ->select('declarations.*','documents.*','declarations.name as decname')
                ->get();


@$docu_id = DB::table('documents')
->where('documents.id',$decleration_info[0]->document_attachment_id)
->select('documents.*')
->get();


$product_detailss =  product_details::all()->sortBy('product_name');

$get_details_declarations = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('manufacturers', 'medicinal_products.application_id', '=', 'manufacturers.application_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select('medicinal_products.id as _id','medicinal_products.medicine_id as medicine_id','medicinal_products.product_trade_name as product_trade_namme','medicines.product_name as medicine_product_name','route_administrations.name as route_name','dosage_forms.name as dosage_name','medicines.id as medicinal_id','route_administrations.id as route_id','dosage_forms.id as dosage_id','medicines.*','medicinal_products.*','route_administrations.*','dosage_forms.*','manufacturers.*','manufacturers.addressline_one as manufacturer_addressline_one','manufacturers.addressline_two as manufacturer_addressline_two','manufacturers.name as manufacturer_name',)
->where('medicinal_products.application_id',$request->application_id)
->first();





return view('application_reception.application_reception_re_registration_update',[

'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
'decleration_info'=> $decleration_info,
'countries' => $countries,
'fast_track_applications' =>$fast_track_applications,
'country_contact_info_supplier_info' => $country_contact_info_supplier_info ,
'dosage_forms'=>  $dosage_forms,
'apis'=>  $apis,
'route_administrations'=>$route_administrations ,
'company_suppliers'=> $company_suppliers,
'agents'=>$agents,
'medicines'=>$product_detailss,
'application_check_wizard'=>$application_check_wizard,
'explode' =>  $explode,
'company_supplier_per_applicant'=> $company_supplier_per_applicant,
'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
'applications_status'  => $applications_status,
'country_contact_info' => $country_contact_info,
'agent_contact_County_info'=>$agent_contact_County_info,
'agent_contact_info' => $agent_contact_info,
'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
'med_id'=> (@$product_details[0]->medicine_id =='')?0:1,
'product_details_general'=> $product_details,
'product_composition_info'=>$product_composition_info,
'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
'product_manufacturers_info'=>$product_manufacturers_info,
'company_suppliers_template' =>$company_suppliers_template,
'agents_template'=>$agents_template ,
'api_manufacturers_info' => $api_manufacturers_info,
'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
'docu_id' =>  @$docu_id,
'get_details_declarations' => @$get_details_declarations,
]);


}


public function view_completed_application(Request $request)
{

  $countries = Country::all()->sortBy('country_name');;
  $fast_track_applications =  fast_track_application::all()->sortBy('name');;
  $dosage_forms  = DosageForms::all()->sortBy('name');;
  $apis  = apis::all()->sortBy('api_name');;
  $route_administrations = route_administrations::all()->sortBy('name');
  $agents = agents::all()->sortBy('trade_name');
  $company_suppliers = company_suppliers::all()->sortBy('trade_name');

  $product_detailss =  product_details::all()->sortBy('product_name');

  $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

  $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
  $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

   $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
   $agents_template = agents_template::all()->sortBy('trade_name');


   $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
   ->where('contact_type','Supplier')
   ->get();

   $country_contact_info_supplier_info = DB::table('countries')->where('id',$contact_detail_per_applicant_supplier[0]->country_id)
   ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
   ->get();

   $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
   ->where('contact_type','Supplier')
   ->get();

   $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


   $applications_status = DB::table('applications')
          ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
          ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
          ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
          ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
          ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
          ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
          ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
          ->select('countries.*','countries.id as countryid','applications.*',
           'invoices.*','contacts.*', 'invoices.amount',
            'medicines.product_name',
           'medicinal_products.product_trade_name',
           'manufacturers.addressline_one as manufacturer_addressline_one',
           'manufacturers.addressline_two as manufacturer_addressline_two',
           'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
           'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
           'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
           'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
           'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
           'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
           'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
           'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
           'contacts.last_name as cont_last_name','contacts.id as cont_id', 
           'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
           'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email', 'contacts.fax as cont_fax', 
           'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
           'contacts.position as cont_position','contacts.city as cont_city'
            )
            ->where('contact_type','Supplier')
          ->where('applications.application_id',$request->application_id)
          ->orderBy('invoices.invoice_number','ASC')
          ->get();

          if(empty($applications_status[0]->cont_id) )
          {
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
                      ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                      ->get();

          }
          else if(!empty($applications_status[0]->cont_id) )
          {

         $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
             //->Orwhere('id',68)
             ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
             ->get();


          }



$agent_contact_info = DB::table('applications')
                    ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                    ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                    ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                    ->select('agents.*','contacts.*','agents.trade_name as business_name',
                    'agents.state as agent_state','agents.address_line_one as agent_address_line_one',
                    'agents.address_line_two as  agent_address_line_two','agents.city as agent_city',
                    'agents.postal_code as agent_postal_code','agents.telephone as agent_telephone',
                    'agents.webiste_url as agent_webiste_url','agents.email as agent_email',
                    'agents.country_code as agent_country_code','contacts.id as cont_id',
                    'agents.id as agent_id','contacts.first_name as cont_first',
                    'contacts.middle_name as cont_middle','contacts.last_name as cont_last',
                    'contacts.position as cont_position','contacts.city as cont_city',
                    'contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two',
                    'contacts.postal_code as cont_postal_code','contacts.telephone as cont_tele',
                    'contacts.fax as cont_fax','contacts.email as cont_email')
                     ->where('contact_type','Agent')
                     ->where('agents.application_id',$request->application_id)
                     ->get();



$agent_contact_County_info = DB::table('countries')->where('id','68')
                       ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                       ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
'medicinal_products.id as _id',
'medicinal_products.medicine_id as medicine_id',
'medicinal_products.product_trade_name as product_trade_namme',
'medicines.product_name as medicine_product_name',
'route_administrations.name as route_name',
'dosage_forms.name as dosage_name',
'medicines.id as medicinal_id',
'route_administrations.id as route_id',
'dosage_forms.id as dosage_id',
'medicines.*',
'medicinal_products.*',
'route_administrations.*',
              'dosage_forms.*'
)
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
                          ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                          ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                          ->where('applications.application_id',$request->application_id)
                          ->select('applications.*','medicines.*')
                          ->get();


$product_manufacturers_info = DB::table('manufacturers')
                                   ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                   ->where('manufacturers.application_id',$request->application_id)
                                   ->select('manufacturers.id as mmid','manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                   'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                   ->get();

$api_manufacturers_info = DB::table('api_manufacturers')
                             ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                            ->where('api_manufacturers.application_id',$request->application_id)
                            ->select(   'api_manufacturers.*','countries.*',
                                        'api_manufacturers.api_name as api_name',
                                        'api_manufacturers.id as api_id')
                            ->get();

@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
           ->where('declarations.application_id',$request->application_id)
                           ->select('declarations.*','documents.*','declarations.name as decname')
                           ->get();


@$docu_id = DB::table('documents')
 ->where('documents.id',$decleration_info[0]->document_attachment_id)
 ->select('documents.*')
 ->get();


$get_details_declarations = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('manufacturers', 'medicinal_products.application_id', '=', 'manufacturers.application_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select('medicinal_products.id as _id','medicinal_products.medicine_id as medicine_id','medicinal_products.product_trade_name as product_trade_namme','medicines.product_name as medicine_product_name','route_administrations.name as route_name','dosage_forms.name as dosage_name','medicines.id as medicinal_id','route_administrations.id as route_id','dosage_forms.id as dosage_id','medicines.*','medicinal_products.*','route_administrations.*','dosage_forms.*','manufacturers.*','manufacturers.addressline_one as manufacturer_addressline_one','manufacturers.addressline_two as manufacturer_addressline_two','manufacturers.name as manufacturer_name',)
->where('medicinal_products.application_id',$request->application_id)
->first();


       return view('application_reception.view_completed_application',[

        'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
'decleration_info'=> $decleration_info,
'countries' => $countries,
'fast_track_applications' =>$fast_track_applications,
'country_contact_info_supplier_info' => $country_contact_info_supplier_info ,
'dosage_forms'=>  $dosage_forms,
'apis'=>  $apis,
'route_administrations'=>$route_administrations ,
'company_suppliers'=> $company_suppliers,
'agents'=>$agents,
'medicines'=>$product_detailss,
'application_check_wizard'=>$application_check_wizard,
'explode' =>  $explode,
'company_supplier_per_applicant'=> $company_supplier_per_applicant,
'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
'applications_status'  => $applications_status,
'country_contact_info' => $country_contact_info,
'agent_contact_County_info'=>$agent_contact_County_info,
'agent_contact_info' => $agent_contact_info,
'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
'med_id'=> (@$product_details[0]->medicine_id =='')?0:1,
'product_details_general'=> $product_details,
'product_composition_info'=>$product_composition_info,
'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
'product_manufacturers_info'=>$product_manufacturers_info,
'company_suppliers_template' =>$company_suppliers_template,
'agents_template'=>$agents_template ,
'api_manufacturers_info' => $api_manufacturers_info,
'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
'docu_id' =>  @$docu_id,
'get_details_declarations' => @$get_details_declarations,
      ]);

}






public function view_completed_application_re(Request $request)
{

  $countries = Country::all()->sortBy('country_name');;
  $fast_track_applications =  fast_track_application::all()->sortBy('name');;
  $dosage_forms  = DosageForms::all()->sortBy('name');;
  $apis  = apis::all()->sortBy('api_name');;
  $route_administrations = route_administrations::all()->sortBy('name');
  $agents = agents::all()->sortBy('trade_name');
  $company_suppliers = company_suppliers::all()->sortBy('trade_name');

  $product_detailss =  product_details::all()->sortBy('product_name');

  $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

  $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
  $explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);

   $company_supplier_per_applicant = company_suppliers::where('application_id',$request->application_id)->get();
   $agents_template = agents_template::all()->sortBy('trade_name');


   $contact_detail_per_applicant_supplier = contacts::where('application_id',$request->application_id)
   ->where('contact_type','Supplier')
   ->get();

   $country_contact_info_supplier_info = DB::table('countries')->where('id',$contact_detail_per_applicant_supplier[0]->country_id)
   ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
   ->get();

   $contact_detail_per_applicant_agents = contacts::where('application_id',$request->application_id)
   ->where('contact_type','Supplier')
   ->get();

   $agent_detail_per_applicant = agents::where('application_id',$request->application_id)->get();


   $applications_status = DB::table('applications')
          ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
          ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
          ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
          ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
          ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
          ->leftjoin('countries', 'countries.id', '=', 'company_suppliers.country_id')
          ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
          ->select('countries.*','countries.id as countryid','applications.*',
           'invoices.*','contacts.*', 'invoices.amount',
            'medicines.product_name',
           'medicinal_products.product_trade_name',
           'manufacturers.addressline_one as manufacturer_addressline_one',
           'manufacturers.addressline_two as manufacturer_addressline_two',
           'manufacturers.name as manufacturer_name','company_suppliers.trade_name','company_suppliers.*',
           'company_suppliers.city as customer_city','company_suppliers.id as  company_suppliers_id',
           'company_suppliers.address_line_one as Supplier_line_one_address','company_suppliers.state as Supplier_state',
           'company_suppliers.address_line_two as Supplier_line_two_address','company_suppliers.telephone as telephone_supplier',
           'company_suppliers.email as email_supplier','company_suppliers.country_code as customer_country_code',
           'company_suppliers.postal_code as Supplier_postal_code', 'company_suppliers.telephone as tphone',
           'company_suppliers.webiste_url as  cs_webiste_url','invoices.invoice_number','invoices.remark',
           'contacts.first_name as cont_first_name','contacts.middle_name as cont_middle_name',
           'contacts.last_name as cont_last_name','contacts.id as cont_id', 
           'contacts.city as cont_city','contacts.address_line_one as cont_line_one_address',
           'contacts.address_line_two as cont_line_two_address','contacts.email as cont_email', 'contacts.fax as cont_fax', 
           'contacts.telephone as cont_telephone',/*'contacts.webiste_url as cont_url',*/
           'contacts.position as cont_position','contacts.city as cont_city'
            )
            ->where('contact_type','Supplier')
          ->where('applications.application_id',$request->application_id)
          ->orderBy('invoices.invoice_number','ASC')
          ->get();

          if(empty($applications_status[0]->cont_id) )
          {
$country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)->Orwhere('id',68)
                      ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                      ->get();

          }
          else if(!empty($applications_status[0]->cont_id) )
          {

         $country_contact_info = DB::table('countries')->where('id',$applications_status[0]->cont_id)
             //->Orwhere('id',68)
             ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
             ->get();


          }



$agent_contact_info = DB::table('applications')
                    ->leftjoin('agents', 'agents.application_id', '=', 'applications.application_id')
                    ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
                    ->leftjoin('countries', 'countries.id', '=', 'agents.country_id')
                    ->select('agents.*','contacts.*','agents.trade_name as business_name','agents.state as agent_state','agents.address_line_one as agent_address_line_one','agents.address_line_two as  agent_address_line_two','agents.city as agent_city','agents.postal_code as agent_postal_code','agents.telephone as agent_telephone','agents.webiste_url as agent_webiste_url','agents.email as agent_email','agents.country_code as agent_country_code','contacts.id as cont_id','agents.id as agent_id','contacts.first_name as cont_first','contacts.middle_name as cont_middle','contacts.last_name as cont_last','contacts.position as cont_position','contacts.city as cont_city','contacts.address_line_one as cont_line_one','contacts.address_line_two as cont_line_two','contacts.postal_code as cont_postal_code','contacts.telephone as cont_tele','contacts.fax as cont_fax','contacts.email as cont_email')
                     ->where('contact_type','Agent')
                     ->where('agents.application_id',$request->application_id)
                     ->get();



$agent_contact_County_info = DB::table('countries')->where('id','68')
                       ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                       ->get();


//Retriving  value form the medicical product Details

$product_details = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select(
'medicinal_products.id as _id',
'medicinal_products.medicine_id as medicine_id',
'medicinal_products.product_trade_name as product_trade_namme',
'medicines.product_name as medicine_product_name',
'route_administrations.name as route_name',
'dosage_forms.name as dosage_name',
'medicines.id as medicinal_id',
'route_administrations.id as route_id',
'dosage_forms.id as dosage_id',
'medicines.*',
'medicinal_products.*',
'route_administrations.*',
              'dosage_forms.*'
)
->where('medicinal_products.application_id',$request->application_id)
->get();



$product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id',$request->application_id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$request->application_id)->get();

$product_enlm_list = DB::table('applications')
                          ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                          ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                          ->where('applications.application_id',$request->application_id)
                          ->select('applications.*','medicines.*')
                          ->get();


$product_manufacturers_info = DB::table('manufacturers')
                                   ->leftjoin('countries', 'countries.id', '=', 'manufacturers.country_id')
                                   ->where('manufacturers.application_id',$request->application_id)
                                   ->select('manufacturers.id as mmid','manufacturers.*','countries.*','manufacturers.id as manufac_id',
                                   'manufacturers.name as manufac_name','manufacturers.id as manu_id')
                                   ->get();

$api_manufacturers_info = DB::table('api_manufacturers')
                             ->leftjoin('countries', 'countries.id', '=', 'api_manufacturers.country_id')
                            ->where('api_manufacturers.application_id',$request->application_id)
                            ->select(   'api_manufacturers.*','countries.*',
                                        'api_manufacturers.manufacturer_name as api_name',
                                        'api_manufacturers.id as api_id')
                            ->get();

@$decleration_info = DB::table('declarations')
->leftjoin('documents', 'documents.id', '=', 'declarations.document_attachment_id')
           ->where('declarations.application_id',$request->application_id)
                           ->select('declarations.*','documents.*','declarations.name as decname')
                           ->get();


@$docu_id = DB::table('documents')
 ->where('documents.id',$decleration_info[0]->document_attachment_id)
 ->select('documents.*')
 ->get();


$get_details_declarations = DB::table('medicinal_products')
->leftjoin('medicines', 'medicines.id', '=', 'medicinal_products.medicine_id')
->leftjoin('manufacturers', 'medicinal_products.application_id', '=', 'manufacturers.application_id')
->leftjoin('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
->leftjoin('route_administrations', 'route_administrations.id', '=', 'medicinal_products.route_administration_id')
->select('medicinal_products.id as _id','medicinal_products.medicine_id as medicine_id','medicinal_products.product_trade_name as product_trade_namme','medicines.product_name as medicine_product_name','route_administrations.name as route_name','dosage_forms.name as dosage_name','medicines.id as medicinal_id','route_administrations.id as route_id','dosage_forms.id as dosage_id','medicines.*','medicinal_products.*','route_administrations.*','dosage_forms.*','manufacturers.*','manufacturers.addressline_one as manufacturer_addressline_one','manufacturers.addressline_two as manufacturer_addressline_two','manufacturers.name as manufacturer_name',)
->where('medicinal_products.application_id',$request->application_id)
->first();


//dd($get_details_declarations);

       return view('application_reception.view_completed_application_re_registration',[

          'decleration_present'=> (@$decleration_info[0]->name =='')?0:1,
          'decleration_info'=> $decleration_info,
          'countries' => $countries,
          'fast_track_applications' =>$fast_track_applications,
          'dosage_forms'=>  $dosage_forms,
          'apis'=>  $apis,
          'route_administrations'=>$route_administrations ,
          'country_contact_info_supplier_info' => $country_contact_info_supplier_info ,
          'company_suppliers'=> $company_suppliers,
          'agents'=>$agents,
          'medicines'=>$product_detailss,
          'application_check_wizard'=>$application_check_wizard,
          'explode' =>  $explode,
          'company_supplier_per_applicant'=> $company_supplier_per_applicant,
          'supplier_name'=> (@$company_supplier_per_applicant[0]->trade_name =='')?0:1,
          'contact_detail_per_applicant_supplier'=> $contact_detail_per_applicant_supplier,
          'contact_detail_per_applicant_agents'=> $contact_detail_per_applicant_agents,
          'applications_status'  => $applications_status,
          'country_contact_info' => $country_contact_info,
          'agent_contact_County_info'=>$agent_contact_County_info,
          'agent_contact_info' => $agent_contact_info,
          'agent_name'=> (@$agent_contact_info[0]->trade_name =='')?0:1,
          'product_trade_name'=> (@$product_details[0]->product_trade_name =='')?0:1,
          'med_id'=> (@$product_details[0]->medicine_id =='')?0:1,
          'product_details_general'=> $product_details,
          'product_composition_info'=>$product_composition_info,
          'product_composition_name'=> (@$product_composition_info[0]->composition_name =='')?0:1,
          'manufacturers_name'=>(@$product_manufacturers_info[0]->manufac_name=='')?0:1,
          'product_manufacturers_info'=>$product_manufacturers_info,
          'company_suppliers_template' =>$company_suppliers_template,
          'agents_template'=>$agents_template ,
          'api_manufacturers_info' => $api_manufacturers_info,
          'api_name'=>(@$api_manufacturers_info[0]->manufacturer_name=='')?0:1,
          'docu_id' =>  @$docu_id,
          'get_details_declarations' => $get_details_declarations,
      ]);

}
//view_completed_application_re_registration






public function dossier_sample_status_edit(Request $request)
{

    //dd( $request->application_id );
    $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
    $application_number = $application_check_wizard[0]->application_number;
    foreach($application_check_wizard as $app)
    {
  $explode = explode(',', $app->hold_progress_wizard);

    }

 return view('dossier_status_sample.doosier_status_edit_applicant_info',
   ['application_id'=>$request->application_id,
   'application_check_wizard'=> $application_check_wizard,
   'application_number'=> $application_number,

   ]);



}



public function dossier_control_wizard(Request $request)
 {


  //dd( $request->application_id );
  $application_check_wizard  = applications::where('application_id',$request->application_id)->get();
  $application_number = $application_check_wizard[0]->application_number;
  foreach($application_check_wizard as $app)
  {
$explode = explode(',', $app->hold_progress_wizard);

  }



  return view('dossier_status_sample.dossier_status_check',
  [

    'application_id'=>$request->application_id,
    'application_number'=>$application_number,
  ]);


}



public function get_checklist_value(Request $request)
{


  $applications = DB::table('applications')
  ->where('application_id', '=',$request->application_id )
  ->select('applications.*')
  ->get();

foreach($applications as $application)
{


if(!empty($application->medical_product_id) )
{
$checked="checked";
}

$rendered_template =  "<div class='card-body'>
<!-- Minimal style -->
<div class='row'>
  <div class='col-sm-6'>
    <!-- checkbox -->
    <div class='form-group clearfix'>
<div class='icheck-primary d-inline'>
        <input type='checkbox' id='product_name' >
        <label for='checkboxPrimary3'>
          Product Information
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='company_supplier_name' >
        <label for='checkboxPrimary3'>
          Supplier Information
        </label>
      </div>
   </div>

      <div class='form-group clearfix'>
      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Manufacturer Infromation
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Agent Infomation
        </label>
      </div>
     </div>
<div class='form-group clearfix'>
      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Product Composition
        </label>
      </div>


     </div>
  </div>


<br><br>


    <div class='col-sm-6'>
    <!-- checkbox -->
    <div class='form-group clearfix'>



      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Sample Infromation
        </label>
      </div>

       <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Api product Information
        </label>
      </div>



    </div>
    <div class='form-group clearfix'>



      <div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Payment Information
        </label>
      </div>

<div class='icheck-primary d-inline'>
        <input type='checkbox' id='checkboxPrimary3' >
        <label for='checkboxPrimary3'>
          Dossier Information
        </label>
      </div>



    </div>

  </div>

</div>
</div>
</div>";


return response()->json(
  [

  'application_id'=>$request->application_id,
  'rendered_html' => $rendered_template,
  'Invoice_Created' => 'true',

  ]);



}





}






public function validate_email(Request $request  )
{
    $this->Email="";
    $company_suppliers = new company_suppliers;
    //dd( $request['Email']);
    $email = strtolower($request['Email']);
   $Check_Email = DB::select('select * from  company_suppliers where email = ?',  [$email] );
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( strtolower($this->Email) == strtolower($request['Email']) )
{

return response()->json([''=>""]);

//return response()->json(['error'=>"<i class='fa fa-exclamation-triangle fa-1'></i>Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";

}
else
{
 return response()->json(['success'=>'Good To go']);

}



  //  return view('user.index', ['users' => $users]);  validate_email_customer_contact
}



public function validate_email_customer_contact(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
    $email = strtolower($request['Email']);
   $Check_Email = DB::select('select * from  contacts  where email = ?', [$email]  );
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( strtolower($this->Email) == strtolower($request['Email']) )
{
  return response()->json(['success'=>'Good To go']);
// return response()->json(['error'=>"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> Error Email Already Registered</span>"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}



public function manufactrer_email(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from manufacturers  where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle fa-1'> </i>Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}


public function api_manufactrer_email(Request $request  )
{
    $this->Email="";
    $company_contacts = new contacts;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from    api_manufacturers where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
return response()->json(['error'=>"<i class='fa fa-exclamation-triangle'></i> Error Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";
}
else
{
 return response()->json(['success'=>'Good To go']);

}

}










public function validate_url(Request $request  )
{

 $url = $request->url;

 if (!filter_var($url, FILTER_VALIDATE_URL) === false)
{

  return response()->json(['Message'=>'1','success'=>"<i class='fa fa-check-circle'> $url is  a valid URL "]);

}
else if (!filter_var($url, FILTER_VALIDATE_URL) === true)
{

return response()->json(['Message'=>'0','error'=>"<i class='fa fa-exclamation-triangle'> $url is not  a valid URL"]);

}


}








public function validate_local_agent_email(Request $request  )
{
    $this->Email="";
    $company_suppliers = new agents;
    //dd( $request['Email']);
   $Check_Email = DB::select('select * from  agents where email = ?', [$request['Email']]);
   foreach ($Check_Email as $email)
   {
     $this->Email= $email->email;
   }


if( $this->Email == $request['Email'] )
{
  
//return response()->json(['error'=>"<i class='fa fa-exclamation-triangle fa-1'></i>Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";


return response()->json([''=>""]);


//return response()->json(['error'=>"<i class='fa fa-exclamation-triangle fa-1'></i>Email Already Registered"]);
//echo $Result = ($ResidentID=='Error')?"<span class='alert alert-danger'><i class='fa fa-exclamation-triangle'> </span>":"<span class='alert alert-success'><i class='fa fa-check'></span>";

}
else
{
 return response()->json(['success'=>'Good to go']);

}



  //  return view('user.index', ['users' => $users]);  validate_email_customer_contact
}










public function Request_for_all_sample()
{
    $countries = Country::all()->sortBy('country_name');;
    $fast_track_applications =  fast_track_application::all()->sortBy('name');;
    $dosage_forms  = DosageForms::all()->sortBy('name');;
    $apis  = apis::all()->sortBy('api_name');;
    //$route_administrations = route_administrations::all();
    $route_administrations = route_administrations::all()->sortBy('name');

    $agents = agents::all()->sortBy('trade_name');

    $agents_template = agents_template::all()->sortBy('trade_name');

    $company_suppliers = company_suppliers::all()->sortBy('trade_name');

    $company_suppliers_template = DB::select('select * from  company_supplier_template  where (is_Approved_By_NMFA = 1 )  order by trade_name  ASC');


    $product_details = DB::select('select * from  medicines where (is_enlm =1 and is_approved=1)  order by product_name ASC');
   // $product_details =  product_details::all()->sortBy('product_name');


         return view('app_recep',[
            'countries' => $countries,
            'fast_track_applications' =>$fast_track_applications,
            'dosage_forms'=>  $dosage_forms,
            'apis'=>  $apis,
            'route_administrations'=>$route_administrations ,
            'company_suppliers'=> $company_suppliers,
            'company_suppliers_template' =>  $company_suppliers_template,
            'agents'=>$agents,
            'agents_template'=>$agents_template,
            'medicines'=>$product_details,
        ]);
      }

    // method added by release two team
private function format_countdown_timer($remaining_time){

    $countdown_time_array = explode(' ', $remaining_time->format('%y %m %d %H %I'));
    //index 0 = year diff, index 1 = month diff, index 2 = day diff etc

    if($countdown_time_array[0] == 0 and $countdown_time_array[1] == 0 and $countdown_time_array[2] == 0)
        $remaining_time = $remaining_time->format('%Hhr %Imin');
    else if($countdown_time_array[0] == 0 and $countdown_time_array[1] == 0)
        $remaining_time = $remaining_time->format('%dd %Hhr %Imin');
    else if($countdown_time_array[0] == 0)
        $remaining_time = $remaining_time->format('%mm %dd %Hhr %Imin');
    else
        $remaining_time = $remaining_time->format('%yyr %mm %dd %Hhr %Imin');
    return $remaining_time;

}

}



