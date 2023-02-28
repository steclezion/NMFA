<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
use App\Models\uploaded_documents;
use App\Models\agents_template;
use App\Models\documents;
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
use App\Models\issue_queries;
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

use Illuminate\Support\Facades\Storage;
use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Events\ApplicationReceiptionEvent;


use PDF;
use DataTables;






class IssueQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    function __construct()

    {
  
  
      $this->middleware('permission:assessor_roles|application-list|application-status-lis');
      
    
     
  
    }


    public function index()
    {
        //
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


    public function delete_file_data_applicant(Request $request)
    {
             //dd($request->all());
         $documents = new documents;
         $documents= documents::find($request->document_id);
         $documents->delete();
       

     $path = public_path('storage/PreliminaryScreening/Document_Uploaded_To_Assessor/');
     $file = $path.$request->file_name;
    
     //delete a file 

    //  unlink($file ,'Its already deleting');
    // Storage::delete($file);

     $return_data='';

     $issue_queries = documents::join('issue_queries','issue_queries.PS_squential_number','documents.ref_num')
     ->select('issue_queries.*','documents.*','documents.id as document_id')
     // ->where('issue_queries.assessor_user_id','=',auth()->user()->id)
     ->where('documents.ref_num','=',$request->sequence_number)
     ->where('documents.document_type','=',14)
     ->get();

     // dd( $issue_queries);
     
     $i=1;
     foreach($issue_queries as $issue_queries)
            
     {
     
     $return_data .= "<tr><td>".$i++."</td>";
     $return_data .= "<td id='seqence_number_$issue_queries->PS_squential_number' >".$issue_queries->PS_squential_number."</td>";
     $return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
     $return_data .= "<td>".$issue_queries->dosage_form."</td>";
     $return_data .= "<td>".$issue_queries->strength."</td>";
     $return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
     $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
     }
     
     
     
     return response()->json(['Data_returned'=>$return_data ]);

    }



    public function delete_file_data_Assessor(Request $request)
    {

         $documents = new documents;
         $documents= documents::find($request->document_id);
         $documents->delete();
       

     $path = public_path('storage/PreliminaryScreening/Document_Uploaded_To_Assessor/');
     $file = $path.$request->file_name;
    
     //delete a file 

    //  unlink($file ,'Its already deleting');
    // Storage::delete($file);

     $return_data='';

     $issue_queries = issue_query::leftjoin('documents','documents.ref_num','issue_queries.PS_squential_number')
     ->select('issue_queries.*','documents.*','documents.id as document_id')
     ->where('issue_queries.assessor_user_id','=',auth()->user()->id)
     ->where('issue_queries.PS_squential_number','=',$request->sequence_number)
     ->where('documents.document_type','=',13)
     ->get();
     // dd( $issue_queries);
     
     $i=1;
     foreach($issue_queries as $issue_queries)
            
     {
     
     $return_data .= "<tr><td>".$i++."</td>";
     $return_data .= "<td id='seqence_number_$issue_queries->PS_squential_number' >".$issue_queries->PS_squential_number."</td>";
     $return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
     $return_data .= "<td>".$issue_queries->dosage_form."</td>";
     $return_data .= "<td>".$issue_queries->strength."</td>";
     $return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
     $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
     }
     
     
     
     return response()->json(['Data_returned'=>$return_data ]);

    }



  public function retrive_anwered_query_from_applicant(Request $request)
  {

//  dd($request->all());
$return_data='';

$issue_queries = issue_query::join('documents','documents.ref_num','issue_queries.PS_squential_number')
->select('issue_queries.*','documents.*')
->where('documents.ref_num','=',$request->sequence_number)
->where('documents.document_type','=',14)
->get();

// dd($issue_queries );

$i=1;
foreach($issue_queries as $issue_queries)
       
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td>".$issue_queries->PS_squential_number."</td>";
$return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
$return_data .= "<td>".$issue_queries->dosage_form."</td>";
$return_data .= "<td>".$issue_queries->strength."</td>";
$return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning'> <i class='fas fa-download'></i> Query </a> </td>";

}



return response()->json(['Data_returned'=>$return_data ]);



}




    public function issue_query_front(Request $request )
    {
        // dd($request->all());
   $id = $request->number_application; 

        $issue_query = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
        'medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name',
         DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$id)
        ->where('contacts.contact_type','Supplier')
        ->orderBy('invoices.invoice_number','ASC')
        ->get();


        $count = issue_query ::where('PS_squential_number', '<>', null)->count();
        $count_sequence = $count + 1;
        $year = Date('Y');
        $zero_filled_counter = sprintf('%04d', $count_sequence);  
        $random_application_PS_squential_number= 'NMFA/PS/'.$year."/".$zero_filled_counter;

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
        ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
        ->where('contact_type','Agent')
        ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->where('contact_type','Supplier')
        ->get();
        
