<?php

namespace App\Http\Controllers;

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
use App\Models\MainTask;
use Spatie\Permission\Models\Permission;
use App\Models\Acknowledgement_letter;
use App\Http\Controllers\MainTaskController;



class receipts extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * 
     * 
     */
    function __construct()

    {
  
  
      $this->middleware('permission:assessor_roles',['only' => ['index','store']]);
    
     
  
    }


    public function retrive_file_uploaded_to_applicant_financial_section(Request $request)
    {




      $doc_retrive_app = receipt::join('documents','documents.id','receipts.upload_financial_notification_to_applicant')
      ->select('documents.*','receipts.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
      ->where('receipts.application_id','=',$request->application_id)
      ->where('documents.document_type','=',20)
      ->get();

          //dd($doc_retrive_app);
    
     $i=1;   $return_data='';

     

      foreach($doc_retrive_app as $user_upload)
      {
      $return_data .= "<tr><td>".$i++."</td>";
      $return_data .= "<td id='seqence_number_$user_upload->id' >
     <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
     </td>";
      $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
      $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-document_id='$user_upload->did' data-id='$request->application_id' data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
      }
   
   
      
return response()->json(['Message'=>true,'return_data'=>$return_data ]);
   



    }

public function rendered_html_data_custom($request)
{

  $path_header = "images/nmfa_header.png";
  $path_footer = "images/nmfa_footer.png";

  $rendered_template  = 
  "  
  <!DOCTYPE html>
  <html lang='en-US'>
      <head>
          <meta charset='utf-8'>
          <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
          <meta name='viewport' content='width=device-width, initial-scale=1'>
     
  
          <title>HTML 2 PDF</title>
          <style type='text/css'>
              .center {
                  text-align: center;
              }
          </style>
      
      </head>
      <body>


  
  <section class='content'>
  <div class='container-fluid'>
    <div class='row'>
      <div class='col-12' id='letter_acknowledgement'>
        <!-- Main content -->
        <div class='container'>
        <img src='$path_header'  class='img-responsive' style='width: 100%;height: auto;'  alt='image' height='140' width='800'/>
      </div>
            <!-- /.col -->
          </div>
          <!-- info row -->

<!-- Main content -->
<div class='invoice'>
  <!-- title row -->
  <div class='row'>
    <div class='col-12'>
      <h4>
       
        
<input type='hidden' value=' $request->application_id ' id='application_id' name='' />
  <span hidden> <i class='fas fa-globe'>  </i>  </span>
  <small class='float-right' style='position: absolute;left: 80%; '>Date: $request->financial_notification_date_of_order </small>
      <br>
        <h2  style='position: absolute;left: 28%; ' > Financial Notification </h2>
        </h4>
     </div>

<div class='row invoice-info' style='position: absolute;left: 0%; top: 30%;'>
<div class='col-lg-12'    >
<b> Customer’s Name :</b> $request->fullname_contact <br>
<b>Ministry of Finance receipt number:</b>   $request->receipt_number   <br>
<b>Date of order: $request->financial_notification_date_of_order</b> 
</div>
<!-- /.col -->
</div>
</div>
<br><br> <br><br>     <br><br> <br><br>    <br><br>
$request->To_be_rendered
<br>

<p>
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


<div class='container'>
<img src='$path_footer'  class='img-responsive' alt='image' style='width: 100%;height: auto;'/>     
</div>  
</p>        
            </div>
           
</div>
<!-- /.content -->

</div>


</section>

</body>
</html>";

return $rendered_template;

}





public function save_financial_notification(Request $request)
{

//dd($request->all());
$rendered_html_data = $this->rendered_html_data_custom($request);
$pdf = PDF::loadHTML($rendered_html_data);
$pdf->setPaper('A4', 'portrait');
// <--- load your view into theDOM wrapper;
$time=time();
$path = public_path('storage/Financial_Notification/Financial_client_side_un_sealed/');

// <--- folder to store the pdf documents into the server;
$fileName = "--"."Financial_Notification".$time."-".'.pdf' ; // <--giving the random filename,
$pdf->save($path.$fileName);

$generated_pdf_link = Storage::url('public/Financial_Notification/Financial_client_side_un_sealed/'.$fileName);


$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '19';
$documents->ref_num = $fileName;
$documents->description = 'Financial_client_side_un_sealed';
$documents->save();


$update_receipt_order_date= DB::table('receipts')
->where('application_id', $request->application_id)
->update([
'financial_notification_date_order' => $request->financial_notification_date_of_order,
'financial_notification_flag' =>1,
'upload_financial_notification_document_id' => $documents->id

]);

//dd($request->application_id);




return response()->json(['Message'=>true,'Download_Link'=>$documents->path ]);

}



public function financial_notification_generate(Request $request,$id)
  {


$applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->leftjoin('checklists','checklists.application_id','applications.application_id')
    ->leftjoin('receipts','receipts.application_id','applications.application_id')
    ->select('receipts.*','checklists.application_id as check_app','applications.application_id','receipts.receipt_number as rn',
    'medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'applications.application_id as app_id',
    DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname_contact'),
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.assigned_To','=',auth()->user()->id)
    ->get();

//dd($id);
   //return view('Financial_Notification.template_for_financial_notification');


   
   return view('Financial_Notification.template_for_financial_notification',
   [
     'applications' =>  $applications,
    'id'=>$id,
    ]);


  }



     public function generating_financial_notifications(Request $request)
     {

        // $data = DB::table('receipts')
        // ->join('invoices','receipts.invoice_id','=','invoices.id')
        // ->join('documents','receipts.invoice_document_id','=','documents.id')
        // ->join('applications','applications.application_id','=','receipts.application_id')
        // ->select('receipts.*','invoices.*','documents.*')
        // ->whereNotNull('receipt_document_id')
        // ->where('applications.assigned_To','=',auth()->user()->id)
        //  ->get();



         $data = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
         ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
         ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
         ->join('contacts','contacts.application_id','applications.application_id')
         ->leftjoin('checklists','checklists.application_id','applications.application_id')
         ->join('invoices','applications.application_id','=','invoices.application_id')
         ->join('receipts','applications.application_id','=','receipts.application_id')
         ->leftjoin('documents', 'documents.id','=','receipts.upload_financial_notification_document_id')
         ->select('documents.*','checklists.*','checklists.application_id as check_app','applications.application_id as app_id','invoices.*',
         'receipts.*','medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
         'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*','contacts.first_name as cfirst_name',
         'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
         ->where('contacts.contact_type','=','Supplier')
         ->whereNotNull('receipts.receipt_document_id')
         ->where('applications.assigned_To','=',auth()->user()->id)
         ->get();


         
       




         return view('Financial_Notification.financial_notification',compact('data'));


     }



  public function rendere_html_financial_notification($application_id)
  {


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


  return $rendered_template;



  }

public function upload_financial_document_applicant(Request $request)
{

  try
  {
$validatedData = $request->validate([
 'file_Finance_notify' => 'required|mimes:pdf|max:2048',
 ]);


 $name = $request->file('file_Finance_notify')->getClientOriginalName();
 $time=time();
 $path = public_path('storage/Financial_Notification/Financial_applicant_side_uploaded_sealed/');
 
 // <--- folder to store the pdf documents into the server;
 $fileName =  $name."-".$time.'.pdf' ; // <--giving the random filename,
 $filePath = $request->file('file_Finance_notify')->storeAs('Financial_Notification/Financial_applicant_side_uploaded_sealed/', $fileName, 'public');
 $generated_pdf_link = Storage::url('public/Financial_Notification/Financial_applicant_side_uploaded_sealed/'.$fileName);
 
 //$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link;
$documents->document_type = '20';
$documents->ref_num =  $fileName;
$documents->description = 'Financial Document Uploaded To Applicant';
$documents->save();


$update_user_upload_ack = DB::table('receipts')
->where('application_id', $request->app_id)
->update(['upload_financial_notification_to_applicant' => $documents->id]);

//dd($update_user_upload_ack);



$doc_upload_finance_document  = receipt::join('documents',
'documents.id','receipts.upload_financial_notification_to_applicant')
->select('documents.*','receipts.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('receipts.application_id','=',$request->app_id)
->where('documents.document_type','=',20)
->get();

//dd( $issue_queries);


$i=1;   $return_data='';
foreach($doc_upload_finance_document as $user_upload)
    
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td id='seqence_number_$user_upload->id' >

<a  href='".$documents->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>


</td>";
$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-document_id='$user_upload->did' 
data-id='$request->app_id' 
data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
}




$application=applications::where('application_id', $request->app_id)->first();

   $user=User::where('id',$application->user_id)->first();


            $new_notification=[];
            $new_notification['type'] = 'Notification';
            $new_notification['subject'] ='Acknowledgement receipt for registration';
            $new_notification['from_user'] = 'Assesor';
            $new_notification['data'] = 'Financial Notification to Application payment registation recieved for :'.$application->application_number;
            $new_notification['related_document'] = null;
            $new_notification['related_id'] = $application->application_number;
           
            $new_notification['alert_level'] = null;
            $new_notification['remark'] = null;
            
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'Financial Notification to Application payment registation recieved for application No:'.$application->application_number));
            




return response()->json(['Message'=>true,'Download_Link'=>$documents->path,'Data_returned'=>$return_data ]);



  }

  catch(Exception $e)
  {

     return response()->json(['Message'=>false,'item'=>'error'.$e]);

  }




}











    public function upload_to_applicant(Request $request)

    {
        //dd($request->all());


        try
        {
     $validatedData = $request->validate([
       'file_ACK' => 'required|mimes:pdf|max:2048',
       ]);
    
   
       $name = $request->file('file_ACK')->getClientOriginalName();
       $time=time();
       $path = public_path('storage/Acknowledgement_Receipt_of_Registration_Application/uploaded_to_applicant');
       
       // <--- folder to store the pdf documents into the server;
       $fileName =  $name."-".$time.'.pdf' ; // <--giving the random filename,
       $filePath = $request->file('file_ACK')->storeAs('Acknowledgement_Receipt_of_Registration_Application/uploaded_to_applicant/', $fileName, 'public');
       $generated_pdf_link = Storage::url('public/Acknowledgement_Receipt_of_Registration_Application/uploaded_to_applicant/'.$fileName);
       
       //$generated_pdf_link = Storage::url($path.$fileName);
      //Uses to insert data in to the Document Selections
   
   $documents = new documents;
   $documents->name =  $fileName;
   $documents->path =  $generated_pdf_link;
   $documents->document_type = '18';
   $documents->ref_num =  $fileName;
   $documents->description = 'Upload Acknowledgement receipt for registration ';
   $documents->save();


   $update_user_upload_ack = DB::table('application_receipt_of_registrations')
   ->where('application_id', $request->app_id)
   ->update(['uploaded_to_applicant' => $documents->id]);
   $application=applications::where('application_id', $request->app_id)->first();

   $user=User::where('id',$application->user_id)->first();


            $new_notification=[];
            $new_notification['type'] = 'Notification';
            $new_notification['subject'] ='Acknowledgement receipt for registration';
            $new_notification['from_user'] = 'Assesor';
            $new_notification['data'] = 'Acknowledgement to Application payment for registation recieved for :'.$application->application_number;
            $new_notification['related_document'] = null;
            $new_notification['related_id'] = $application->application_number;
           
            $new_notification['alert_level'] = null;
            $new_notification['remark'] = null;
            
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, ' Acknowledgement for Application  registration payment '. $application->application_number));
            

//dd($update_user_upload_ack);

 $doc_upload_ack = application_receipt_of_registration::join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
   ->select('documents.*','application_receipt_of_registrations.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
   ->where('application_receipt_of_registrations.application_id','=',$request->app_id)
   ->where('documents.document_type','=',18)
   ->get();

   //dd( $issue_queries);

   
   $i=1;   $return_data='';
   foreach($doc_upload_ack as $user_upload)
          
   {
   $return_data .= "<tr><td>".$i++."</td>";
   $return_data .= "<td id='seqence_number_$user_upload->id' >
   
   <a  href='".$documents->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>

   
   </td>";
   $return_data .= "<td>".$user_upload->uploaded_Date."</td>";

   $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
   data-document_id='$user_upload->did' 
   data-id='$request->app_id' 
   data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
   }


   
return response()->json(['Message'=>true,'Download_Link'=>$documents->path,'Data_returned'=>$return_data ]);



        }
   
        catch(Exception $e)
        {
   
           return response()->json(['Message'=>false,'item'=>'error'.$e]);
   
        }
   

    }











