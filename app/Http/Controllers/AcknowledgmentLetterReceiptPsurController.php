<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Events\ApplicationReceiptionEvent;
use App\Models\Acknowledgment_letter_receipt_psur;
use App\Models\applications;
use App\Models\documents;
use App\Models\User;
use App\Notifications\ApplicationReceiptionNotification;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PDF;
use \Mpdf\Mpdf as PDFF;

class AcknowledgmentLetterReceiptPsurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */


    public function delete_file_uploaded_acknowledgment_letter_psur(Request $request)
    {

      //dd($request->all());

$update_applications = DB::table('acknowledgment_letter_receipt_psurs')
->where('acknowledgment_letter_receipt_psurs.psur_refrence__number', $request->psur_reference_number)
->update([
'uploaded_id'=>  ''
]);


      $documents = new documents;
      $documents= documents::find($request->document_id);
      $documents->delete();


  $doc_upload_acknowledgement_letters_document  = Acknowledgment_letter_receipt_psur::join('documents','documents.id',
  'acknowledgment_letter_receipt_psurs.uploaded_id')
  ->select('documents.*','acknowledgment_letter_receipt_psurs.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgment_letter_receipt_psurs.psur_refrence__number','=',$request->psur_reference_number)
  ->where('documents.document_type','=',34)
  ->get();
  
  //dd( $issue_queries);
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_acknowledgement_letters_document as $user_upload)
      
  {
  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR'   id='Download_File' >   ".$user_upload->dname."</a>
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='deletefile'
  data-document_id='$user_upload->did' 
  data-id='$user_upload->application_id' 
  data-psur_reference_number='$user_upload->psur_refrence__number' 
  data-original-title='Delete'  class='edit btn btn-danger btn-sm deleteFile_psur'> <i class='fas fa-trash'></i> Remove </a></td>";    
  }
  
  
  
  return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);
  
 
    }


    public function fetch_uploaded_acknowledgement_letter_if_any_psur(Request $request)
    {

     // dd($request->all());

  $doc_upload_acknowledgement_letters_document  = Acknowledgment_letter_receipt_psur::join('documents','documents.id',
  'acknowledgment_letter_receipt_psurs.uploaded_id')
  ->select('documents.*','acknowledgment_letter_receipt_psurs.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgment_letter_receipt_psurs.application_number','=',$request->application_number)
  ->where('acknowledgment_letter_receipt_psurs.psur_refrence__number','=',$request->psur_refrence_number)   
  ->where('documents.document_type','=',34)
  ->get();
  
  $doc_generated_from_saving  = Acknowledgment_letter_receipt_psur::join('documents','documents.id',
  'acknowledgment_letter_receipt_psurs.document_id')
  ->select('documents.*','acknowledgment_letter_receipt_psurs.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgment_letter_receipt_psurs.psur_refrence__number','=',$request->psur_refrence_number)
 
  ->where('documents.document_type','=',33)
  ->first();

  //dd( $doc_upload_acknowledgement_letters_document );
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_acknowledgement_letters_document as $user_upload)
  {

  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id'>
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR'   id='Download_File' >   ".$user_upload->dname."</a>
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='deletefile'
  data-document_id='$user_upload->did' 
  data-id='$user_upload->application_id' 
  data-psur_reference_number='$user_upload->psur_refrence__number' 
  data-original-title='Delete'  class='edit btn btn-danger btn-sm deleteFile_psur'> <i class='fas fa-trash'></i> Remove </a></td>";
  }
  
  
  
  return response()->json(['Message'=>true,'Download_Link'=>@$doc_generated_from_saving->path,'Data_returned'=>$return_data ]);
  
 
    }






    