$issue_queries_info = DB::table('issue_queries')->where('issue_queries.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


@$product_enlm_list = DB::table('applications')
               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
               ->where('applications.application_id',$id)
               ->select('applications.*','medicines.*','medicinal_products.*')
               ->get();


@$dosage_forms = DB::table('medicinal_products')
               ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
               ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
               ->select('dosage_forms.*','medicinal_products.*')
               ->get();


               
$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

$country_contact_info = DB::table('countries')->where('id',$issue_query[0]->country_id)->Orwhere('id',68)
                     ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                     ->get();


$country_contact_info = DB::table('countries')->where('id',$issue_query[0]->country_id)
    ->Orwhere('id',68)
    ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                ->get();

      
      $Assessor_issue_queries = DB::table('issue_queries')->where('application_id', $id)->get();



    if(  @$Assessor_issue_queries[0]->application_number != '')
    {
        @$select_document_id = DB::table('documents') ->where('id', $Assessor_issue_queries[0]->document_id)->get();
        @$path = $select_document_id[0]->path;

        // $assessor_sent_document_id = DB::table('documents')->where('id', $Assessor_issue_queries[0]->assessor_sent_document_id)->get();
        @$path_uploaded_query = $select_document_id[0]->path;



        $number_days_receipts = $Assessor_issue_queries[0]->number_days_receipts;

    }
    else
    {
        $path='';
        $path_uploaded_query ='';
        $number_days_receipts  = '';
    }




    
  return view('Preliminary_Screening_Queries.Preliminary_Screening_Queries',
    [
        'issue_query' =>  $issue_query,
        'country_contact_info' => $country_contact_info,
        'random_application_RL_squential_number'=> $random_application_PS_squential_number,
        'path' => $path,
        'path_uploaded_query' => $path_uploaded_query,
        'number_days_receipts' => $number_days_receipts ,
        'agent_contact_info' => $agent_contact_info,
        'issue_queries_info' => $issue_queries_info,
        'api_manufacturers_info' => $api_manufacturers_info,
        'product_enlm_list' => $product_enlm_list,
        'receipts_info' => $receipts_info,
        'dosage_forms' => $dosage_forms,
        'applicant_contact_info' => $applicant_contact_info,
        'invoice_number' => $invoice_number,
        'receipts_number' =>$receipts_number
    ]);
    
    
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
     * @param  \App\Models\issue_query  $issue_query
     * @return \Illuminate\Http\Response
     */
    public function show(issue_query $issue_query)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\issue_query  $issue_query
     * @return \Illuminate\Http\Response
     */
    public function edit(issue_query $issue_query)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\issue_query  $issue_query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, issue_query $issue_query)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\issue_query  $issue_query
     * @return \Illuminate\Http\Response
     */
    public function destroy(issue_query $issue_query)
    {
        //
    }



    public function issue_query($id)
    {

    $issue_query = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
        'medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name',
         DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$id)
        ->where('contacts.contact_type','Supplier')
        ->orderBy('invoices.invoice_number','ASC')
        ->get();


        $count = issue_query ::where('PS_squential_number', '<>', null)->count();
        $count_sequence = $count + 1;
        $year = Date('Y');
        $zero_filled_counter = sprintf('%04d', $count_sequence);  
        $random_application_PS_squential_number= 'NMFA/PS/'.$year."/".$zero_filled_counter;

