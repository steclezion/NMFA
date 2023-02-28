<?php
namespace App\Http\Controllers;
use App;
use App\Http\Controllers\FPDF;
use \Mpdf\Mpdf as PDFF;
use App\Models\applications;
use App\Models\Country;
use App\Models\documents;
use App\Models\dossier;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;
use DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Acknowledgement_letter;

use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Events\ApplicationReceiptionEvent;
use Illuminate\Support\Facades\Artisan;

class AcknowledgementLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save_acknowledgementLetter(Request $request)
    {
         
      try{

        $Application_Number  = Acknowledgement_letter::where('application_id',$request->application_id)->get();
         //dd($Application_Number);

        if(@$Application_Number[0]->application_number == '')
        {
            $count = Acknowledgement_letter::where('RL_squential_number', '<>', null)->count();
            $count_sequence = $count + 1;
            $year = Date('Y');
            $zero_filled_counter = sprintf('%04d', $count_sequence);
            $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;
            $random_application_RL_squential_name= 'NMFA_RL_'.$year."_".$zero_filled_counter;
            $request->RL_squential_number = $random_application_RL_squential_number;

            $time=time(); $date= date("Y-m-d",$time);
            $Acknowledgement_letter = new Acknowledgement_letter;
            $Acknowledgement_letter->application_id = $request->application_id;
            $Acknowledgement_letter->RL_squential_number= $request->RL_squential_number;
            $Acknowledgement_letter->date=  $date;
            $Acknowledgement_letter->applicant_name= $request->applicant_name;
            $Acknowledgement_letter->region_state= $request->state_plot_number;
            $Acknowledgement_letter->region_state= $request->country;
            $Acknowledgement_letter->contact_person_name= $request->contact_person_name;
            $Acknowledgement_letter->application_number= $request->application_number;
            $Acknowledgement_letter->number_days_receipts = $request->number_days_receipts;
            $Application_ =  $Acknowledgement_letter->save();



       if ( $Application_ == true)
       {
         $path = public_path('storage/Acknowledgement_Letter/System_Generated_Documents/');
         $rendered_html_data = $this->rendered_html_data($request);

    

         $time=time();
         $file_name = 'AcknowledgementLetter.pdf';
         $uploaded_file_name = $time.$file_name;
         $document = new PDFF([ 'format'=>"A4", 'margin_header'=>"1", 'margin_top'=>"30", 'margin_bottom'=>"20", 'margin_footer'=>"2", ]);
         $header=['Content-Type'=> 'application/pdf','Content-Disposition'=>'inline: filename=""'];
         $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
         $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');
         $document->WriteHTML($rendered_html_data);
         Storage::disk('Acknowledgement_Letter')->put($uploaded_file_name,$document->Output($uploaded_file_name,"S"));
         $generated_pdf_link = Storage::url('public/Acknowledgement_Letter/System_Generated_Documents/'.$uploaded_file_name);

     
      //Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $uploaded_file_name;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '9';
$documents->ref_num = $random_application_RL_squential_name;
$documents->description = '--fafadsf';
$documents->save();

         // Uses to insert data in to the Document Selections
$select_document_id = DB::table('documents') ->where('name',  $uploaded_file_name)->get();

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

// Uses to insert data in to the Acknowledgment Letter
 $update_acknowledgement_letter = DB::table('acknowledgement_letters')
->where('acknowledgement_letters.application_number', $request->application_number)
->update(['document_id' => $select_document_id[0]->id]);



// dd($select_data_applications[0]->dossier_actual_path);
//Uses to update the dossier Path



$application_retreive = DB::table('applications')
        ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
        ->leftjoin('company_suppliers','applications.application_id','=','company_suppliers.application_id')
        ->leftjoin('invoices','applications.application_id','=','invoices.application_id')
        ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
        ->leftjoin('checklists','checklists.application_id','applications.application_id')
        ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
        ->select('checklists.*','applications.*','invoices.*','contacts.*', 'medicines.product_name','applications.id as app_id',
        'medicinal_products.product_trade_name', 
         DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname_contact'),
        'company_suppliers.trade_name','invoices.invoice_number','invoices.remark','invoices.amount')
        ->where('applications.application_id',$request->application_id)
        ->where('contacts.contact_type','Supplier')
        ->get();





$dossier = new dossier;
$dossier->description = $application_retreive[0]->product_trade_name ;
$dossier->path = $select_data_applications[0]->dossier_actual_path;
$dossier->save();




//Uses to update the applicatiions Progress Percentage
if($select_data_applications[0]->application_type == 1) { $progress_percentage = 10;}
else if($select_data_applications[0]->application_type ==2) {$progress_percentage = 20;}

$update_applications = DB::table('applications')
->where('applications.application_id', $request->application_id)
->update([
    'progress_percentage' => $progress_percentage ,
    'dossier_id' => $dossier->id
]);




$select_app_id = DB::table('applications')->select('applications.id as app_id')->where('applications.application_id',$request->application_id)->first();

$update_main_task_for_completion = DB::table('main_tasks')->where('main_tasks.related_id', $select_app_id->app_id)->where('task_name',  'Application')->update(['task_duration_days_actual' => NOW()]);



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
$new_notification['type']='Notification';
$new_notification['data']=' Assesor Submited screening Report';
$new_notification['from_user'] = 'System Notification';
$new_notification['subject']='Application Screening Report has been completed';
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







    public function save_rejection_letter(Request $request)
    {
         
      try{

        $Application_Number  = Acknowledgement_letter::where('application_id',$request->application_id)->get();
         //dd($Application_Number);

        if(@$Application_Number[0]->application_number == '')
        {
            $count = Acknowledgement_letter::where('RL_squential_number', '<>', null)->count();
            $count_sequence = $count + 1;
            $year = Date('Y');
            $zero_filled_counter = sprintf('%04d', $count_sequence);
            $random_application_RL_squential_number= 'NMFA/RL/'.$year."/".$zero_filled_counter;
            $random_application_RL_squential_name= 'NMFA_RL_'.$year."_".$zero_filled_counter;
            $request->RL_squential_number = $random_application_RL_squential_number;

            $time=time(); $date= date("Y-m-d",$time);
            $Acknowledgement_letter = new Acknowledgement_letter;
            $Acknowledgement_letter->application_id = $request->application_id;
            $Acknowledgement_letter->RL_squential_number= $request->RL_squential_number;
            $Acknowledgement_letter->date=  $date;
            $Acknowledgement_letter->applicant_name= $request->applicant_name;
            $Acknowledgement_letter->region_state= $request->state_plot_number;
            $Acknowledgement_letter->region_state= $request->country;
            $Acknowledgement_letter->contact_person_name= $request->contact_person_name;
            $Acknowledgement_letter->application_number= $request->application_number;
            $Acknowledgement_letter->number_days_receipts = '000';
            $Application_ =  $Acknowledgement_letter->save();



       if ( $Application_ == true)
       {
         $path = public_path('storage/Acknowledgement_Letter/System_Generated_Documents/');
         $rendered_html_data = $this->rendered_html_data($request);

    

         $time=time();
         $file_name = 'AcknowledgementLetter_rejected.pdf';
         $uploaded_file_name = $time.$file_name;
         $document = new PDFF([ 'format'=>"A4", 'margin_header'=>"1", 'margin_top'=>"30", 'margin_bottom'=>"20", 'margin_footer'=>"2", ]);
         $header=['Content-Type'=> 'application/pdf','Content-Disposition'=>'inline: filename=""'];
         $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
         $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');
         $document->WriteHTML($rendered_html_data);
         Storage::disk('Acknowledgement_Letter')->put($uploaded_file_name,$document->Output($uploaded_file_name,"S"));
         $generated_pdf_link = Storage::url('public/Acknowledgement_Letter/System_Generated_Documents/'.$uploaded_file_name);

     
      //Uses to insert data in to the Document Selections

$documents = new documents;
$documents->name =  $uploaded_file_name;
$documents->path =  $generated_pdf_link ;
$documents->document_type = '9';
$documents->ref_num = $random_application_RL_squential_name;
$documents->description = '--fafadsf';
$documents->save();

  // Uses to insert data in to the Document Selections
$select_document_id = DB::table('documents') ->where('name',  $uploaded_file_name)->get();

// Uses to insert data in to the Applications Selections
$select_data_applications= DB::table('applications') ->where('application_id', $request->application_id)->get();

// Uses to insert data in to the Acknowledgment Letter
 $update_acknowledgement_letter = DB::table('acknowledgement_letters')
->where('acknowledgement_letters.application_number', $request->application_number)
->update(['document_id' => $select_document_id[0]->id]);





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


        $update_applications = DB::table('applications')
        ->where('applications.application_id', $request->application_id)
        ->update([
            'application_status' => 'Preliminary screening rejected' ,
            
        ]);



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
$new_notification['type']='Notification';
$new_notification['data']=' Assesor Submited screening Report ';
$new_notification['from_user'] = 'System Notification';
$new_notification['subject']='Application Screening has been rejected';
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














    public function index()
    {
        //
    }



    public function rendered_html_data($request)
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
                      <h4> </h4>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- info row -->
      <div class='row invoice-info'>
    $request->acknowledegment_template
     </div>
      </form>
        </div>
    </div>
        </section>";
    
    return $rendered_html_data;
    
      return $rendered_template;
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
     * @param  \App\Models\Acknowledgement_letter  $acknowledgement_letter
     * @return \Illuminate\Http\Response
     */
    public function show(Acknowledgement_letter $acknowledgement_letter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acknowledgement_letter  $acknowledgement_letter
     * @return \Illuminate\Http\Response
     */
    public function edit(Acknowledgement_letter $acknowledgement_letter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acknowledgement_letter  $acknowledgement_letter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acknowledgement_letter $acknowledgement_letter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acknowledgement_letter  $acknowledgement_letter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acknowledgement_letter $acknowledgement_letter)
    {
        //
    }



    public function upload_file_acknowledgement_reject(Request $request)
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
              $filePath = $request->file('file')->storeAs('Acknowledgement_Letter/Document_Uploaded_To_Applicant/', $fileName, 'public');
              $generated_pdf_link = Storage::url('public/Acknowledgement_Letter/Document_Uploaded_To_Applicant/'.$fileName);
      
      
      
          //$generated_pdf_link = Storage::url($path.$fileName);
         //Uses to insert data in to the Document Selections
      
        $documents = new documents;
        $documents->name =  $fileName;
        $documents->path =  $generated_pdf_link ;
        $documents->document_type = '11';
        $documents->ref_num = $reference_number;
        $documents->description = 'Letter of rejection for Preliminary screening ';
        $documents->save();
      
        $application=applications::where('application_id', $request->application_id)->first();
      
      
        $user=User::where('id', $request->applicant_user_id)->first();
      
        
        // ::send($users, new ($invoice));
        $new_notification=[];
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='Application has been rejected';
        $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
        $new_notification['data']=  'Rejection letter for application No:'. $application->application_number.' ' ; 
        $new_notification['related_document'] = $documents->ref_num;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
        
        
        
        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
      
        
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
        $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
        $new_notification['data']='Assesor rejected Preliminary Screening';
        $new_notification['subject']='Preliminary Screeninig rejected';
        $new_notification['alert_level']='high';
        $new_notification['related_id'] = $request->application_id;
        $new_notification['related_document']=  '';
        $new_notification['remark']='remark';
        // ::send($users, new ($invoice));
        
        
        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
        event(new ApplicationReceiptionEvent($user->id, ' Assesor completed Preliminary Screening' . ' ' . $user->first_name . ' ' . $user->first_name));
        
        }
        }
        }
        }
      
      
        event(new ApplicationReceiptionEvent($user->id, 'Acknowledgement for application completion for  '. $request->application_number));
        
        //	task_status
      
        $update_applications = DB::table('acknowledgement_letters')
      ->where('acknowledgement_letters.application_number', $request->application_number)
      ->update([
          'applicant_user_id' => $request->applicant_user_id,
          'assessor_user_id' => $request->assesor_user_id ,
          'uploaded_applicant_document_id'=>  $documents->id
      
      ]);
      
      
      
      $application=applications::where('application_id',$request->application_id)->first();
      
      //dd($request->all());
      
      $update_applications_status = DB::table('applications')
      ->where('application_id', $request->application_id)
      ->update([
          'application_status' => 'Preliminary screening rejected',
      ]);
      
      
      $update_main_task_status = DB::table('main_tasks')
      ->where('related_id', $application->id)
      ->where('related_task', 'Application')
      ->update([
          'task_status' => 'completed',
      ]);
      
      
      
      $doc_upload_acknowledgement_letters_document  = Acknowledgement_letter::join('documents','documents.id',
        'acknowledgement_letters.uploaded_applicant_document_id')
        ->select('documents.*','acknowledgement_letters.*','documents.name as dname',
        'documents.created_at as uploaded_Date', 'documents.id as did')
        ->where('acknowledgement_letters.application_number','=',$request->application_number)
        ->where('documents.document_type','=',11)
        ->get();
        
        //dd( $issue_queries);
        
        
        $i=1;   $return_data='';
        foreach($doc_upload_acknowledgement_letters_document as $user_upload)
            
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
      
      
      //return response()->json(['Message'=>true,'Download_Link'=>$documents->path]);
               }
      
               catch(Exception $e)
               {
      
                  return response()->json(['Message'=>false,'item'=>'error'.$e]);
      
               }


    }


    public function upload_file_acknowledgement(Request $request)
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


        $name = $request->file('file')->getClientOriginalName();

        // $size = Storage::size( $request->file('file')  );
        // dd($size);

        $time=time();
        // $path = $request->file('file')->store('public/images');
        $path = public_path('storage/Acknowledgement_Letter/Document_Uploaded_To_Applicant/');

        // <--- folder to store the pdf documents into the server;
        $fileName =  $name."-".$request->app_name.$time.".".$request->file('file')->extension();  // <--giving the random filename,
       
        $reference_number = $name."-".$request->app_name;
        $filePath = $request->file('file')->storeAs('Acknowledgement_Letter/Document_Uploaded_To_Applicant/', $fileName, 'public');
        $generated_pdf_link = Storage::url('public/Acknowledgement_Letter/Document_Uploaded_To_Applicant/'.$fileName);



    //$generated_pdf_link = Storage::url($path.$fileName);
   //Uses to insert data in to the Document Selections

  $documents = new documents;
  $documents->name =  $fileName;
  $documents->path =  $generated_pdf_link ;
  $documents->document_type = '11';
  $documents->ref_num = $reference_number;
  $documents->description = 'Upload Acknowledgement Letter For Preliminary Screening To Applicant';
  $documents->save();

  $application=applications::where('application_id', $request->application_id)->first();


  $user=User::where('id', $request->applicant_user_id)->first();

  
  // ::send($users, new ($invoice));
  $new_notification=[];
  $new_notification['type'] = 'Notification';
  $new_notification['subject'] ='Application completion acknowledgement';
  $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
  $new_notification['data']=' Acknowledgement  for application completion for application No:  '.$application->application_number.' ' ; 
  $new_notification['related_document'] = $documents->ref_num;
  $new_notification['related_id'] = $request->application_id;
  $new_notification['alert_level'] = null;
  $new_notification['remark'] = null;
  
  
  
  Notification::send($user, new ApplicationReceiptionNotification($new_notification));

  
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
  $new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
  $new_notification['data']='Assesor completed Preliminary Screening';
  $new_notification['subject']='Screening completion';
  $new_notification['alert_level']='high';
  $new_notification['related_id'] = $request->application_id;
  $new_notification['related_document']=  '';
  $new_notification['remark']='remark';
  // ::send($users, new ($invoice));
  
  
  Notification::send($user, new ApplicationReceiptionNotification($new_notification));
  event(new ApplicationReceiptionEvent($user->id, ' Assesor completed Preliminary Screening' . ' ' . $user->first_name . ' ' . $user->first_name));
  
  }
  }
  }
  }


  event(new ApplicationReceiptionEvent($user->id, 'Acknowledgement for application completion for  '. $request->application_number));
  
  //	task_status

  $update_applications = DB::table('acknowledgement_letters')
