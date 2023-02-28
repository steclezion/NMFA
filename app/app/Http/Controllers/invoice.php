<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\receipt;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\application_receipt_of_registration;
use App\Models\Country;
use App\Models\payment_configuration;
use App\Models\invoices;
use App\Models\declerations;
use App\Models\documents;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Config;
use App\Models\TaskTracker;
use App\Events\ApplicationReceiptionEvent;
use Illuminate\Support\Facades\Notification;

use Hash;
use DataTables;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\MainTask;
use App\Models\Acknowledgement_letter;
use App\Http\Controllers\MainTaskController;






class invoice extends Controller
{






    /**
     * Display a listing of the resource.
     *
     * @return Response
     * 
     * 
     * 
     * 
     */

    public function __construct()

    {
   
   $this->middleware('permission:assessor_roles');
   
    }

    
    public function generate_invoices(Request $request)
{
     $invoices = new invoices();

    $application_id =  DB::table('applications')
    ->join('users','users.id','applications.user_id')
    ->join('countries','countries.id','users.country_id')
    ->distinct('application_id')->get();
        return view('invoices.invoice',[

            'application_id' =>  $application_id,

        ]);



}


    public function index(Request $request)
    {
        //
// dd(auth()->user()->id);

        if ($request->ajax())
        {
                   // $data = applications::latest()->get();
    $application_check_complete = applications::all()->sortBy('hold_progress_wizard');
     foreach($application_check_complete  as $application_check_complete )

      {
// If the applications reception phase i data completetion wizard is finished then crossing checking the progress status the invoice module will be generated
//only application whose application level is at 8th level can put to the invoice page
//dd(in_array('8',$explode));

$explode = explode(',', $application_check_complete->hold_progress_wizard);
  if(in_array('8',$explode))
                   {
             $data = DB::table('applications')
            // ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->select('applications.*', 'medicines.product_name','medicinal_products.product_trade_name',
           'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
            ->whereNotNull('company_suppliers.trade_name')
            // ->whereNotNull('applications.dossier_id')
            ->where('applications.assigned_To','=',auth()->user()->id)
          //  ->whereNull('invoices.invoice_number')
          //  ->orwhereNotNull('invoices.invoice_number')
            ->orderBy('applications.application_number','ASC')
            ->get();

     return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function($row){
                                $invoices = DB::table('invoices')
                                ->where('application_id', '=',$row->application_id )
                                ->select('invoices.*')
                                ->get();

                                if(empty($invoices[0]->invoice_number))
{

$btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Generate Invoice" data-id="'.$row->application_id.'"  data-app_number = "'.$row->application_number.'"  data-original-title="Edit"  class="edit btn btn-primary btn-sm editBook">   <i class="fas fa-file-invoice-dollar"></i></a><br>';

}

else

{ 

  $show_data = DB::table('invoices')
  ->leftjoin('documents', 'invoice_document_id', '=', 'documents.id')
   ->select('invoices.*','documents.*')
   ->where('invoices.application_id','=',$row->application_id)
  ->get();
  
    $btn = '<a href="'.$show_data[0]->path.'" title="Download Invoice" class="edit btn btn-success btn-sm"> <i class="fas fa-download"></i></a><br/>';


    $btn .= '<br><a href="javascript:void(0)" data-toggle="tooltip" title="Upload Invoice" 
    data-id="'.$row->application_id.'" 
    data-application_number="'.$row->application_number.'" 
    data-original-title="Edit" 
   
   class="edit btn btn-primary btn-sm edituploadinvoice">   <i class="fas fa-upload"></i></a> <br>';

}
            //$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';

            return $btn;

                            })
                   ->addColumn('application_type', function($row){
                        if($row->application_type==1) {$application_type=" Standard Mode";}
                        else {$application_type=$row->fast_track_details;}
                      return $application_type;

                          })
                            ->rawColumns(['action','application_type'])
                            ->make(true);
        }
    }


  }

    //return  $data;
    return view('invoice.invoices');
    }