$agent_contact_info = DB::table('agents')->where('agents.application_id',$id)
        ->leftjoin('contacts', 'agents.application_id', '=', 'contacts.application_id')
        ->where('contact_type','Agent')
        ->get();

$applicant_contact_info = DB::table('applications')->where('applications.application_id',$id)
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->where('contact_type','Supplier')
        ->get();
        
$issue_queries_info = DB::table('issue_queries')->where('issue_queries.application_id',$id)->get();

$receipts_info = DB::table('receipts')->where('receipts.application_id',$id)->get();


$api_manufacturers_info = DB::table('api_manufacturers')->where('api_manufacturers.application_id',$id)->get();


@$product_enlm_list = DB::table('applications')
               ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
               ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
               ->where('applications.application_id',$id)
               ->select('applications.*','medicines.*','medicinal_products.*')
               ->get();


                @$dosage_forms = DB::table('medicinal_products')
               ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
               ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
               ->select('dosage_forms.*','medicinal_products.*')
               ->get();

$invoice_number = DB::table('invoices')->where('invoices.application_id',$id)->get();

$receipts_number = DB::table('receipts')->where('receipts.application_id',$id)->get();

@$country_contact_info = DB::table('countries')->where('id',$issue_query[0]->country_id)->Orwhere('id',68)
                     ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                     ->get();


@$country_contact_info = DB::table('countries')->where('id',$issue_query[0]->country_id)
    ->Orwhere('id',68)
    ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                ->get();

      
      $Assessor_issue_queries = DB::table('issue_queries')->where('application_id', $id)->get();



    if(  @$Assessor_issue_queries[0]->application_number != '')
    {
        @$select_document_id = DB::table('documents') ->where('id', $Assessor_issue_queries[0]->document_id)->get();
        @$path = $select_document_id[0]->path;

        // $assessor_sent_document_id = DB::table('documents')->where('id', $Assessor_issue_queries[0]->assessor_sent_document_id)->get();
        @$path_uploaded_query = $select_document_id[0]->path;



        $number_days_receipts = $Assessor_issue_queries[0]->number_days_receipts;

    }
    else
    {
        $path='';
        $path_uploaded_query ='';
        $number_days_receipts  = '';
    }




    
  return view('Preliminary_Screening_Queries.Preliminary_Screening_Queries',
    [
        'issue_query' =>  $issue_query,
        'country_contact_info' => $country_contact_info,
        'random_application_RL_squential_number'=> $random_application_PS_squential_number,
        'path' => $path,
        'path_uploaded_query' => $path_uploaded_query,
        'number_days_receipts' => $number_days_receipts ,
        'agent_contact_info' => $agent_contact_info,
        'issue_queries_info' => $issue_queries_info,
        'api_manufacturers_info' => $api_manufacturers_info,
        'product_enlm_list' => $product_enlm_list,
        'receipts_info' => $receipts_info,
        'dosage_forms' => $dosage_forms,
        'applicant_contact_info' => $applicant_contact_info,
        'invoice_number' => $invoice_number,
        'receipts_number' =>$receipts_number
    ]);
    
    
}

    ////////////////////////////////////////////