public function upload_file_acknowledgement_psur(Request $request)
{

    //dd($request->all());

 try
 {
$validatedData = $request->validate([
'file' => 'required|mimes:pdf,docx,doc|max:204800',
]);


$name = $request->file('file')->getClientOriginalName();

// $size = Storage::size( $request->file('file')  );
// dd($size);

$time=time();
// $path = $request->file('file')->store('public/images');
$path = public_path('storage/Acknowledgement_Letter/Document_Uploaded_To_Applicant/');

// <--- folder to store the pdf documents into the server;
$fileName =  $name."-".$request->app_name.$time.".".$request->file('file')->extension();  // <--giving the random filename,

$reference_number = $name."-".$request->app_name;
$filePath = $request->file('file')->storeAs('Acknowledgement_Receipt_of_Registration_Application/Document_Uploaded_To_Applicant/', $fileName, 'public');
$generated_pdf_link = Storage::url('public/Acknowledgement_Receipt_of_Registration_Application/Document_Uploaded_To_Applicant/'.$fileName);



//$generated_pdf_link = Storage::url($path.$fileName);
//Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '34';
$documents->ref_num = $reference_number;
$documents->description = 'Upload Acknowledgement letter to applicant for PSUR';
$documents->save();



$application=applications::where('application_id', $request->application_id)->first();


$user=User::where('id', $request->applicant_user_id)->first();


// ::send($users, new ($invoice));
$new_notification=[];
$new_notification['type'] = 'Notification';
$new_notification['subject'] ='Supervisor has uploaded acknowledgement of PSUR';
$new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
$new_notification['data']='Supervisor has uploaded acknowledgement of PSUR for application number: '. $request->application_number.' ' ; 
$new_notification['related_document'] = $documents->ref_num;
$new_notification['related_id'] = $request->application_id;
$new_notification['alert_level'] = null;
$new_notification['remark'] = null;

Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'Supervisor has uploaded acknowledgement of PSUR' . ' ' . $user->first_name . ' ' . $user->first_name));


$update_applications = DB::table('acknowledgment_letter_receipt_psurs')
->where('acknowledgment_letter_receipt_psurs.psur_refrence__number', $request->psur_reference_number_hidden)
->update([
'applicant_user_id' => $request->applicant_user_id,
'supervisor_id' => $request->supervisor_id,
'uploaded_id'=>  $documents->id

]);



$application=applications::where('application_id',$request->application_id)->first();




$update_main_task_status = DB::table('main_tasks')
->where('related_id', $application->id)
->where('related_task', 'Application')
->update([
'task_status' => 'Acknowledgment letter uploaded for PSUR',
]);



$doc_upload_acknowledgement_letters_document  = Acknowledgment_letter_receipt_psur::join('documents','documents.id',
'acknowledgment_letter_receipt_psurs.uploaded_id')
->select('documents.*','acknowledgment_letter_receipt_psurs.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('acknowledgment_letter_receipt_psurs.application_id','=',$request->application_id)
->where('acknowledgment_letter_receipt_psurs.psur_refrence__number','=',$request->psur_reference_number_hidden)  
->where('documents.document_type','=',34)
->get();

//dd( $issue_queries);


$i=1;   $return_data='';
foreach($doc_upload_acknowledgement_letters_document as $user_upload)

{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td id='seqence_number_$user_upload->id' >

<a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of PSUR '   id='Download_File' >   ".$user_upload->dname."</a>


</td>";
$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-document_id='$user_upload->did' 
data-id='$user_upload->application_id' 
data-psur_reference_number='$user_upload->psur_refrence__number' 
data-original-title='Edit'  class='edit btn btn-danger btn-sm deleteFile_psur'> <i class='fas fa-trash'></i> Remove </a></td>";    
}



return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);


//return response()->json(['Message'=>true,'Download_Link'=>$documents->path]);
 }

 catch(Exception $e)
 {

    return response()->json(['Message'=>false,'item'=>'error'.$e]);

 }




}