public function upload_invoice_letter(Request $request)
{
       // dd($request->all());
try
     {
   
    $validatedData = $request->validate([
    //  'file' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
    'file' => 'required|mimes:pdf,docx,doc|max:204800',
// Validate that an uploaded file is exactly 512 kilobytes...
//   'file' => 'file<size:512'

    ]);
 
    $name = $request->file('file')->getClientOriginalName();
    // $size = Storage::size( $request->file('file')  );
    // dd($size);
   $time=time();
    // $path = $request->file('file')->store('public/images');
    $path = public_path('storage/invoices/Uploaded_to_applicant/');
    // <--- folder to store the pdf documents into the server;
    $fileName =  $name."-".$request->application_id.$time.'.pdf' ; // <--giving the random filename,
    $reference_number = $name."-".$request->app_number;
    $filePath = $request->file('file')->storeAs('invoices/Uploaded_to_applicant/', $fileName, 'public');

    $generated_pdf_link = Storage::url('public/invoices/Uploaded_to_applicant/'.$fileName);

   // dd($filePath.$generated_pdf_link );

//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '21';
$documents->ref_num = $reference_number;
$documents->description = 'Upload Sealed invoice letter To Applicant';
$documents->save();

//applicant_user_id
$get_applicant_user_id =  DB::table('applications')
->where('applications.application_id', $request->application_id)
->get();

$update_applications = DB::table('invoices')
->where('invoices.application_id', $request->application_id)
->update([ 
'uploaded_invoice_document_id' => $documents->id,
'applicant_user_id' => $get_applicant_user_id[0]->user_id,

]);


$doc_upload_invoice_document  = invoices::join('documents',
'documents.id','invoices.uploaded_invoice_document_id')
->select('documents.*','invoices.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('invoices.application_id','=',$request->application_id)
->where('documents.document_type','=',21)
->get();

//dd( $issue_queries);


$i=1;   $return_data='';
foreach($doc_upload_invoice_document as $user_upload)
    
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td id='seqence_number_$user_upload->id' >

<a  href='".$documents->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>


</td>";
$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-document_id='$user_upload->did' 
data-id='$request->application_id' 
data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
}



/************************* */
$application=applications::where('application_id',$request->application_id)->first();

$main_task = $this->get_main_task_id($application->id,'Application');

$duration_days=30;
$end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
$issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
$task_category = 'Payment';
$task_activity_title = 'Invoice';
$content_details = 'Application invoice has been issued.';
$route_link = '';
$activity_status = 'Inprogress';
$uploaded_document_id = $documents->id;
MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
/*
$tasks = TaskTracker::where('task_id', $main_task->id)
->where('task_category','Payment')
->first();


application_evaluation_progresses::insert([
'application_id'=>$application->id,
'task_id' =>$tasks->id,
]);*/


$user=User::where('id',$application->user_id)->first();
$new_notification=[];
                $new_notification['type'] = 'Notification';
                $new_notification['subject'] ='Application Invoice';
                $new_notification['from_user'] = 'System Reminder';
                $new_notification['data'] = 'Assessor issued  invoice for applicant`s  application:'.$application->application_number;
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $application->application_number;
                $new_notification['alert_level'] = null;
                $new_notification['remark'] = null;
              // ::send($users, new ($invoice));


              Notification::send($user, new ApplicationReceiptionNotification($new_notification));
              event(new ApplicationReceiptionEvent($user->id, ' New application  invoice  notification has been issued by assessor for application No: '.$application->application_number ));


       

/************************* */


return response()->json(['Message'=>true,'Download_Link'=>$documents->path,'Data_returned'=>$return_data ]);


     }

     catch(Exception $e)
     {

        return response()->json(['Message'=>false,'item'=>'error'.$e]);

     }



  

}


public function fetch_uploaded_invoice_letter(Request $request)
{


  $doc_upload_invoice_document  = invoices::join('documents',
  'documents.id','invoices.uploaded_invoice_document_id')
  ->select('documents.*','invoices.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('invoices.application_id','=',$request->application_id)
  ->where('documents.document_type','=',21)
  ->get();
  
  //dd( $issue_queries);
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_invoice_document as $user_upload)
      
  {
  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
  
  
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
  data-document_id='$user_upload->did' 
  data-id='$request->application_id' 
  data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
  }
  
  
  
  return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);
  
 
}