public function save_issue_preliminary_screening(Request $request)
{


    try{
// dd($request->all());

        $Application_Number  = issue_query::where('application_number',$request->application_number)->get();
        if(@$Application_Number[0]->application_number != '0')
        {
            $PS_squential_number = $request->PS_squential_number;
            $time=time(); $date= date("Y-m-d",$time);

     $issue_query = new issue_query;
     $issue_query->application_id = $request->application_id;
     $issue_query->PS_squential_number= $PS_squential_number;
     $issue_query->date=  $date;
     $issue_query->applicant_name= $request->applicant_name;
     $issue_query->region_state= $request->state_plot_number;
     $issue_query->region_state= $request->country;
     $issue_query->strength=$request->stregnth;
     $issue_query->remarks=$request->remark;
     $issue_query->Name_of_the_product=$request->product_name;
     $issue_query->dosage_form=$request->dosage_forms;
     $issue_query->Brand_name=$request->product_trade_name;
     $issue_query->contact_person_name= $request->contact_person_name;
     $issue_query->application_number= $request->application_number;
     $issue_query->number_days_receipts = $request->number_days_receipts;
     $Application_ = $issue_query->save();

      

       if ( $Application_ == true)    
       {
           //This section uses to create the documents from the dom pdf package downloaded from 
   
         $rendered_html_data = $this->rendered_html_data($request->application_number);
         $pdf = PDF::loadHTML($rendered_html_data);
         $pdf->setPaper ('A4', 'portrait');
      // <--- load your view into theDOM wrapper;
      $PS_squential_number=  str_ireplace("/","_",$PS_squential_number);
  
      $time=time();
      $path = public_path('storage/PreliminaryScreening/System_Generated_Documents/');
      // <--- folder to store the pdf documents into the server;
      $fileName =  $PS_squential_number.$time."-".'.pdf' ; // <--giving the random filename,
      $pdf->save($path.$fileName);
      
      $generated_pdf_link = Storage::url('public/PreliminaryScreening/System_Generated_Documents/'.$fileName);

        //$generated_pdf_link = Storage::url($path.$fileName);
       //Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '11';
$documents->ref_num = $request->PS_squential_number;
$documents->description = '--';
$documents->save();

         // Uses to insert data in to the Document Selections
$select_document_id = DB::table('documents')->where('name', $fileName)->get();

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();
   
// Uses to insert data in to the Acknowledgment Letter
 $update_query_issue = DB::table('issue_queries')
->where('issue_queries.application_number', $request->application_number)
->update(['document_id' => $select_document_id[0]->id]);

return response()->json([ 'Message'=>true, 
                           'Applicant_Number'=>$request->application_number,
                            'Download_link' =>$select_document_id[0]->path,
                            'save_preliminary_screening/save' => $PS_squential_number
                         ]);


}
       else
       {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);

       }
       
  }
  else
  {


    
   return response()->json(['Message'=>false,'item'=>'error']);

  }
  
    
        }
        catch(Exception $e)
        {
        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }

        





        
}

    











public function rendered_html_data($application_number)
{

   
  
  $path_header = "images/nmfa_header.png";
  $path_footer = "images/nmfa_footer.png";
  
$issue_query  = issue_query::where('application_number',$application_number)->get();


//$applications = applications::create($request->all());
$issue_query  = issue_query::where('application_number',$application_number)->get();
  
foreach($issue_query   as $checked_issued_query) {

  $random_application_PS_squential_number =  $checked_issued_query['PS_squential_number'];
  $number_days_receipts =  $checked_issued_query['number_days_receipts'];
  $date  =  $checked_issued_query['date'];
  $fullname_contact  =  $checked_issued_query['contact_person_name'];
  $address  =  $checked_issued_query['region_state'];
  $strength =  $checked_issued_query['strength'];
  $Name_of_the_product =   $checked_issued_query['Name_of_the_product'];
  $dosage_form =  $checked_issued_query['dosage_form'];
  $Brand_name =  $checked_issued_query['Brand_name'];
  $remark =  $checked_issued_query['Remarks'];


}

$rendered_template = "
  <!DOCTYPE html>
  <html lang='en-US'>
      <head>
     </head>

     <!-- Main content -->
            <div class='invoice p-3 mb-3'>
              <!-- title row -->
              <div class='row'>
                <div class='col-12'>
                  <h4>
                 
                  <img src='".$path_header."'  alt='image' height='100' width='690'/>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
  <div class='row invoice-info'>


<div class='container'> 
<p class='list-group'>
<div class='panel panel-default'>

<div class='panel-heading'  >Date: <span id='current_date'>  $date  </span> </div>
<br/>
<div class='panel-body'  >Ref: <span id='PS_squential_number'>  $random_application_PS_squential_number  </span>  </div>
<br/>
<div class='panel-body'>
To: <span id='applicant_name'>  $fullname_contact  </span>  <br/>
<ul>
  <li> <span id='state_plot_number'>   $address </span> </li> <br/>
  

</ul>
</div>
</div>
</p>

<style>
p,block {
  text-align: justify;
}
</style>
   


<b> Subject: Preliminary Screening Queries</b> 
<br/><br/>
<block style='text-align: justify;'>
<p>Dear Sir/Madam or  <span id='contact_person_name'>  $fullname_contact </span> ,</p>
<br/>

This is to inform you that preliminary assessment of 
<u> 
<b>
<span id='steng'> $strength   </span>
<span id='product_name'> $Name_of_the_product   </span>
<span id='dosage_forms'>  $dosage_form   </span>
<span id='product_trade_name'> $Brand_name </span>
</b>
</u>

has been completed. The assessment of your application indicates the deficiencies listed
below which you are requested to address for further processing: 
<br> &nbsp;&nbsp;&nbsp;
$remark
<br>
Note that if your response is not received within

$number_days_receipts 

days the application will be considered closed and you may be required to re-apply
if you wish to continue with the application. 
The evaluation process will not start until the above-mentioned queries are addressed.



</block>
<br/><br/>
<p> Best regards,  </p>

Iyassu Bahta
<br>
Director, National Medicines and Food Administration <br>
Ministry of Health <br>
Asmara, Eritrea

              </div>
              <br><br>
  <p>
              <br><br>
  <p>
<img src='".$path_footer."'  height='100' width='690'/>       
</p>        
              </div>

         

  </body>
</html>
  ";

  return $rendered_template;
}