public function save_acknowledgementLetter_psur(Request $request)
   {
     // dd($request->all());
      try{

        $Application_Number  =  Acknowledgment_letter_receipt_psur::where('psur_refrence__number',$request->psur_refrence_number)->get();
         //dd($Application_Number);

        if(@$Application_Number[0]->application_number == '')
        {
            $count =  Acknowledgment_letter_receipt_psur::where('RL_squential_number', '<>', null)->count();
            $count_sequence = $count + 1;
            $year = Date('Y');
            $zero_filled_counter = sprintf('%04d', $count_sequence);
            $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;
            $random_application_RL_squential_name= 'NMFA_RL_'.$year."_".$zero_filled_counter;
            $request->RL_squential_number = $random_application_RL_squential_number;

            $time=time(); $date= date("Y-m-d",$time);
            $Acknowledgement_letter = new Acknowledgment_letter_receipt_psur;
            $Acknowledgement_letter->application_id = $request->application_id;
            $Acknowledgement_letter->psur_refrence__number = $request->psur_refrence_number;
            $Acknowledgement_letter->RL_squential_number= $request->RL_squential_number;
            $Acknowledgement_letter->date=  $date;
            $Acknowledgement_letter->applicant_name= $request->applicant_name;
            $Acknowledgement_letter->region_state= $request->state_plot_number;
            $Acknowledgement_letter->region_state= $request->country;
            $Acknowledgement_letter->contact_person_name= $request->contact_person_name;
            $Acknowledgement_letter->application_number= $request->application_number;
            $Acknowledgement_letter->edited_html_file= $request->edited_html_file ;


       $Application_ =  $Acknowledgement_letter->save();

//dd($Application_);

       if ( $Application_ == true)
       {
           //This section uses to create the documents from the dom pdf package downloaded from

         $rendered_html_data = $this->rendered_html_data($request->application_id,$request->application_number,$request->edited_html_file );
        
    // <--- load your view into theDOM wrapper;
   
      $path = public_path('storage/Acknowledgement_Receipt_of_PSUR/saved_before_sealed/');
    
      $time=time().".pdf";
      $file_name = 'Acknowledgement_Receipt_of_PSUR';
      $uploaded_file_name = $file_name."_".$time;

         $document = new PDFF([ 'format'=>"A4", 'margin_header'=>"1", 'margin_top'=>"30", 'margin_bottom'=>"20", 'margin_footer'=>"2", ]);
         $header=['Content-Type'=> 'application/pdf','Content-Disposition'=>'inline: filename=""'];
         $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
         $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');
         $document->WriteHTML($rendered_html_data);


         Storage::disk('Acknowledgement_Receipt_of_PSUR')->put($uploaded_file_name,$document->Output($uploaded_file_name,"S"));
         
         $generated_pdf_link = Storage::url('public/Acknowledgement_Receipt_of_PSUR/saved_before_sealed/'.$uploaded_file_name);



$documents = new documents;
$documents->name =  $uploaded_file_name;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '33';
$documents->ref_num = $random_application_RL_squential_name;
$documents->description = '--';
$documents->save();

         // Uses to insert data in to the Document Selections
$select_document_id = DB::table('documents') ->where('name',  $uploaded_file_name)->get();

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

// Uses to insert data in to the Acknowledgment Letter
 $update_acknowledgement_letter = DB::table('acknowledgment_letter_receipt_psurs')
->where('acknowledgment_letter_receipt_psurs.psur_refrence__number', $request->psur_refrence_number)
->update(['document_id' => $documents->id]);



// dd($select_data_applications[0]->dossier_actual_path);
//Uses to update the dossier Path



$application_retreive = DB::table('applications')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
        'medicinal_products.product_trade_name', 
         DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$request->application_id)
        ->where('contacts.contact_type','Supplier')
        ->get();


return response()->json(['Message'=>true,
'Applicant_Number'=>$request->application_number,
'Download_link' =>$select_document_id[0]->path
]);



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
$new_notification['type']='Preliminary';
$new_notification['data']=' Assesor Submited screening Report Section two';
$new_notification['from_user'] = 'System Notification';
$new_notification['subject']='Application Screening Report';
$new_notification['alert_level']='high';
$new_notification['related_document']=  '';
$new_notification['remark']='remark';
// ::send($users, new ($invoice));
Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, ' Assesor submit application ' . ' ' . $user->first_name . ' ' . $user->first_name));
}
}
}
}




       }
       else
       {
           /** @var TYPE_NAME $e */
           $psur_letter_acknowledgment =DB::table('acknowledgment_letter_receipt_psurs')->where('application_id', '=', $request->application_id)->delete();

           return response()->json(['Message'=>$e,'item'=>'error'.$e]);

       }

  }
  else
  {

    $psur_letter_acknowledgment =DB::table('acknowledgment_letter_receipt_psurs')->where('application_id', '=', $request->application_id)->delete();
   return response()->json(['Message'=>false,'item'=>'error']);

  }


        }
        catch(Exception $e)
        {
            $psur_letter_acknowledgment =DB::table('acknowledgment_letter_receipt_psurs')->where('application_id', '=', $request->application_id)->delete();

        return response()->json(['Message'=>$e,'item'=>'error'.$e]);
        }







   }



   public function rendered_html_data($application_id,$application_number,$editable_file)
   {
   

   
     $path_header = "images/nmfa_header.png";
     $path_footer = "images/nmfa_footer.png";
   
       //$applications = applications::create($request->all());
   $Acknowledgement_letter  = Acknowledgment_letter_receipt_psur::where('application_id',$application_id)->get();
   
   foreach($Acknowledgement_letter  as $checked_acknowledgement_letter) {
   
       $random_application_RL_squential_number =  $checked_acknowledgement_letter['RL_squential_number'];
       $number_days_receipts =  $checked_acknowledgement_letter['number_days_receipts'];
       $date  =  $checked_acknowledgement_letter['date'];
       $fullname_contact  =  $checked_acknowledgement_letter['contact_person_name'];
       $address  =  $checked_acknowledgement_letter['region_state'];
       $country_name =   $checked_acknowledgement_letter['region_state'];
   
   
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
                  
                   <!-- /.col -->
                 </div>
                 <!-- info row -->
     <div class='row invoice-info'>
   

   $editable_file

                   </div>
               
  
                   </div>
   
   
   
       </body>
     </html>
     ";
   
     return $rendered_template;
   }




































    public function Acknowledgement_Letter($id)
    {

        @$explode = explode('--',$id);
        $id= $explode[0];
        $psur_id = $explode[1];
        
       $Get_psur_reference_number =  DB::table('psurs')
       ->where('psurs.id',$psur_id)
       ->select('psurs.*')
       ->first();



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



        $check_list = DB::table('applications')
        ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->leftjoin('dosage_forms', 'medicinal_products.dosage_form_id', '=', 'dosage_forms.id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name',
        'medicinal_products.product_trade_name', 'manufacturers.name as manufacturer_name',
         DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount','dosage_forms.name as dname')
        ->where('applications.application_id',$id)
        ->where('contacts.contact_type','Supplier')
        ->orderBy('invoices.invoice_number','ASC')
        ->get();

//dd($check_list);

        $count = Acknowledgment_letter_receipt_psur::where('RL_squential_number', '<>', null)->count();
        $count_sequence = $count + 1;
        $year = Date('Y');
        $zero_filled_counter = sprintf('%04d', $count_sequence);  
        $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;

    $country_contact_info = DB::table('countries')->where('id',$check_list[0]->country_id)
                           ->Orwhere('id',68)
                           ->select('countries.*','countries.id as countryid','countries.country_name as contact_country_name')
                           ->get();

      @$Supervisor_generated_Acknowledgemet_letter = DB::table('acknowledgment_letter_receipt_psurs')->where('psur_refrence__number',$Get_psur_reference_number->psur_refrence_number)->get();

                                                                                                                        


    if(  @$Supervisor_generated_Acknowledgemet_letter[0]->application_number != '')
    {
       $select_document_id = DB::table('documents')->where('id', $Supervisor_generated_Acknowledgemet_letter[0]->document_id)->get();
        @$path = $select_document_id[0]->path;
        //$number_days_receipts = $Supervisor_generated_Acknowledgemet_letter[0]->number_days_receipts;

    }
    else
    {
        $path='';
        $number_days_receipts  = '';
    }


return view('Acknowledgement_Letter_PSUR.Acknowledgement_Letter_post_marketing',

    [
        'check_list' =>  $check_list ,
        'country_contact_info' => $country_contact_info,
        'random_application_RL_squential_number'=> $random_application_RL_squential_number,
        'path' => $path,
       // 'number_days_receipts' => $number_days_receipts ,
        'dosage_forms' => $dosage_forms,
        'psur_reference_number' =>  $Get_psur_reference_number
    ]);
    
    
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
     * @param  \App\Models\Acknowledgment_letter_receipt_psur  $acknowledgment_letter_receipt_psur
     * @return \Illuminate\Http\Response
     */
    public function show(Acknowledgment_letter_receipt_psur $acknowledgment_letter_receipt_psur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acknowledgment_letter_receipt_psur  $acknowledgment_letter_receipt_psur
     * @return \Illuminate\Http\Response
     */
    public function edit(Acknowledgment_letter_receipt_psur $acknowledgment_letter_receipt_psur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acknowledgment_letter_receipt_psur  $acknowledgment_letter_receipt_psur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acknowledgment_letter_receipt_psur $acknowledgment_letter_receipt_psur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acknowledgment_letter_receipt_psur  $acknowledgment_letter_receipt_psur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acknowledgment_letter_receipt_psur $acknowledgment_letter_receipt_psur)
    {
        //
    }
}