// public function upload_to_applicant(Request $request)
// {
    

//     $update_acknowledgment_receipt_register= DB::table('application_receipt_of_registrations')
//     ->where('application_receipt_of_registrations.application_id', $request->application_id)
//     ->update([
//         'uploaded_to_applicant'=>  1
//     ]);


//     return response()->json(['Message'=>true,'uploaded_to_applicant'=>true]);

// }






     public function upload_acknowledgment_receipt(Request $request)
{

    try
    {

       $Acknowledgement_letter_receipt_registration = new application_receipt_of_registration;
       $Acknowledgement_letter_receipt_registration->application_id= $request->application_id;
       $Acknowledgement_letter_receipt_registration->Reference_number= $request->RL_squential_number;
       $Acknowledgement_letter_receipt_registration->received_document_types= $request->document_received_types;
       $Acknowledgement_letter_receipt_registration->application_number= $request->application_number;
       $Acknowledgement_letter_receipt_registration->reference_letter_dated= $request->date_of_letter;
       $Acknowledgement_letter_receipt_registration->No_of_DVDs_received= $request->dvd_received;
       $Application_receipt =  $Acknowledgement_letter_receipt_registration->save();



    $rendered_html_data = $this->rendered_html_data($request);
    $pdf = PDF::loadHTML($rendered_html_data);
    $pdf->setPaper('A4', 'portrait');
   // <--- load your view into theDOM wrapper;
 $time=time();
 $path = public_path('storage/Acknowledgement_Receipt_of_Registration_Application/saved_before_sealed/');
 $number_squence= str_replace("/","_",$request->RL_squential_number);
 // <--- folder to store the pdf documents into the server;
 $fileName =  $number_squence."--"."Receipt_of_Registration".$time."-".'.pdf' ; // <--giving the random filename,
 $pdf->save($path . '/'.$fileName);

 $generated_pdf_link = Storage::url('public/Acknowledgement_Receipt_of_Registration_Application/saved_before_sealed/'.$fileName);


    $documents = new documents;
    $documents->name =  $fileName;
    $documents->path =  $generated_pdf_link ;
    $documents->document_type = '17';
    $documents->ref_num = $request->RL_squential_number;
    $documents->description = 'Acknowledgement Receipt of Registration Application';
    $documents->save();


    $update_acknowledgment_receipt_register= DB::table('application_receipt_of_registrations')
    ->where('application_receipt_of_registrations.application_number', $request->application_number)
    ->update([ 'document_id'=>  $documents->id]);


    return response()->json(['Message'=>true,'Download_Link'=>$documents->path]);

    }

    catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}

}