//upload_file_issued_query_front_section



public function upload_file_issued_query(Request $request)
{
      //dd($request->all());
     try
     {
    $validatedData = $request->validate([
    //  'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
    'file' => 'required|mimes:pdf,docx,doc|max:2048',
// Validate that an uploaded file is exactly 512 kilobytes...
//   'file' => 'file<size:512'

    ]);
 

    $name = $request->file('file')->getClientOriginalName();

    // $size = Storage::size( $request->file('file')  );
    // dd($size);

    $time=time();
    // $path = $request->file('file')->store('public/images');
    $path = public_path('storage/PreliminaryScreening/Document_Uploaded_To_Applicant/');

    // <--- folder to store the pdf documents into the server;
    $fileName =  $name."-".$request->app_name.$time.'.pdf' ; // <--giving the random filename,
    $reference_number = $name."-".$request->app_name;
    $filePath = $request->file('file')->storeAs('PreliminaryScreening/Document_Uploaded_To_Applicant/', $fileName, 'public');

    $generated_pdf_link = Storage::url('public/PreliminaryScreening/Document_Uploaded_To_Applicant/'.$fileName);



//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '13';
$documents->ref_num = $request->application_number;
$documents->description = 'Upload Sealed  Preliminary Screening To Applicant';
$documents->save();



$update_applications = DB::table('issue_queries')
->where('issue_queries.application_number', $request->application_number)
->update([ 
'applicant_user_id' => $request->applicant_user_id,
'assessor_user_id' => $request->assesor_user_id 

]);


return response()->json(['Message'=>true,'Download_Link'=>$documents->path]);
     }

     catch(Exception $e)
     {

        return response()->json(['Message'=>false,'item'=>'error'.$e]);

     }



}




public function get_issued_queries(Request $request)
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

    
    $issue_queries = issue_query::join('documents','documents.id','issue_queries.document_id')
    ->join('applications','applications.application_id','issue_queries.application_id')
    ->select('issue_queries.*','applications.*','documents.*')
    ->where('issue_queries.applicant_user_id','=',auth()->user()->id)
    ->get();

   

        return view('Preliminary_Screening_Queries.Preliminary_Screening_Queries_applicant',[
         'countries' => $countries,
         'fast_track_applications' =>$fast_track_applications,
         'dosage_forms'=>  $dosage_forms,
         'apis'=>  $apis,
         'route_administrations'=>$route_administrations ,
         'company_suppliers'=> $company_suppliers,
         'agents'=>$agents,
         'medicines'=>$product_details,
         'applications' => $issue_queries,
      
     ]);




}