public function delete_file_uploaded_invoice_letter(Request $request)
    {
        // dd($request->all());
         $documents = new documents;
         $documents= documents::find($request->document_id);
         $documents->delete();


 $update_applications = DB::table('invoices')->where('invoices.application_id', $request->application_id)->update(['uploaded_invoice_document_id' => 0]);
 $doc_upload_invoice_document  = invoices::join('documents','documents.id','invoices.uploaded_invoice_document_id')->
 select('documents.*','invoices.*','documents.name as dname',
 'documents.created_at as uploaded_Date', 'documents.id as did')->where('invoices.application_id','=',$request->application_id)
 ->where('documents.document_type','=',21)
 ->get();
  
  
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_invoice_document as $user_upload)
      
  {
  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
  
  
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
  data-document_id='$user_upload->did' 
  data-id='$request->application_id' 
  data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
  }
  
  if($return_data == '')  {$return_data = '<th colspan="4" style="text-align:center"> No Data </th>'; 

    $update_applications = DB::table('invoices')
    ->where('invoices.application_id', $request->application_id)
    ->update([ 
    'uploaded_invoice_document_id' => $documents->id,
    'applicant_user_id' => 0,
    
    ]);
   } 
  
  return response()->json(['Message'=>true,'Download_Link'=>'','Data_returned'=>$return_data ]);
  


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


    public function view(Request $request)
    {
        //

        if ($request->ajax())
        {
                   // $data = applications::latest()->get();
                   $application_check_complete = applications::all()->sortBy('hold_progress_wizard');
                   $explode = explode(',', $application_check_complete[0]->hold_progress_wizard);



                    /*$data= applications::all()->sortBy('hold_progress_wizard')
                    ->join('manufacturers','manufacturers.user_id','applications.user_id')
                    ->join('medicinal_products','medicinal_products.id','applications.medical_product_id')
                    ->join('company_suppliers','company_suppliers.user_id','applications.user_id')
                    ->join('users','users.id','applications.user_id');*/

              $data = DB::table('applications')
            ->join('manufacturers', 'applications.user_id', '=', 'manufacturers.user_id')
            ->join('medicinal_products', 'applications.user_id', '=', 'medicinal_products.user_id')
            ->join('company_suppliers','applications.user_id','=','company_suppliers.user_id')
            ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
             ->leftjoin('medicines', 'medicinal_products.id', '=', 'medicines.id')
            ->select('applications.*', 'medicinal_products.product_trade_name', 'manufacturers.name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
            ->get();



//dd($data);



                    return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function($row){

            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" 
            data-app_number = "'.$row->application_number.'" 

            data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Generate Invoice</a><br>';

            $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';

            return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }

                return view('invoice.invoices');
    }


      /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function htmlPdf()
    {
        // selecting PDF view
        $pdf = PDF::loadView('html-Pdf');

        // download pdf file
        return $pdf->download('pdfview.pdf');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
   public function   create(Request  $request )
{
try{
  //dd($request->all());
  $pdf = App::make('dompdf.wrapper');
  $rendered_html_data = $this->rendered_html_data($request->application_id,$request->invoice_number,$request->remark);

  $pdf = PDF::loadHTML($rendered_html_data);
  $pdf->setPaper ('A4', 'portrait');

  // <--- load your view into theDOM wrapper;
  $explode_name =explode("/",$request->invoice_number);
  // $path = public_path('Generated_Invoice_PDFS'); // <--- folder to store the pdf documents into the server;
  $path = public_path('storage/invoices/');

  $fileName =  $explode_name[0].$explode_name[1].$explode_name[2].$explode_name[3].'.'. 'pdf' ; // <--giving the random filename,

  $save_path= $pdf->save($path.$fileName);
  //$generated_pdf_link = Storage::path('Generated_Invoice_PDFS/'.$fileName);
  $generated_pdf_link = Storage::url('invoices/'.$fileName);


  $data = DB::table('applications')
  ->where('applications.application_id', '=',$request->application_id )
  ->join('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
  ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
  ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
  ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
  ->select('applications.*', 'medicinal_products.product_trade_name', 'manufacturers.name',
  'company_suppliers.trade_name as Tname','invoices.invoice_number','invoices.remark','invoices.amount')
  ->get();

  $check_payment_options = DB::table('company_supplier_template')
  ->where('company_supplier_template.trade_name', '=',$data[0]->Tname)
  ->select('company_supplier_template.is_Registerd_company as is_Registerd_company')
  ->get();

  if( $check_payment_options[0]->is_Registerd_company == 'NEW'){$payment=1000; $payment_words='one thousand dollars';
    $affected_cst = DB::table('company_supplier_template')
    ->where('company_supplier_template.trade_name',$data[0]->Tname)
    ->update(['company_supplier_template.is_Registerd_company' =>'Registerd']);

  }   
  
  else { $payment_words='five hundered dollars'; $payment=500;
  

  }


$invoices = invoices::create($request->all());

//return $pdf->stream();
//return $pdf->download('pdf.pdf');
//$pdf = PDF::loadView('invoice', $rendered_html_data);
//return $pdf->download('codingdriver.pdf');
//$pdf->stream();

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '8';
$documents->ref_num = $request->invoice_number;
$documents->description = $request->remark;
$documents->save();

$select_document_id = DB::table('documents')
                    ->where('name', $fileName)
                     ->get();

$affected_invoice = DB::table('invoices')
              ->where('invoices.application_id', $request->application_id)
              ->where('invoice_number', $request->invoice_number)
              ->update(['invoice_document_id' => $select_document_id[0]->id]);

$select_invoice_id = DB::table('invoices')
                    ->where('invoices.application_id', $request->application_id)
                    ->where('invoice_number', $request->invoice_number)
                     ->get();

$receipts = new receipt;
$receipts->application_id = $request->application_id;
$receipts->invoice_id = $select_invoice_id[0]->id;
$receipts->receipt_number = 'NMFA/------';
$receipts->amount = $select_invoice_id[0]->amount;
$receipts->date = now();
$receipts->description = '---';
$receipts->invoice_document_id=$select_invoice_id[0]->invoice_document_id ;
$receipts->save();


return response()->json(['Message'=>true,'application_id'=> $request->application_id,'Generated_Link'=>$generated_pdf_link]);


}
catch(Exception $e)
{
 return response()->json(['errorr'=>$e,'item'=>'error'.$e , 'Message'=>false]);
 }
}



public function rendered_html_data($application_id ,$random_application_id,$remark)
{

    $Users = new User();
    $array = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

    $random = Arr::random($array);
    $invoices = new invoices();
    $t=time();
    $ApplicationReceptionController = new  ApplicationReceptionController();

    $application_ID   = applications::where('applications.application_id',$application_id)
    ->join('users','users.id','applications.user_id')
    ->join('countries','countries.id','users.country_id')
    ->get();

    $data = DB::table('applications')
    ->where('applications.application_id', '=',$application_id )
    ->join('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
    ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
    ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
    ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
    ->select('applications.*', 'medicinal_products.product_trade_name', 'manufacturers.name','company_suppliers.trade_name as Tname','invoices.invoice_number','invoices.remark','invoices.amount')
    ->get();


 


    $i=$j=1;$return_data=''; $return_dataa='';

    foreach( $data as $row)

    {
   $return_data .= "<tr><td>".$i++."</td>";
   $return_data .= "<td>".$row->application_id."</td>";
   $return_data .= "<td>".$row->product_trade_name."</td>";
   $return_data .= "<td>".$row->name."</td>";
   $return_data .= "<td>".$random_application_id."</td>";
   $return_data .= "<td>".$row->application_type."</td>";
   $return_data .= "<td>".$remark."</td>";

  ;

    }

 



    if($row->application_type=='NewApplication')
  {
    $payment_value   = payment_configuration::where('payment_type','Application_fee')
    ->get();
    $amount = $payment_value[0]->amount;
  }

  if($row->application_type = 'Application_Reregistration')
  {
    $payment_value   = payment_configuration::where('payment_type','Application_fee')
    ->get();
    $amount = $payment_value[0]->amount;

  }


  $path = "images/nmfa_header.png";

  $path_footer = "images/nmfa_footer.png";

    //$applications = applications::create($request->all());
    $rendered_template = "
    <!DOCTYPE html>
<html lang='en-US'>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <style type='text/css'>
        h2{
            text-align: center;
            font-size:22px;
            margin-bottom:50px;
        }
        body{
            background:#f2f2f2;
        }
        .section{
            margin-top:30px;
            padding:50px;
            background:#fff;
        }
        .pdf-btn{
            margin-top:30px;
        }

    </style>

        <title>HTML 2 PDF</title>
        <style type='text/css'>
            .center {
                text-align: center;
            }
        </style>
        <link rel='stylesheet' href='{{asset('dist/css/adminlte.min.css')}}'>
    </head>
    <body>
    <section class='content'>
    <div class='container-fluid'  id='print_invoice'>
      <div class='row'>
        <div class='col-12'>
          <div class='callout callout-info'>
            <h5><i class='fas fa-info'></i> Invoice Note:</h5>
            <img src = ".$path."  alt='image' style='width: 100%;height: auto;''  />

           </div>


          <!-- Main content -->
          <div class='invoice p-3 mb-3'>
            <!-- title row -->
            <div class='row'>
              <div class='col-12'>
                <h4>
                <span hidden> <i class='fas fa-globe'>  </i>  </span>
                  <small class='float-right'>Date:".date("Y-m-d",$t)."</small>
                </h4>
              </div>
              <!-- /.col -->
            </div>
            <!-- info row -->
            <div class='row invoice-info'>
              <div class='col-sm-4 invoice-col'>
                From
                <address>
                  <strong>NMFA, Inc.</strong><br>
                  795 Orotta, Suite 600<br>
                  Asmara , Maekel 7845<br>
                  Phone: (+291) -1-125899<br>
                  Email: NMFA@pharmocovegilence.com
                </address>
              </div>
              <!-- /.col -->
              <div class='col-md-6'   style='position: absolute;left: 0%; top: 10%;' >
                To
                <address>
                  <strong>".$application_ID[0]->first_name.' '.$application_ID[0]->middle_name.' '.$application_ID[0]->last_name.' '."</strong><br>
                  ".$application_ID[0]->city."<br>
                  ".$application_ID[0]->count_name."<br>
                  Phone:  ".$application_ID[0]->telephone."<br>
                  Email: ".$application_ID[0]->email."
                </address>
              </div>
              <!-- /.col -->
              <div class='col-sm-4 invoice-col'>
                <b>Invoice ".$random_application_id."</b><br>
                <br>
                <b>Order ID:</b> 4F3S8J<br>
                <b>Payment Due:</b> 2/22/2014<br>
                <b>Account:</b> 968-34567
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->


            <!-- Table row -->
            <div class='row'>
              <div class='col-12 table-responsive'>
                <table class='table table-striped'>
                  <thead>
                  <tr>
                  <th>ID</th>
                    <th>AppID</th>
                    <th>ProductName</th>
                    <th>Company Supplier Name</th>
                    <th>Invoice Generated</th>
                    <th>Application Type</th>
                    <th>Remark</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                ".$return_data."
                  </tr>


                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class='row'>
              <!-- accepted payments column -->
              <div class='col-6'>
                <p class='lead'>Payment Methods:</p>
                <img src='../../dist/img/credit/visa.png' alt='Visa'>
                <img src='../../dist/img/credit/mastercard.png' alt='Mastercard'>
                <img src='../../dist/img/credit/american-express.png' alt='American Express'>
                <img src='../../dist/img/credit/paypal2.png' alt='Paypal'>

                <p class='text-muted well well-sm shadow-none' style='margin-top: 10px;'>
                Amount in words: Total amount written in words
                </p>
                <p style='font-weight:bold'  width=500px height=500px>
                Transfer the sum of USD
                To: - FEDERAL RESERVE BANK OF NEW YORK
                NEW YORK
                U.S.A
                SWIFT: FRNYUS33


                For Credit to: - Account no. 021088483
                Of Bank of Eritrea
                S.W.I.F.T. BOERERAI
                Asmara, Eritrea (with them)
                </p>

                <p style='font-weight:bold'>
                In favor of (Beneficiary): -
                Name: MINISTRY OF HEALTH
                A/C No. 120.201.0009
                </p>

                <p style='font-weight:italic' >
                Issued by: Iyassu Bahta,
                </p>
                <p>
                Director, National Medicines and Food Administration
                </p>
                <p>
                Cc: Head of finance, Ministry of Health, Eritrea

                </p>
              </div>
              <!-- /.col -->
              <div class='col-6'>
                <p class='lead'>Amount Due 2/22/2014</p>

                <div class='table-responsive'>
                  <table class='table'>

                    <tr>
                      <th>Total:</th>
                      <td id='amount_value'>".$amount."</td>
                    </tr>
                  </table>
                </div>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class='row no-print'>

            </div>
          </div>
          <!-- /.invoice -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>

  </body>
  </html>

  ";



  $check_payment_options = DB::table('company_supplier_template')
  ->where('company_supplier_template.trade_name', '=',$data[0]->Tname)
  ->select('company_supplier_template.is_Registerd_company as is_Registerd_company')
  ->get();

  if( $check_payment_options[0]->is_Registerd_company == 'NEW'){$payment=1000; $payment_words='one thousand dollars';}   else { $payment_words='five hundered dollars'; $payment=500;}




  foreach( $data as $row)

  {
 $return_dataa .= "<tr><td>".$j++."</td>";
 $return_dataa .= "<td>".$row->product_trade_name."</td>";
 $return_dataa .= "<td> <b id='amount_value'> $payment </b> </td></tr>";

  }



$rendered_templatee = "
<!DOCTYPE html>
<html lang='en-US'>

    <head>
   </head>
   
    <body>
    <div class='form-group'>
<section class='content'>
<div class='container-fluid'  id='print_invoice'>
  <div class='row'>
    <div class='col-12'>
      <div class='callout callout-info'>
       
        <img src = ".$path."  alt='image' style='width: 100%;height: auto;'' />

       </div>
      <!-- Main content -->
      <div class='invoice p-3 mb-3'>
      <!-- title row -->
      <div class='row'>
        <div class='col-12'>
          <h4>
          <span hidden> <i class='fas fa-globe'>  </i>  </span>
            <small class='float-right' style='position: absolute;left: 80%; '>Date:".date("Y-m-d",$t)."</small>
            <h2  style='position: absolute;left: 40%; ' > INVOICE </h2>

          </h4>
        </div>

     

          <!-- /.col -->
        </div>
        <br><br>
        <!-- info row -->
        <div class='row invoice-info'>
        
          <!-- /.col -->
         
          <!-- /.col -->
          <div class='col-md-6'   style='position: absolute;left: 0%; top: 22%;' >
            <b>Invoice Number :".$random_application_id."</b><br>
            <b>Customer’s Name: </b>".$application_ID[0]->first_name.' '.$application_ID[0]->middle_name.' '.$application_ID[0]->last_name.' '."<br>
            <b>Order number: </b> 2/22/2014<br>
            <b>Date of order:</b> ".date("Y-m-d",$t)."
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <br><br>   <br><br>   <br><br><br>   <br>

        <!-- Table row -->
        <div class='row'>
          <div class='col-12 table-responsive'>
            <table class='table table-striped'>
              <thead>
              <tr>
                <th>No</th>
                <th>Purpose:<i style='color:blue'> New application registration/ Application for re-registration  </i> </th>
                <th>Unit price (USD)</th>
                </tr>
              </thead>
              <tbody>
          
              $return_dataa

              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class='row'>
          <!-- accepted payments column -->
          <div class='col-6'>
           

            <p class='text-muted well well-sm shadow-none' style='margin-top: 10px;'>
            Amount in words: $payment_words
            <p style='font-weight:bold'  width=500px height=500px>
            Transfer the sum of USD
            <br>
            To: - FEDERAL RESERVE BANK OF NEW YORK
            NEW YORK
            U.S.A
            SWIFT: FRNYUS33

            <br><br>

            For Credit to: - Account no. 021088483
            Of Bank of Eritrea
            S.W.I.F.T. BOERERAI
            Asmara, Eritrea (with them)
            </p>

            <p style='font-weight:bold'>
            In favor of (Beneficiary): -
            Name: MINISTRY OF HEALTH
            A/C No. 120.201.0009
            </p>

            <p style='font-weight:italic' >
            Issued by: Iyassu Bahta,
            </p>
            <p>
            Director, National Medicines and Food Administration
            </p>
            <p>
            Cc: Head of finance, Ministry of Health, Eritrea

            </p>
          </div>
          <!-- /.col -->
       
            </div>

            <br><br>  <br><br>  <br><br> <br><br>  
            <p>
          <img src='".$path_footer."'  style='width: 100%;height: auto;' />       
          </p> 


          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class='row no-print'>

        </div>
      </div>
      <!-- /.invoice -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
</section>
</div>
</body>
</html>

";


return $rendered_templatee;

}



public function generated_invoices(Request $request)
{
     $invoices = new invoices();
     $invoice_generated=  DB::table('invoices')
     ->join('users','users.id','invoices.user_id')
     ->get();
    return view('receipts.reciepts',[

            'invoice_generated' =>  $invoice_generated,

        ]);



}


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
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
        $applications = applications::find($id);
       // dd( response()->json($book));
        return response()->json($applications);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
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




    public function generate_invoices_now(Request $request)
{
  $Users = new User();
  $array = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];


  $random = Arr::random($array);
  $invoices = new invoices();
  $t=time();
  $year = Date('Y');
   $count = invoices::where('id', '<>', null)->count();
   $count_sequence = $count + 1;
   $zero_filled_counter = sprintf('%04d', $count_sequence);
  //  $random_application_id= 'NMFA_'.$year."_".$zero_filled_counter;


  $ApplicationReceptionController = new  ApplicationReceptionController();


  // $random_application_id = 'NMFA/INV/'.$year ."/".$ApplicationReceptionController->random($random);

  $random_application_id = 'NMFA/INV/'.$year ."/".$zero_filled_counter;




  $application_ID   = applications::where('application_id',$request->application_id)
  ->join('users','users.id','applications.user_id')
  ->join('countries','countries.id','users.country_id')
  ->get();



  $data = DB::table('applications')
  ->where('applications.application_id', '=',$request->application_id )
  ->join('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
  ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
  ->join('company_suppliers','applications.application_id','=','company_suppliers.application_id')
  ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
  ->select('applications.*', 'medicinal_products.product_trade_name', 'manufacturers.name',
  'company_suppliers.trade_name as Tname','invoices.invoice_number','invoices.remark','invoices.amount')
  ->get();


  $check_payment_options = DB::table('company_supplier_template')
  ->where('company_supplier_template.trade_name', '=',$data[0]->Tname)
  ->select('company_supplier_template.is_Registerd_company as is_Registerd_company')
  ->get();

  if( $check_payment_options[0]->is_Registerd_company == 'NEW')
   {$payment=1000; $payment_words='one thousand dollars';}  
   else { $payment_words='five hundered dollars'; $payment=500;}



  $i=$j=1;$return_data=''; $return_dataa='';
  foreach( $data as $row)

  {
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .= "<td>".$row->application_id."</td>";
 $return_data .= "<td>".$row->product_trade_name."</td>";
 $return_data .= "<td>".$row->name."</td>";
 $return_data .= "<td>".$random_application_id."</td>";
 $return_data .= "<td>".$row->application_type."</td>";
 $return_data .= "<td>".$request->remark."</td>";
  }

  foreach( $data as $row)

  {
 $return_dataa .= "<tr><td>".$j++."</td>";
 $return_dataa .= "<td>".$row->product_trade_name."</td>";
 $return_dataa .= "<td> <b id='amount_value'> $payment </b> </td>";

  }

  if($row->application_type=='NewApplication')
{
  $payment_value   = payment_configuration::where('payment_type','Application_fee')
  ->get();
  $amount = $payment_value[0]->amount;
}

if($row->application_type = 'Application_Reregistration')
{
  $payment_value   = payment_configuration::where('payment_type','Application_fee')
  ->get();
  $amount = $payment_value[0]->amount;

}


$path = "../../../images/nmfs.jpg";

$path_footer = "images/nmfa_footer.png";

  //$applications = applications::create($request->all());
  $rendered_template = "
  <section class='content'>
  <div class='container-fluid'  id='print_invoice'>
    <div class='row'>
      <div class='col-12'>
        <div class='callout callout-info'>
          <h5><i class='fas fa-info'></i> Invoice Note:</h5>
          <img src = ".$path."  alt='image' style='width: 100%;height: auto;'  />

         </div>


        <!-- Main content -->
        <div class='invoice p-3 mb-3'>
          <!-- title row -->
          <div class='row'>
            <div class='col-12'>
              <h4>
              <span hidden> <i class='fas fa-globe'>  </i>  </span>
                <small class='float-right'>Date:".date("Y-m-d",$t)."</small>
              </h4>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class='row invoice-info'>
            <div class='col-sm-4 invoice-col'>
              From
              <address>
                <strong>NMFA, Inc.</strong><br>
                795 Orotta, Suite 600<br>
                Asmara , Maekel 7845<br>
                Phone: (+291) -1-125899<br>
                Email: NMFA@pharmocovegilence.com
              </address>
            </div>
            <!-- /.col -->
            <div class='col-sm-4 invoice-col'>
              To
              <address>
                <strong>".$application_ID[0]->first_name.' '.$application_ID[0]->middle_name.' '.$application_ID[0]->last_name.' '."</strong><br>
                ".$application_ID[0]->city."<br>
                ".$application_ID[0]->count_name."<br>
                Phone:  ".$application_ID[0]->telephone."<br>
                Email: ".$application_ID[0]->email."
              </address>
            </div>
            <!-- /.col -->
            <div class='col-sm-4 invoice-col'>
              <b>Invoice ".$random_application_id."</b><br>
              <br>
              <b>Order ID:</b> 4F3S8J<br>
              <b>Payment Due:</b> 2/22/2014<br>
              <b>Account:</b> 968-34567
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

<br><br>
          <!-- Table row -->
          <div class='row'>
            <div class='col-12 table-responsive'>
              <table class='table table-striped'>
                <thead>
                <tr>
                <th>ID</th>
                  <th>AppID</th>
                  <th>ProductName</th>
                  <th>Company Supplier Name</th>
                  <th>Invoice Generated</th>
                  <th>Application Type</th>
                  <th>Remark</th>
                </tr>
                </thead>
                <tbody>
                <tr>
              ".$return_data."
                </tr>


                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <div class='row'>
            <!-- accepted payments column -->
            <div class='col-6'>
              <p class='lead'>Payment Methods:</p>
              <img src='../../dist/img/credit/visa.png' alt='Visa'>
              <img src='../../dist/img/credit/mastercard.png' alt='Mastercard'>
              <img src='../../dist/img/credit/american-express.png' alt='American Express'>
              <img src='../../dist/img/credit/paypal2.png' alt='Paypal'>

              <p class='text-muted well well-sm shadow-none' style='margin-top: 10px;'>
              Amount in words: Total amount written in words
              </p>
              <p style='font-weight:bold'  width=500px height=500px>
              Transfer the sum of USD
              To: - FEDERAL RESERVE BANK OF NEW YORK
              NEW YORK
              U.S.A
              SWIFT: FRNYUS33


              For Credit to: - Account no. 021088483
              Of Bank of Eritrea
              S.W.I.F.T. BOERERAI
              Asmara, Eritrea (with them)
              </p>

              <p style='font-weight:bold'>
              In favor of (Beneficiary): -
              Name: MINISTRY OF HEALTH
              A/C No. 120.201.0009
              </p>

              <p style='font-weight:italic' >
              Issued by: Iyassu Bahta,
              </p>
              <p>
              Director, National Medicines and Food Administration
              </p>
              <p>
              Cc: Head of finance, Ministry of Health, Eritrea

              </p>
            </div>
            <!-- /.col -->
            <div class='col-6'>
              <p class='lead'>Amount Due 2/22/2014</p>

              <div class='table-responsive'>
                <table class='table'>

                  <tr>
                    <th>Total:</th>
                    <td id='amount_value'>".$amount."</td>
                  </tr>
                </table>
              </div>
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- this row will not appear when printing -->
          <div class='row no-print'>

          </div>
        </div>
        <!-- /.invoice -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</section>";





$rendered_templatee = "
  <section class='content'>
  <div class='container-fluid'  id='print_invoice'>
    <div class='row'>
      <div class='col-12'>
        <div class='callout callout-info'>
         
          <img src = ".$path."  alt='image' style='width: 100%;height: auto;'  />

         </div>
        <!-- Main content -->
        <div class='invoice p-3 mb-3'>
          <!-- title row -->
          <div class='row'>
            <div class='col-12'>
              <h4>
              <span hidden> <i class='fas fa-globe'>  </i>  </span>
                <small class='float-right'>Date:".date("Y-m-d",$t)."</small>
              </h4>
              <h2 class='pull-right' style='position: absolute;left: 45%; top: 95%;' > INVOICE </h2>

            </div>
            <!-- /.col -->
          </div>
          <br><br>
          <!-- info row -->
          <div class='row invoice-info'>
          
            <!-- /.col -->
           
            <!-- /.col -->
            <div class='col-md-6'   style='position: absolute;left: 0%; top: 10%;' >
              <b>Invoice ".$random_application_id."</b><br>
              <b>Customer’s Name: </b>".$application_ID[0]->first_name.' '.$application_ID[0]->middle_name.' '.$application_ID[0]->last_name.' '."<br>
              <b>Order number: </b> 2/22/2014<br>
              <b>Date of order:</b> ".date("Y-m-d",$t)."
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          <br><br>  <br><br><br>

          <!-- Table row -->
          <div class='row'>
            <div class='col-12 table-responsive'>
              <table class='table table-striped'>
                <thead>
                <tr>
                  <th>No</th>
                  <th>Purpose:<i style='color:blue'> New application registration/ Application for re-registration  </i> </th>
                  <th>Unit price (USD)</th>
                  </tr>
                </thead>
                <tbody>
                <tr>
              ".$return_dataa."
                </tr>


                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <div class='row'>
            <!-- accepted payments column -->
            <div class='col-6'>
             

              <p class='text-muted well well-sm shadow-none' style='margin-top: 10px;'>
              Amount in words:  $payment_words
              </p>
              <p style='font-weight:bold'  width=500px height=500px>
              Transfer the sum of USD
              <br>
              To: - FEDERAL RESERVE BANK OF NEW YORK
              NEW YORK
              U.S.A
              SWIFT: FRNYUS33

              <br><br>

              For Credit to: - Account no. 021088483
              Of Bank of Eritrea
              S.W.I.F.T. BOERERAI
              Asmara, Eritrea (with them)
              </p>

              <p style='font-weight:bold'>
              In favor of (Beneficiary): -
              Name: MINISTRY OF HEALTH
              A/C No. 120.201.0009
              </p>

              <p style='font-weight:italic' >
              Issued by: Iyassu Bahta,
              </p>
              <p>
              Director, National Medicines and Food Administration
              </p>
              <p>
              Cc: Head of finance, Ministry of Health, Eritrea

              </p>
            </div>
            <!-- /.col -->
         
              </div>

          <br><br>
              <p>
            <img src='".$path_footer."' style='width: 100%;height: auto;' />       
            </p> 


            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- this row will not appear when printing -->
          <div class='row no-print'>

          </div>
        </div>
        <!-- /.invoice -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</section>";






  return response()->json(
    [ 'invoice_generated'=>  $random_application_id,
      'rendered_template' => $rendered_templatee,
      'Invoice_Created' =>   'true',
      // 'generated_path' => $generated_path,
   ]);


   
}






public function generatePDF( Request $request)
{  
  
  
}





















}