public function rendered_html_data(Request $request)
{
    $path_header = "images/nmfa_header.png";
    $path_footer = "images/nmfa_footer.png";

    $rendered_html_data  = 
    " <!-- Content Wrapper. Contains page content -->
    <div class='content'>
      <!-- Content Header (Page header) -->
      <section class='content-header'>
        <div class='container-fluid'>
          <div class='row mb-2'>
           
            <div class='col-sm-6'>
              <ol class='breadcrumb float-sm-right'>
              
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <section class='content'>
        <div class='container-fluid'>
          <div class='row'>
            <div class='col-12' id='letter_acknowledgement'>
       
  
  
              <!-- Main content -->
              <div class='invoice p-3 mb-3'>
                <!-- title row -->
                <div class='row'>
                  <div class='col-12'>
                    <h4>
  
  
                  <div class='container'>
                    <img src='$path_header'     alt='image' alt='image' height='80' width='690'/>
                  </div>
  
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->
    <div class='row invoice-info'>
  
  <input hidden id='application_id' value='$request->application_id' />
  <div class='container'> 
   
    
    <p class='list-group'>
    <div class='panel panel-default'>
  
    <div class='panel-heading'  >Date: <span id='current_date'> $request->current_date </span> </div>
    <br/>
    <div class='panel-body'  >Ref: <span id='RL_squential_number'>  $request->RL_squential_number </span>  </div>
    <br/>
    <div class='panel-body'>
   To: <span id='applicant_name'>  $request->applicant_name </span>  <br/>
   <ul>
      <li> <span id='state_plot_number'> $request->region_state  </span> </li> <br/>
      <!-- <li> <span id='country'>  </span> </li>  </br> -->
      <li> <span id='region_state'>$request->region_state   </span>  </li>    </br> 
    </ul>
  </div>
  </div>
    </p>
   
  <style>
  p,block {
      text-align: justify;
  }
  
  
  </style>
       
  <b> Subject: Acknowledgement of Receipt of Registration Application</b> 
  <br/><br/>
  <block style='text-align: justify;'>
  <p>Dear Sir/Madam or  <span id='contact_person_name'> $request->contact_person_name </span> ,</p>
  <br/>
  This is to acknowledge receipt of your application for registration of a medicine in reference to your 
  letter dated   $request->date_of_letter. 
  The application number for the below product is  <b> <span id='application_number'>  $request->application_number  </span> </b>.
  <br><br>
  <p>
  Product Name: <span id='p_n'> $request->p_n  </span>
  <br><br>
  Documents received:
  </p>
  
  <ul>

  $request->document_received_types

  </ul>
  <br>
  <p>   No. of DVDs received:  $request->dvd_received    </p>
  
  </block>
  <br/><br/>
  <p> Best regards,  </p>
  
  Iyassu Bahta
  Director, National Medicines and Food Administration
  <br>
  Ministry of Health
  <br>
  Asmara, Eritrea
  <br> <br><br>
  </div>
  
  <br><br><br>
      <p>
      <div class='container'>
  <img src='$path_footer'  class='img-responsive' alt='image' style='width: 100%;height: auto;'/>     
  </div>  
  </p>        
                  </div>
                 
  
                  </div>
                  <!-- /.col -->
                </div>
  
                   </div>
          </div>
          </form>
      </div>
  </div>
      </section>";

return $rendered_html_data;

}







    public function index(Request $request)
    {
        //
        if ($request->ajax())
        {
             $data = DB::table('receipts')
             ->join('invoices','receipts.invoice_id','=','invoices.id')
             ->join('documents','receipts.invoice_document_id','=','documents.id')
             ->join('applications','applications.application_id','=','receipts.application_id')
             ->select('receipts.*','invoices.*','documents.*')
             ->whereNull('receipt_document_id')
             ->where('applications.assigned_To','=',auth()->user()->id)
              ->get();


        return Datatables::of($data)
                     ->addIndexColumn()
                     ->addColumn('action', function($row)
                     {
           
$btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->invoice_number.'"  

data-original-title="Edit" class="edit btn btn-warning  

btn-sm editReceipt" title="Upload Receipt File"> 

<i class="fas fa-money-check-alt"> </i> 
</a>
<br>';
           
//$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
            
                    return $btn;

                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
              
                return view('receipts.reciepts');
    }
    /**
     * Show the form for creating a new resource
     * 
     * 
     * .
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */


    public function Received(Request $request)
    {
        //


        if ($request->ajax())
        {
             $data = DB::table('receipts')
             ->join('invoices','receipts.invoice_id','=','invoices.id')
             ->join('documents','receipts.receipt_document_id','=','documents.id')
             ->join('applications','applications.application_id','=','receipts.application_id')
             ->select('receipts.*','invoices.*','documents.*')
             ->whereNotNull('receipt_document_id')
             ->where('applications.assigned_To','=',auth()->user()->id)
            //  ->where('documents.document_type','=',9)
              ->get();


return Datatables::of($data)->addIndexColumn()->addColumn('action', function($row){



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
 DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname_contact'),
'manufacturers.state as mstate', 'applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 
 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
->where('applications.application_id',$row->application_id)
->where('contacts.contact_type','Supplier')
->orderBy('invoices.invoice_number','ASC')
->get();



$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$row->application_id)
                            ->select('applications.*','medicines.*','medicinal_products.*')
                            ->get();


$dosage_forms = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                            ->select('dosage_forms.*','medicinal_products.*')
                            ->get();



$application_receipt_of_registration = DB::table('application_receipt_of_registrations')
                            ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
                            ->select('application_receipt_of_registrations.*')
                            ->get();




                            $application_receipt_of_registrations =  new application_receipt_of_registration;
                            $t=time();
                            $year = Date('Y');
                            $count = application_receipt_of_registration::where('Reference_number', '<>', null)->count();
                            $count_sequence = $count + 1;
                            $zero_filled_counter = sprintf('%04d', $count_sequence);
                            $squential_Reference_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;



$btn = '<a href="'.$row->path.'" title="Download Receipt File" class="edit btn btn-info btn-sm"><i class="fas fa-eye"> </i></a><br>';

if(@$application_receipt_of_registration[0]->application_number == ''  )

{


$btn = $btn.'<br><a href="javascript:void(0)" data-toggle="tooltip"  
data-id="'.$row->application_id.'" 
data-sequence_number="'.$squential_Reference_number.'" 
data-street_plot_number = "'.$check_list[0]->address_line_one.' '.$check_list[0]->address_line_two.'"
data-region_state= "'.$check_list[0]->mstate.'"
data-contact_person = "'.$check_list[0]->fullname_contact.'"
data-application_number = "'.$check_list[0]->application_number.'"
data-p_n =  "'.$check_list[0]->product_name.''.$dosage_forms[0]->name.' ,'.$check_list[0]->product_trade_name.'"
data-list_received_documents = "'.$check_list[0]->received_document_types.'"
title="Acknowledgement Receipt of Registration" class="btn btn-warning btn-sm receipt_register"> <i class="fas fa-pencil-square"> </i></a><br>';
}
else
{


    $doc_upload_ack = application_receipt_of_registration::join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
   ->select('documents.*','application_receipt_of_registrations.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
   ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
   ->where('documents.document_type','=',18)
   ->get();

   //dd( $issue_queries);

   
   $i=1;   $return_data='';

   foreach($doc_upload_ack as $user_upload)
          
   {
   $return_data .= "<tr><td>".$i++."</td>";
   $return_data .= "<td id='seqence_number_$user_upload->id' >
   
   <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>

   
   </td>";
   $return_data .= "<td>".$user_upload->uploaded_Date."</td>";

   $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
   data-document_id='$user_upload->did' 
   data-id='$row->application_id' 
    data-original-title='delete'  class='delete btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
   }



    $path = DB::table('application_receipt_of_registrations')
                          ->join('documents', 'documents.id', '=', 'application_receipt_of_registrations.document_id')
                           ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
                            ->select('application_receipt_of_registrations.*','documents.*')
                            ->get();



    $btn= $btn.'<br> <a href="javascript:void(0)" 
    data-id="'.$row->application_id.'" 
    data-path="'.$path[0]->path.'" 
    data-return_data="'.$return_data.'"
    data-document_id="'.$path[0]->document_id.'"
    data-toggle="tooltip"
    title="upload Receipt File" class="edit btn btn-success btn-sm upload_receipt_register">
    <i class="fas fa-file-upload"> </i></a><br>';

}


//$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
           


             return $btn;
                        
            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
              
                return view('receipts.receipts_received');
    }






    public function receipts_all(Request $request)
    {
        //
        if ($request->ajax())
        {
             $data = DB::table('receipts')
             ->join('invoices','receipts.invoice_id','=','invoices.id')
             ->join('documents','receipts.receipt_document_id','=','documents.id')
             ->join('applications','applications.application_id','=','receipts.application_id')
             ->select('receipts.*','invoices.*','documents.*')
             ->whereNotNull('receipt_document_id')
             ->where('applications.assigned_To','=',auth()->user()->id)
            //  ->where('documents.document_type','=',9)
              ->get();


return Datatables::of($data)->addIndexColumn()->addColumn('action', function($row){



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
 DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname_contact'),
'manufacturers.state as mstate', 'applications.*','invoices.*','contacts.*', 'medicines.product_name','medicinal_products.product_trade_name', 
 'manufacturers.name as manufacturer_name','company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
->where('applications.application_id',$row->application_id)
->where('contacts.contact_type','Supplier')
->orderBy('invoices.invoice_number','ASC')
->get();



$product_enlm_list = DB::table('applications')
                            ->join('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
                            ->join('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
                            ->where('applications.application_id',$row->application_id)
                            ->select('applications.*','medicines.*','medicinal_products.*')
                            ->get();


$dosage_forms = DB::table('medicinal_products')
                            ->join('dosage_forms', 'dosage_forms.id', '=', 'medicinal_products.dosage_form_id')
                            ->where('medicinal_products.dosage_form_id','=',$product_enlm_list[0]->dosage_form_id)
                            ->select('dosage_forms.*','medicinal_products.*')
                            ->get();



$application_receipt_of_registration = DB::table('application_receipt_of_registrations')
                            ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
                            ->select('application_receipt_of_registrations.*')
                            ->get();




                            $application_receipt_of_registrations =  new application_receipt_of_registration;
                            $t=time();
                            $year = Date('Y');
                            $count = application_receipt_of_registration::where('Reference_number', '<>', null)->count();
                            $count_sequence = $count + 1;
                            $zero_filled_counter = sprintf('%04d', $count_sequence);
                            $squential_Reference_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;



$btn = '<a href="'.$row->path.'" title="Download Receipt File" class="edit btn btn-info btn-sm"><i class="fas fa-eye"> </i></a><br>';

if(@$application_receipt_of_registration[0]->application_number == ''  )

{
$btn = $btn.'<br><a href="javascript:void(0)" data-toggle="tooltip"  
data-id="'.$row->application_id.'" 
data-sequence_number="'.$squential_Reference_number.'" 
data-street_plot_number = "'.$check_list[0]->address_line_one.' '.$check_list[0]->address_line_two.'"
data-region_state= "'.$check_list[0]->mstate.'"
data-contact_person = "'.$check_list[0]->fullname_contact.'"
data-application_number = "'.$check_list[0]->application_number.'"
data-p_n =  "'.$check_list[0]->product_name.''.$dosage_forms[0]->name.' ,'.$check_list[0]->product_trade_name.'"
data-list_received_documents = "'.$check_list[0]->received_document_types.'"
title="Application Receipt Registration Application" class="btn btn-info btn-sm receipt_register"></a><br>';
}
else
{


    $doc_upload_ack = application_receipt_of_registration::join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
   ->select('documents.*','application_receipt_of_registrations.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
   ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
   ->where('documents.document_type','=',18)
   ->get();

   //dd( $issue_queries);

   
   $i=1;   $return_data='';

   foreach($doc_upload_ack as $user_upload)
          
   {
   $return_data .= "<tr><td>".$i++."</td>";
   $return_data .= "<td id='seqence_number_$user_upload->id' >
   
   <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>

   
   </td>";
   $return_data .= "<td>".$user_upload->uploaded_Date."</td>";

   $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
   data-document_id='$user_upload->did' 
   data-id='$row->application_id' 
    data-original-title='delete'  class='delete btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
   }



    $path = DB::table('application_receipt_of_registrations')
                          ->join('documents', 'documents.id', '=', 'application_receipt_of_registrations.document_id')
                           ->where('application_receipt_of_registrations.application_id','=',$row->application_id)
                            ->select('application_receipt_of_registrations.*','documents.*')
                            ->get();



    $btn= $btn.'<a href="javascript:void(0)" 
    data-id="'.$row->application_id.'" 
    data-path="'.$path[0]->path.'" 
    data-return_data="'.$return_data.'"
    data-document_id="'.$path[0]->document_id.'"
    data-toggle="tooltip"
    title="upload Receipt File" class="edit btn btn-success btn-sm upload_receipt_register">
    <i class="fas fa-file-upload"> </i></a> &nbsp;&nbsp';

}


//$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
           


             return $btn;
                        
            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
              

              
                return view('receipts.receipts_received_all');
    }




    public function store(Request $req )
    { 
        $selected_invoice = DB::table('invoices')
         ->where('invoice_number', $req->invoice_number)
        ->get();

   
      $path = $req->file->path();
      $extension = $req->file->extension();

       $req->validate([
        'file' => 'required|mimes:pdf,csv,txt,xlx,xls,pdf|max:2048'
        ]);

        $fileModel = new  receipt;

        if($req->has('file')) {

            $inv_num = str_replace("/", "_",  $req->invoice_number);

            // $fileName = $inv_num.'_'.'Receipt'->getClientOriginalName();
            
            $fileName = $inv_num.'_'.'Receipt.pdf';
            $filePath = $req->file('file')->storeAs('Collected_Receipt_PDFS', $fileName, 'public');

           $fileModel->name = time().'_'.$req->file;
            $fileModel->file_path = '/storage/' . $filePath;


            $documents = new documents;
            $documents->name =  $fileName;
            $documents->path =  $fileModel->file_path ;
            $documents->document_type = '9';
            $documents->ref_num = $req->receipt_number;
            $documents->description = $req->description;
            $documents->save();

           

            $Get_user_id = DB::table('receipts')
            ->join('invoices','receipts.invoice_id','=','invoices.id')
            ->join('applications','applications.application_id','=','receipts.application_id')
            ->select('applications.*')
          
            ->where('invoices.invoice_number','=',$req->invoice_number)
             ->first();
             //dd($Get_user_id );

             $user=User::where('id',$Get_user_id->user_id)->first();


            $new_notification=[];
            $new_notification['type'] = 'Notification';
            $new_notification['subject'] =' Applicantion payment recieved';
            $new_notification['from_user'] = 'Assesor';
            $new_notification['data'] = 'Application payment for registation recieved for :';
            $new_notification['related_document'] = null;
            $new_notification['related_id'] = $Get_user_id->application_number;
           
            $new_notification['alert_level'] = null;
            $new_notification['remark'] = null;
            
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'Application  registration payment  recieved for  '. $Get_user_id->application_number));
            


            $select_document_id = DB::table('documents')
                    ->where('name', $fileName)
                     ->get();




           $affected_receipts = DB::table('receipts')
            ->where('invoice_id', $selected_invoice[0]->id)
            ->update([
            'receipt_number' => $req->receipt_number,
            'Receipt_Date' => $req->receipt_data,
            'description' => $req->description,
            'receipt_document_id' =>  $select_document_id[0]->id,
                
                ]);


                $affected_application_payment_Status = DB::table('applications')
                ->where('application_id', $selected_invoice[0]->application_id)
                ->update([
                'payment_status' => '1',
                   ]);


           return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);
        }
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




    public function delete_file_uploaded_to_applicant(Request $request)
    {
            // dd($request->all());
         $documents = new documents;
         $documents= documents::find($request->document_id);
         $documents->delete();


         $update_upload_ack =    DB::table('application_receipt_of_registrations')
                                          ->where('application_id', $request->application_id)
                                          ->update(['uploaded_to_applicant' => '0']);


        $doc_upload_ack = application_receipt_of_registration::join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
                                          ->select('documents.*','application_receipt_of_registrations.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
                                          ->where('application_receipt_of_registrations.application_id','=',$request->application_id)
                                          ->where('documents.document_type','=',18)
                                          ->get();
                                        //dd( $issue_queries);
                                         $i=1;   $return_data='';

                                          foreach($doc_upload_ack as $user_upload)
                                          {
                                          $return_data .= "<tr><td>".$i++."</td>";
                                          $return_data .= "<td id='seqence_number_$user_upload->id' >
                                         <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
                                         </td>";
                                          $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
                                          $return_data .= "<td> <a href='javascript:void(0)' 
                                          data-toggle='tooltip' id='query' 
                                          data-document_id='$user_upload->did'
                                           data-id='$row->application_id'
                                            data-original-title='Edit'  
                                            class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
                                          }
                                       
                                       
                                          
return response()->json(['Message'=>true,'return_data'=>$return_data ]);
                                       
                                       
                                       
    }




    public function delete_file_uploaded_financial_notification(Request $request)
    {
            // dd($request->all());
         $documents = new documents;
         $documents= documents::find($request->document_id);
         $documents->delete();


         $update_upload_ack =    DB::table('receipts')
                                          ->where('application_id', $request->application_id)
                                          ->update(['upload_financial_notification_to_applicant' => '0']);


        $doc_upload_ack = receipt::join('documents','documents.id','receipts.upload_financial_notification_to_applicant')
          ->select('documents.*','receipts.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
                                          ->where('receipts.application_id','=',$request->application_id)
                                          ->where('documents.document_type','=',20)
                                          ->get();
                                        //dd( $issue_queries);
                                         $i=1;   $return_data='';

                                          foreach($doc_upload_ack as $user_upload)
                                          {
                                          $return_data .= "<tr><td>".$i++."</td>";
                                          $return_data .= "<td id='seqence_number_$user_upload->id' >
                                         <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
                                         </td>";
                                          $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
                                          $return_data .= "<td> <a href='javascript:void(0)' 
                                          data-toggle='tooltip' id='query' 
                                          data-document_id='$user_upload->did'
                                           data-id='$row->application_id'
                                            data-original-title='Edit'  
                                            class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
                                          }
                                       
                                       
                                          
return response()->json(['Message'=>true,'return_data'=>$return_data ]);
                                       
                                       
                                       
    }













    public function retrive_file_uploaded_to_applicant(Request $request)
    {
            // dd($request->all());
        //  $documents = new documents;
        //  $documents= documents::find($request->document_id);
        //  $documents->delete();



        $doc_upload_ack = application_receipt_of_registration::join('documents','documents.id','application_receipt_of_registrations.uploaded_to_applicant')
                                          ->select('documents.*','application_receipt_of_registrations.*','documents.name as dname','documents.created_at as uploaded_Date','documents.id as did')
                                          ->where('application_receipt_of_registrations.application_id','=',$request->application_id)
                                          ->where('documents.document_type','=',18)
                                          ->get();
                                        //dd( $issue_queries);
                                         $i=1;   $return_data='';

                                          foreach($doc_upload_ack as $user_upload)
                                          {
                                          $return_data .= "<tr><td>".$i++."</td>";
                                          $return_data .= "<td id='seqence_number_$user_upload->id' >
                                         <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
                                         </td>";
                                          $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
                                          $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-document_id='$user_upload->did' data-id='$request->application_id' data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
                                          }
                                       
                                       
                                          
return response()->json(['Message'=>true,'return_data'=>$return_data ]);
                                       
                                       
                                       
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



    public function get_amount_from_invoice(Request $request)
{

  $invoices = new invoices();

  $generated=  DB::table('invoices')
  ->where('invoice_number',$request->invoice_number)
  ->get();


return response()->json(['Message'=>true, 'amount' =>  $generated[0]->amount]);


}



}