public function issue_queries(Request $request)
{
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
    ->join('issue_queries','issue_queries.application_id','applications.application_id')
    //->join('users','users.id','applications.user_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->distinct()
    ->select('checklists.*','checklists.application_id as check_app','applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*','issue_queries.*',
    'company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name',
    'contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    // ->where('applications.user_id',auth()->user()->id)
    ->where('applications.assigned_To','=',auth()->user()->id)
    ->where('issue_queries.document_id','!=','')
    ->get();
   

    $applications_front = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
    ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    
    //->join('users','users.id','applications.user_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->distinct()
    ->select('checklists.*','checklists.application_id as check_app','applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*',
    'company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name',
    'contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    // ->where('applications.user_id',auth()->user()->id)
    ->where('applications.assigned_To','=',auth()->user()->id)
    ->get();

    $issue_queries = issue_query::join('documents','documents.id','issue_queries.document_id')
    ->join('applications','applications.application_id','issue_queries.application_id')
    ->select('issue_queries.*','applications.*','documents.*')
    ->where('issue_queries.assessor_user_id','=',auth()->user()->id)
    ->where('issue_queries.application_id','=',auth()->user()->id)
    ->get();



       return view('Preliminary_Screening_Queries.Preliminary_Screening_Assessor',[
        'countries' => $countries,
        'fast_track_applications' =>$fast_track_applications,
        'dosage_forms'=>  $dosage_forms,
        'apis'=>  $apis,
        'route_administrations'=>$route_administrations ,
        'company_suppliers'=> $company_suppliers,
        'agents'=>$agents,
        'medicines'=>$product_details,
        'applications' =>  $applications,
        'issue_queries' => $issue_queries,
        'applications_front' => $applications_front,
     
    ]);




}




//upload_file_issued_query_front_section

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

public function upload_file_issued_query_from_front_section(Request $request)
{
          //dd($request->all());
        $return_data ='';
     try
     {
    $validatedData = $request->validate([
    //  'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
    'file' => 'required|mimes:pdf,docx,doc|max:2048',
// Validate that an uploaded file is exactly 512 kilobytes...
//   'file' => 'file<size:512'

    ]);
 

    $name = $request->file('file')->getClientOriginalName();

    // $size = Storage::size( $request->file('file')  );
    // dd($size);

    $time=time();
    // $path = $request->file('file')->store('public/images');
    $path = public_path('storage/PreliminaryScreening/Document_Uploaded_To_Applicant/');

    // <--- folder to store the pdf documents into the server;
    $number_squence= str_replace("/","_",$request->sequence_number);
    $fileName =  $name."-". $number_squence.$time.'.pdf' ; // <--giving the random filename,
    $reference_number = $name."-". $number_squence;
    $filePath = $request->file('file')->storeAs('PreliminaryScreening/Document_Uploaded_To_Applicant/', $fileName, 'public');

    $generated_pdf_link = Storage::url('public/PreliminaryScreening/Document_Uploaded_To_Applicant/'.$fileName);



//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '13';
$documents->ref_num = $request->sequence_number;
$documents->description = 'Upload Sealed  Preliminary Screening To Applicant';
$documents->save();



/************************* */

$application=applications::where('application_number',$request->application_number)->first();
/*$query=issue_queries::where('application_number',$request->application_number)
->where('PS_squential_number',$request->sequence_number)
->first();  */        
$task=TaskTracker::where('related_id',$request->sequence_number)->first();
$main_task = $this->get_main_task_id($application->id,'Application');

//$issue_k = issue_query::where('PS_squential_number',$request->sequence_number);

$issue_queries = issue_query::select('issue_queries.*')
->where('PS_squential_number','=',$request->sequence_number)
->first();



$duration_days=$issue_queries->number_days_receipts; 

$end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
$issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
$task_category = 'Application';
$task_activity_title = 'Query';
$content_details = 'Assessor issued  query for  applicant ';
$route_link = '';
$activity_status = 'Inprogress';
$uploaded_document_id = $documents->id;
$related_id =$request->sequence_number;
if($task==null)
{
MainTaskController::insertActivitywithQuery($main_task->id, $issued_datetime, $end_time,
$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id,$related_id);
}
$user=User::where('id',$request->applicant_user_id)->first();
$assesor=User::where('id',$request->assesor_user_id)->first();

$new_notification=[];
$new_notification['type'] = 'Notification';
$new_notification['subject'] ='Query for Applicant';
$new_notification['from_user'] = 'Assessor';
$new_notification['data'] = 'Assessor has issued query during  application screening  for application no '. $request->application_number;
$new_notification['related_document'] = null;
$new_notification['related_id'] = $request->application_number;
$new_notification['alert_level'] = null;
$new_notification['remark'] = null;


Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'Assessor has issued query while screening  for application no '. $request->application_number));

/**************************************** */

$update_applications = DB::table('issue_queries')
->where('issue_queries.application_number', $request->application_number)
->update([ 
'applicant_user_id' => $request->applicant_user_id,
'assessor_user_id' => $request->assesor_user_id 
]);




$issue_queries = issue_query::leftjoin('documents','documents.ref_num','issue_queries.PS_squential_number')
->select('issue_queries.*','documents.*','documents.id as document_id')
->where('issue_queries.assessor_user_id','=',auth()->user()->id)
->where('issue_queries.PS_squential_number','=',$request->sequence_number)
  ->where('documents.document_type','=',13)
->get();


// dd($issue_queries);

$i=1;
foreach($issue_queries as $issue_queries)
       
{

$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td>".$issue_queries->application_number."</td>";
$return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
$return_data .= "<td>".$issue_queries->dosage_form."</td>";
$return_data .= "<td>".$issue_queries->strength."</td>";
$return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning'> <i class='fas fa-download'></i> Query </a> </td>";
$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    


}



return response()->json(['Message'=>true, 'Download_Link'=> $documents->path,'Data_returned'=>$return_data ]);
     }

     catch(Exception $e)
     {

        return response()->json(['Message'=>false,'item'=>'error'.$e]);

     }



}