->where('acknowledgement_letters.application_number', $request->application_number)
->update([
    'applicant_user_id' => $request->applicant_user_id,
    'assessor_user_id' => $request->assesor_user_id ,
    'uploaded_applicant_document_id'=>  $documents->id

]);



$application=applications::where('application_id',$request->application_id)->first();

//dd($request->all());

$update_applications_status = DB::table('applications')
->where('application_id', $request->application_id)
->update([
    'application_status' => 'Preliminary screening completed',
]);


$update_main_task_status = DB::table('main_tasks')
->where('related_id', $application->id)
->where('related_task', 'Application')
->update([
    'task_status' => 'completed',
]);



$doc_upload_acknowledgement_letters_document  = Acknowledgement_letter::join('documents','documents.id',
  'acknowledgement_letters.uploaded_applicant_document_id')
  ->select('documents.*','acknowledgement_letters.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgement_letters.application_number','=',$request->application_number)
  ->where('documents.document_type','=',11)
  ->get();
  
  //dd( $issue_queries);
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_acknowledgement_letters_document as $user_upload)
      
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


//return response()->json(['Message'=>true,'Download_Link'=>$documents->path]);
         }

         catch(Exception $e)
         {

            return response()->json(['Message'=>false,'item'=>'error'.$e]);

         }



    }






    public function fetch_uploaded_acknowledgement_letter_if_any(Request $request)
    {

      //  dd($request->all());

  $doc_upload_acknowledgement_letters_document  = Acknowledgement_letter::join('documents','documents.id',
  'acknowledgement_letters.uploaded_applicant_document_id')
  ->select('documents.*','acknowledgement_letters.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgement_letters.application_number','=',$request->application_number)
  ->where('documents.document_type','=',11)
  ->get();
  
  //dd( $issue_queries);
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_acknowledgement_letters_document as $user_upload)
      
  {
  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
  
  
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='deletefile'
  data-document_id='$user_upload->did' 
  data-id='$request->application_id' 
  data-original-title='Delete'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
  }
  
  
  
  return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);
  
 
    }



    public function delete_file_uploaded_acknowledgment_letter(Request $request)
    {

      //  dd($request->all());
      $documents = new documents;
      $documents= documents::find($request->document_id);
      $documents->delete();


  $doc_upload_acknowledgement_letters_document  = Acknowledgement_letter::join('documents','documents.id',
  'acknowledgement_letters.uploaded_applicant_document_id')
  ->select('documents.*','acknowledgement_letters.*','documents.name as dname',
  'documents.created_at as uploaded_Date', 'documents.id as did')
  ->where('acknowledgement_letters.application_number','=',$request->application_number)
  ->where('documents.document_type','=',11)
  ->get();
  
  //dd( $issue_queries);
  
  
  $i=1;   $return_data='';
  foreach($doc_upload_acknowledgement_letters_document as $user_upload)
      
  {
  $return_data .= "<tr><td>".$i++."</td>";
  $return_data .= "<td id='seqence_number_$user_upload->id' >
  
  <a  href='".$user_upload->path."' style='display:block'   title='Acknowledgment receipt of Application Registration '   id='Download_File' >   ".$user_upload->dname."</a>
  
  
  </td>";
  $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
  
  $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='deletefile'
  data-document_id='$user_upload->did' 
  data-id='$request->application_id' 
  data-original-title='Delete'  class='edit btn btn-danger btn-sm deleteFile'> <i class='fas fa-trash'></i> Remove </a></td>";    
  }
  
  
  
  return response()->json(['Message'=>true,'Download_Link'=>@$user_upload->path,'Data_returned'=>$return_data ]);
  
 
    }


}