public function upload_file_issued_query_from_applicant_to_assessor(Request $request)
{
          //dd($request->all());
        $return_data ='';
     try
     {
    $validatedData = $request->validate([
    //  'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
    'file' => 'required|mimes:pdf,docx,doc|max:2048',
// Validate that an uploaded file is exactly 512 kilobytes...
//   'file' => 'file<size:512'

    ]);
 

    $name = $request->file('file')->getClientOriginalName();

    // $size = Storage::size( $request->file('file')  );
    // dd($size);

    $time=time();
    // $path = $request->file('file')->store('public/images');
    $path = public_path('storage/PreliminaryScreening/Document_Uploaded_To_Assessor/');
    $number_squence= str_replace("/","_",$request->sequence_number);
    // <--- folder to store the pdf documents into the server;
    $fileName =  $name."-".$number_squence.$time.'.pdf' ; // <--giving the random filename,
    $reference_number = $name."-".$number_squence;
    $filePath = $request->file('file')->storeAs('PreliminaryScreening/Document_Uploaded_To_Assessor/', $fileName, 'public');
    $generated_pdf_link = Storage::url('public/PreliminaryScreening/Document_Uploaded_To_Assessor/'.$fileName);



//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '14';
$documents->ref_num = $request->sequence_number;
$documents->description = 'Upload Sealed  Preliminary Screening To Assessor';
$documents->save();

TaskTracker::where('related_id', $request->sequence_number)->update([
  'activity_status' => 'Completed'    
]);



$user=User::where('id', $request->applicant_user_id)->first();


     $new_notification=[];
     $new_notification['type'] = 'Notification';
      $new_notification['subject'] ='Screening Issue Query Response';
      $new_notification['from_user'] = 'System Reminder';
      $new_notification['data'] = 'Applicant  has responded for  application screening  Query  '. $request->application_number.' ';
      $new_notification['related_document'] = null;
      $new_notification['related_id'] = $request->application_number;
      $new_notification['alert_level'] = null;
      $new_notification['remark'] = null;

Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'Applicant  has responded for Query reagarding application no : '. $request->application_number.' '));



$return_data='';

$issue_queries = issue_query::leftjoin('documents','documents.ref_num','issue_queries.PS_squential_number')
->select('issue_queries.*','documents.*','documents.id as document_id')
->where('issue_queries.PS_squential_number','=',$request->sequence_number)
->where('documents.document_type','=',14)
->get();
// dd( $issue_queries);

$i=1;
foreach($issue_queries as $issue_queries)
       
{

$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td>".$issue_queries->PS_squential_number."</td>";
$return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
$return_data .= "<td>".$issue_queries->dosage_form."</td>";
$return_data .= "<td>".$issue_queries->strength."</td>";
$return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    


}



return response()->json(['Message'=>true, 'Download_Link'=> $documents->path,'Data_returned'=>$return_data ]);
     }

     catch(Exception $e)
     {

        return response()->json(['Message'=>false,'item'=>'error'.$e]);

     }



}



//retrive_issued_query_from_applicant

public function  retrive_issued_query_from_applicant(Request $request)
{
    //dd($request->all());
    $return_data='';

    $issue_queries = documents::join('issue_queries','issue_queries.PS_squential_number','documents.ref_num')
    ->select('issue_queries.*','documents.*','documents.id as document_id')
    // ->where('issue_queries.assessor_user_id','=',auth()->user()->id)
    ->where('documents.ref_num','=',$request->sequence_number)
    ->where('documents.document_type','=',14)
    ->get();

    //dd( $issue_queries);
    
    $i=1;
    foreach($issue_queries as $issue_queries)
    {

    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .= "<td id='seqence_number_$issue_queries->PS_squential_number'>".$issue_queries->PS_squential_number."</td>";
    $return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
    $return_data .= "<td>".$issue_queries->dosage_form."</td>";
    $return_data .= "<td>".$issue_queries->strength."</td>";
    $return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
    $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
    
   }
    
    
    
    return response()->json(['Data_returned'=>$return_data ]);



}









public function retrive_issued_query_from_front_section(Request $request)
{
    //dd($request->all());
    $return_data='';

    $issue_queries = documents::join('issue_queries','issue_queries.PS_squential_number','documents.ref_num')
    ->select('issue_queries.*','documents.*','documents.id as document_id')
     ->where('issue_queries.assessor_user_id','=',auth()->user()->id)
    ->where('documents.ref_num','=',$request->sequence_number)
    ->where('documents.document_type','=',13)
    ->get();

    //dd( $issue_queries);
    
    $i=1;
    foreach($issue_queries as $issue_queries)
           
    {
    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .= "<td id='seqence_number_$issue_queries->PS_squential_number'>".$issue_queries->PS_squential_number."</td>";
    $return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
    $return_data .= "<td>".$issue_queries->dosage_form."</td>";
    $return_data .= "<td>".$issue_queries->strength."</td>";
    $return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
    $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
    }
    
    
    
    return response()->json(['Data_returned'=>$return_data ]);



}


public function retrive_issued_query_from_assessor(Request $request)
{


    //dd($request->all());
    $return_data='';

    $issue_queries = documents::join('issue_queries','issue_queries.PS_squential_number','documents.ref_num')
    ->select('issue_queries.*','documents.*','documents.id as document_id')
     ->where('issue_queries.applicant_user_id','=',auth()->user()->id)
    ->where('documents.ref_num','=',$request->sequence_number)
    ->where('documents.document_type','=',13)
    ->get();

    //dd( $issue_queries);
    
    $i=1;
    foreach($issue_queries as $issue_queries)
           
    {
    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .= "<td id='seqence_number_$issue_queries->PS_squential_number'>".$issue_queries->PS_squential_number."</td>";
    $return_data .= "<td>".$issue_queries->Name_of_the_product."</td>";
    $return_data .= "<td>".$issue_queries->dosage_form."</td>";
    $return_data .= "<td>".$issue_queries->strength."</td>";
    $return_data .= "<td> <a href='".$issue_queries->path."'  id='get_path' rel='noopener' target='_blank' class='btn btn-warning btn-sm'> <i class='fas fa-download'></i> Query </a> </td>";
    // $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-file_name='$issue_queries->name' data-id='$issue_queries->document_id'  data-di='$issue_queries->PS_squential_number'  data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
    }
    
    
    
    return response()->json(['Data_returned'=>$return_data ]);

}






}
