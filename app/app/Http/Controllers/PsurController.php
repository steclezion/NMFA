<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Auth;

use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\psur;
use App\Models\psur_alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\FPDF;
use App\Models\applications;
use App\Models\Country;
use App\Models\agents_template;
use App\Models\company_suppliers_template;
use App\Models\payment_configuration;
use App\Models\invoices;
use App\Models\declerations;
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
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Events\ApplicationReceiptionEvent;
use Hash;
use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Models\documents;
use DataTables;

class PsurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public $i=1;


     public function psur_reviewed_report()
     {
        $return_data='';

    $applications = applications::leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
               ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
              ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
              ->leftjoin('users','users.id','applications.user_id')
              ->leftjoin('contacts','contacts.application_id','applications.application_id')
              ->leftjoin('psurs','psurs.application_id','applications.application_id')
              ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
              ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
              ->leftjoin('certifications','decisions.id','certifications.decision_id')
              ->select(
              'psurs.id as psurid','medicinal_products.product_trade_name as t_name',
              'psurs.*','certifications.*','certifications.registration_number as regnumber',
              'medicines.product_name as med_name',
              'psurs.assigned_To as p_assigned_to',
              'medicinal_products.*',
              'medicinal_products.product_trade_name as t_name',
              'company_suppliers.*',
              'company_suppliers.trade_name as cs_tradename',
              'applications.*',
              'contacts.*',
              'contacts.first_name as cfirst_name',
              'contacts.middle_name as cmiddle_name',
              'contacts.last_name as clast_name')
              ->where('contacts.contact_type','=','Supplier')
              ->where('psurs.psur_review_uploaded_id','!=' ,null)
              ->orderBy('applications.application_number','ASC')
              ->get();


              $disabled = ''; $return_data ="";
              foreach($applications as $application)

   
            {

                $doc_upload_psur_document  = psur::join('documents','documents.id','psurs.psur_document_id')
              ->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
              ->where('psurs.application_id','=',$application->application_id)
              ->where('documents.document_type','=',31)
              ->first();


              $psur_review_uploaded = psur::join('documents','documents.id','psurs.psur_review_uploaded_id')
              ->select('documents.*',
              'psurs.*',
              'documents.name as dname',
              'documents.created_at as uploaded_Date', 
              'documents.id as did')
              ->where('psurs.psur_review_uploaded_id','=',$application->psur_review_uploaded_id)
              ->where('documents.document_type','=',35)
              ->first();

              


              $reviewer_name = User::select('users.*')->where('users.id','=',$application->p_assigned_to)->first();

            //   dd($reviewer_name);

              @$psur_review_uploaded_path =  $psur_review_uploaded->path;


              if($psur_review_uploaded_path = '') {$disabled = 'disabled';} else  {$disabled = '';}

if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }

 


              $explode = explode(',', $application->hold_progress_wizard); 

             

           

              $return_data .= 
              "<tr> 

              <td>".$this->i++."</td>";
              $return_data .= "<td>".$application->regnumber."</td>";
              $return_data .= "<td>".$application->psur_refrence_number."</td>";
              $return_data .= "<td>".$application->med_name."</td>";
              $return_data .= "<td>".$application->t_name."</td>";
              $return_data .= "<td >".$application->cs_tradename."</td>";
             
             
              $return_data .= "<td  >".@$reviewer_name->first_name .' '.@$reviewer_name->middle_name.' '.@$reviewer_name->last_name ."</td>";
             
     
              
              $return_data .= "<td>  <a target='_blank' href='$doc_upload_psur_document->path' data-toggle='tooltip' id='query'  title='Download PSUR from applicant' data-original-title='Edit' class='edit btn btn-warning btn-sm' > <i class='fas fa-download'></i>  </a></td>";
             
              if(isset($psur_review_uploaded->path)) 
              {

                $return_data .= "<td> <a target='_blank'  href='$psur_review_uploaded->path' data-toggle='tooltip' id='query'  title='Download PSUR from the reviewer' data-original-title='Edit'   class='edit btn btn-success btn-sm' > <i class='fas fa-download'></i>  </a></td>";
              
              }
              else

              {

                $return_data .= "<td> <a disabled  href='javascript:void(0)' data-toggle='tooltip' id='query'  title='Reviewer hasnot uploaded the result yet!' data-original-title='Edit'   $disabled  class='edit badge badge-info badge-sm' > <i class='fas fa-magic'></i>Pending...   </a></td>";

              }

              $return_data .="</tr>";
         }


 

   
   return view('psur_assign_unassign.psur_reviewed_report',[
              'return_data' => $return_data,
 ]);


     }

public function fetch_review_report_of_PSUR(Request $request)
{

        $doc_upload_psur_document  = psur::join('documents',
        'documents.id','psurs.psur_review_uploaded_id')
        ->select('documents.*','psurs.*','documents.name as dname',
        'documents.created_at as uploaded_Date', 'documents.id as did')
        ->where('psurs.psur_refrence_number','=',$request->psur_refrence_number)
        ->where('documents.document_type','=',35)
        ->get();

        
 $i=1;   $return_data='';
 foreach($doc_upload_psur_document as $user_upload)
     
 {
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'
 title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
 
 $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
 
 $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
 data-id='$user_upload->did' 
 title='Delete PSUR'
 data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
 }

 return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);
 
}




public function Download_file()
{
    $pathToFile = '/templates/Template 2.9  Assessment Report Form - PSUR.docx';
    return response()->download(public_path($pathToFile));

}





  public function Upload_review_report_of_PSUR(Request $request)
  {


 
  try
 {
 $upload_id = User::where('id', '=', auth()->user()->id);
 $name = $request->file('file')->getClientOriginalName();
 $time=time();
 $path = public_path('Storage/UploadPSURReviewReport/');
 $fileName =  $name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
 $filePath = $request->file('file')->storeAs('UploadPSURReviewReport/', $fileName, 'public');
 $generated_pdf_link = Storage::url('public/UploadPSURReviewReport/'.$fileName);
 


 $documents = new documents;
 $documents->name =  $fileName;
 $documents->path =  $generated_pdf_link;
 $documents->document_type = '35';
 $documents->ref_num =  $fileName;
 $documents->description = 'Upload psur review report';
 $documents->save();
 
 
psur::where('psur_refrence_number', $request->psur_refrence_number) ->update(
    [
        'psur_review_uploaded_id' => $documents->id,
        'psur_review_uploaded_by' => auth()->user()->id,
      
    ]);


 
 $doc_upload_psur_document  = psur::join('documents',
 'documents.id','psurs.psur_review_uploaded_id')
 ->select('documents.*','psurs.*','documents.name as dname',
 'documents.created_at as uploaded_Date', 'documents.id as did')
 ->where('psurs.psur_refrence_number','=',$request->psur_refrence_number)
 ->where('documents.document_type','=',35)
 ->get();
 



 $application=applications::where('application_id',$request->application_id)->first();

 $get_supervisor_id = psur::where('application_id',$request->application_id)->first();
 
 $dataa = User::where('id',$get_supervisor_id->assigned_By)->orderBy('id','ASC')->get();


 $new_notification=[];
 $new_notification['type']='Notification';;
 $new_notification['data']='PERC member uploaded review report';
 $new_notification['subject']='Reviewed report file';
 $new_notification['from_user'] = 'System Notification';
 $new_notification['alert_level']='high';
 $new_notification['related_document']=  '';
 $new_notification['related_id'] = $application->id;
 $new_notification['remark']='remark';
 // ::send($users, new ($invoice));
 Notification::send($dataa, new ApplicationReceiptionNotification($new_notification));
 event(new ApplicationReceiptionEvent($get_supervisor_id->assigned_By, 'PERC member uploaded review report'));

 
 
 
 $i=1;   $return_data='';
 foreach($doc_upload_psur_document as $user_upload)
     
 {
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'
 title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
 
 $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
 
 $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
 data-id='$user_upload->did' 
 title='Delete PSUR'
 data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
 }
 return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);
 
   }
 
 catch(Exception $e)
 {
 return response()->json(['Message'=>false,'item'=>'error'.$e]);
 }
 
}








    public function  perc_psur_status_list(Request $request)
    {
        $return_data ="" ; $i =1;$badge ='';



       $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');

       $applications = applications::where('applications.re_registration_number','<>','')
           //    ->leftjoin('manufacturers','manufacturers.application_id','applications.application_id')
   
              ->leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
              ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
              ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
              ->leftjoin('users','users.id','applications.user_id')
              ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
              ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
              ->leftjoin('certifications','decisions.id','certifications.decision_id')
              ->join('contacts','contacts.application_id','applications.application_id')
              ->join('psurs','psurs.application_id','applications.application_id')
              ->select('psurs.id as psurid','psurs.*','medicinal_products.*','certifications.registration_number as regnumber',
              'medicinal_products.product_trade_name as t_name','company_suppliers.*',  'medicines.product_name as med_name',
              'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*','contacts.first_name as cfirst_name',
              'contacts.middle_name as cmiddle_name','contacts.last_name as clast_name')
              ->where('contacts.contact_type','=','Supplier')
              ->where('psurs.assigned_To','=',auth()->user()->id)
              ->orderBy('applications.application_number','ASC')
              ->get();



              $doc_upload_psur_document  = psur::join('documents','documents.id','psurs.psur_document_id')
              ->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
              ->where('psurs.application_id','=',$request->application_id)
              ->where('documents.document_type','=',31)
              ->get();


           //dd($application->psurid);


              foreach($applications as $application)

   
            {
                $doc_upload_psur_document  = psur::join('documents','documents.id','psurs.psur_document_id')
              ->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
              ->where('psurs.application_id','=',$application->application_id)
              ->where('documents.document_type','=',31)
              ->first();

             

              if($application->application_status == 'processing') {  $badge = 'badge bg-warning'; }
              elseif($application->application_status == 'Preliminary screening completed') { $badge = 'badge bg-success'; $application->application_status= 'Dossier Evaluation in progress'; }
              elseif($application->application_status == 'Preliminary screening rejected') { $badge = 'badge bg-danger'; }
              
               


              $explode = explode(',', $application->hold_progress_wizard); 
              $return_data .= "<tr><td>".$i++."</td>";
              $return_data .= "<td>".$application->regnumber."</td>";
              $return_data .= "<td>".$application->psur_refrence_number."</td>";
           
              $return_data .= "<td>".$application->med_name ."</td>";
              $return_data .= "<td>".$application->product_trade_name ."</td>";
              $return_data .= "<td>".$application->trade_name ."</td>";
              $return_data .= "<td hidden >".$application->first_name.' '.$application->middle_name.' '.$application->last_name ."</td>";
             
            //   $return_data .=" <td id='seqence_number'> <span class='$badge'> $application->application_status  </span></td>";

              $return_data .= 
              "<td>
               <a href='$doc_upload_psur_document->path'
                data-toggle='tooltip' id='query' 
              title='Download PSUR' data-original-title='Edit' class='edit btn btn-warning btn-sm' >
              <i class='fas fa-download'></i> 
              </a>
<a href='javascript:void(0)' data-toggle='tooltip' id='query' 
data-id='$application->psurid'  
data-app_id='$application->application_id' 
data-psur_refrence_number='$application->psur_refrence_number'  

title='Upload PSUR'  data-original-title='Edit'  class='edit btn btn-primary btn-sm uploadreviewreport'> <i class='fas fa-upload'></i> </a>
              
</td>

              </tr>";
         }


         $dataa = User::where('id',auth()->user()->id)->orderBy('id','ASC')->get();



   return view('PERC.perc_psur_assigned_list',[
       'company_suppliers_template' =>$company_suppliers_template,'applications'=>$applications,
       'application_id' => @$applications[0]->application_id,
       'return_data' => $return_data,
       'dataa'=>$dataa
        ]);


    }



  
  
  
  
     public function  psur_acknowledgment_list(Request $request)
     {
        $company_suppliers_template = company_suppliers_template::all()->sortBy('trade_name');
        
        $applications = applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
               ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
               ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
               ->join('users','users.id','applications.user_id')
               ->join('contacts','contacts.application_id','applications.application_id')
               ->join('psurs','psurs.application_id','applications.application_id')
               ->join('documents','documents.id','psurs.psur_document_id')
               ->join('dossier_assignments','applications.id','dossier_assignments.application_id')
               ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
               ->join('certifications','decisions.id','certifications.decision_id')
               ->leftjoin('acknowledgment_letter_receipt_psurs','acknowledgment_letter_receipt_psurs.psur_refrence__number',
               'psurs.psur_refrence_number')
               ->select('medicinal_products.*',
               'medicinal_products.product_trade_name as t_name',
               'medicines.product_name as med_name',
               'company_suppliers.*','certifications.*','certifications.registration_number as regnumber',
               'company_suppliers.trade_name as cs_tradename',
               'applications.*','applications.application_number as app_number','applications.application_id as app_id',
               'contacts.*','contacts.first_name as cfirst_name',
               'contacts.middle_name as cmiddle_name',
               'contacts.last_name as clast_name','psurs.*','documents.*','psurs.id as psurid',
               'acknowledgment_letter_receipt_psurs.*')
               ->where('contacts.contact_type','=','Supplier')
               ->orderBy('applications.application_number','ASC')
               ->get();
  
    //dd( $applications );

    return view('Acknowledgement_Letter_PSUR.Acknowledgment_letter_list',[
        'company_suppliers_template' =>$company_suppliers_template,
        'applications'=>@$applications,
        'application_id' => @$applications[0]->application_id,
        ]);


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


        public function assigned_psur(Request $request)
        {


            $dataa = User::orderBy('id','ASC')->get();
           
           
            if ($request->ajax())
    {
    
        $data = applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->join('users','users.id','applications.user_id')
        ->join('medicines','medicinal_products.medicine_id','medicines.id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->join('psurs','psurs.application_id','applications.application_id')
        ->join('documents','psurs.psur_document_id','documents.id')
        ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
               ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
               ->leftjoin('certifications','decisions.id','certifications.decision_id')
        ->select('documents.*',
          'applications.application_number',
          'psurs.assigned_To as assginto', 'psurs.assigned_By as assginby', 
          'psurs.Assginment_Date as assginmentdate',
           DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname'),
          'medicinal_products.*', 
          'psurs.*','certifications.*','certifications.registration_number as regnumber',
          'medicinal_products.product_trade_name as t_name',
          'medicines.*',
          'company_suppliers.*',
          'company_suppliers.trade_name as cs_tradename',
          'applications.*',
          'contacts.*',
          'contacts.first_name as cfirst_name',
          'contacts.middle_name as cmiddle_name',
          'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('psurs.assigned_To','<>',NULL)
        ->orderBy('applications.application_number','ASC')
        ->get();
    
     
    
               // $data = Book::latest()->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('assigned_To', function($row){
                            
                            $assinged_To_full_Name=User::select('users.*')->where('id','=',$row->assginto)->get();
                            
                            return @$assinged_To_full_Name[0]->first_name." ".@$assinged_To_full_Name[0]->middle_name." ".@$assinged_To_full_Name[0]->last_name;
                             })
    
            ->addColumn('assigned_By', function($row)
                {
            $assinged_By_full_Name=User::select('users.*')->where('id','=',$row->assginby)->get();
            
            return @$assinged_By_full_Name[0]->first_name." ".@$assinged_By_full_Name[0]->middle_name." ".@$assinged_By_full_Name[0]->last_name;
                         })
    
                        ->addColumn('action', function($row){
       
        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  
        data-id="'.$row->application_id.'" 
        data-deadline="'.$row->deadline.'" 
        data-psur_refrence_number="'.$row->psur_refrence_number.'"
        title="Assign"
         class="edit btn btn-warning btn-sm editAssign"> 
        <span class="fas fa-edit"> </span> </a>';
     
        // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
        
        return $btn;
                        })

                        ->addColumn('path_psur', function($row){
   
                            $btn = '<a href='.$row->path.' data-toggle="tooltip"  data-id="'.$row->application_id.'"
                                    title="Download PSUR file" class="edit btn btn-success btn-sm"> 
                                    
                                    <span class="fas fa-download"> </span> </a>';
                         
                            // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
                            
                            return $btn;
                                            })


                        ->addColumn('application_status', function($row){
       
                            if($row->application_status == 'processing') { $title='processing'; $badge = 'badge bg-warning' ;$application_stat="processing"; }
                            elseif($row->application_status == 'Preliminary screening completed') { $title='Preliminary screening completed';  $badge = 'badge bg-success';$application_stat="PSC"; }
                            elseif($row->application_status == 'Preliminary screening rejected') { $title='Preliminary screening rejected'; $badge = 'badge bg-danger';$application_stat="PSR"; }
                           
                            $btn = "<span class='$badge'  title='$title'>  $application_stat  </span>";

                         return $btn;
                         })
                         ->rawColumns(['action','application_status','path_psur'])
                        ->make(true);
    }
          
            //return view('assign_unassign');
    
            return view('psur_assign_unassign.assigned',compact('dataa'));
    
            

            

        }

    public function un_assigned_psur(Request $request)
    {

       
        $dataa = User::orderBy('id','ASC')->get();
        
   
        if ($request->ajax())
{


    

    $data = applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('users','users.id','applications.user_id')
    ->join('medicines','medicinal_products.medicine_id','medicines.id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->join('psurs','psurs.application_id','applications.application_id')
    ->join('documents','psurs.psur_document_id','documents.id')
    ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
               ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
               ->leftjoin('certifications','decisions.id','certifications.decision_id')
    ->select('documents.*','applications.application_number','psurs.assigned_To as assginto', 'psurs.assigned_By as assginby', 
          'psurs.Assginment_Date as assginmentdate','medicines.*',  'medicines.product_name as med_name','certifications.registration_number as regnumber',
        DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname'),
        'medicinal_products.*', 'psurs.*',
        'medicinal_products.product_trade_name as t_name','company_suppliers.*',
        'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
        'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('psurs.assigned_To','=',NULL)
    ->orderBy('applications.application_number','ASC')
    ->get();

 

           // $data = Book::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('assigned_To', function($row){
                        $assinged_To_full_Name=User::select('users.*')
                        ->where('id','=',$row->assginto)
                        ->get();
                        return @$assinged_To_full_Name[0]->first_name." ".@$assinged_To_full_Name[0]->middle_name." ".@$assinged_To_full_Name[0]->last_name;
                         })

        ->addColumn('assigned_By', function($row)
            {
        $assinged_By_full_Name=User::select('users.*')
        ->where('id','=',$row->assginby)
        ->get();
        return @$assinged_By_full_Name[0]->first_name." ".@$assinged_By_full_Name[0]->middle_name." ".@$assinged_By_full_Name[0]->last_name;
                     })

                    ->addColumn('action', function($row){
   
    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" 
            data-id="'.$row->application_id.'"
            data-psur_refrence_number="'.$row->psur_refrence_number.'"
            title="Assign" class="edit btn btn-warning btn-sm editAssign"> 
            
            <span class="fas fa-edit"> </span> </a>';
 
    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
    
    return $btn;
                    })

                    ->addColumn('path_psur', function($row){
   
                        $btn = '<a href="'.$row->path.'" data-toggle="tooltip"  data-id="'.$row->application_id.'"
                                title="Download PSUR file" class="edit btn btn-success btn-sm"> 
                                
                                <span class="fas fa-download"> </span> </a>';
                     
                        // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';
                        
                        return $btn;
                                        })


                    ->addColumn('application_status', function($row){
   
                        if($row->application_status == 'processing') { $title='processing'; $badge = 'badge bg-warning' ;$application_stat="processing"; }
                        elseif($row->application_status == 'Preliminary screening completed') { $title='Preliminary screening completed';  $badge = 'badge bg-success';$application_stat="PSC"; }
                        elseif($row->application_status == 'Preliminary screening rejected') { $title='Preliminary screening rejected'; $badge = 'badge bg-danger';$application_stat="PSR"; }
                       
                        $btn = "<span class='$badge' title='$title'>  $application_stat  </span>";


                     return $btn;
                     })
                    ->rawColumns(['action','application_status','path_psur'])
                    ->make(true);
}
      
        //return view('assign_unassign');

        return view('psur_assign_unassign.unassigned',compact('dataa'));

 }




    public function upload_file_psur(Request $request)
    {
 //dd($request->all()) ;
 try
 {
 $upload_id = User::where('id', '=', auth()->user()->id);
 
 $psur = psur::where('application_id', '=', $request->application_id)
 ->where('nmfa_director_flag', '=', 0)
 ->orderBy('id','DESC')
 ->first();
 
 $applications  = applications::select('applications.*')->where('applications.application_id','=',$request->application_id)->first();
 $applications_count  = check_re_registered_application::select('check_re_registered_applications.*')->
 where('check_re_registered_applications.old_id','=',$request->application_id)->count();
 $applications_dossier_assined_year = $applications->dossier_actual_path;
 
 
 $year_explode =  explode('_',$applications_dossier_assined_year);
 if($applications->application_type == 1) { $application_type='SR';} else {$application_type ='FR';}
 $zero_filled_counter = sprintf('%04d', $applications_count);


 $uploaded_filename_by = $check_whether_it_Exists_before  = User::select('users.*',)->where('users.id','=',auth()->user()->id)->first();



 $applications  = applications::select('applications.*')->where('applications.application_id','=',$request->application_id)->first();



 $dossiers  = dossier_assignment::join('dossiers','dossiers.id','dossier_assignments.dossier_id')
 ->join('applications','applications.id','dossier_assignments.application_id')
 ->where('applications.id','=',$applications->id)
 ->select('dossiers.*','dossier_assignments.*')
 ->first();


 $psurs_= psur::select('psurs.*')->where('psurs.psur_dossier_referencing_number','=',$dossiers->dossier_ref_num)->count()  + 1;


  $psur_refrence_number = $dossiers->dossier_ref_num."/PSUR".$psurs_;
  

  $uploaded_filename_by = $check_whether_it_Exists_before  = User::select('users.*',)->where('users.id','=',auth()->user()->id)->first();

 
 
 
 $name = $request->file('file')->getClientOriginalName();
 $time=time();
 $path = public_path('Storage/Upload_alert_file/');
 //$fileName =  $name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
 $fileName =  "PSUR-FILE-".$uploaded_filename_by->first_name."-".$uploaded_filename_by->middle_name."-".$uploaded_filename_by->last_name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
 $filePath = $request->file('file')->storeAs('Uploadpsur/', $fileName, 'public');
 $generated_pdf_link = Storage::url('public/Uploadpsur/'.$fileName);
 
 $documents = new documents;
 $documents->name =  $fileName;
 $documents->path =  $generated_pdf_link;
 $documents->document_type = '31';
 $documents->ref_num =  $fileName;
 $documents->description = 'Upload psur  file';
 $documents->save();
 
 
 
 
 
 
 
 $psur = new psur;
 $psur->psur_refrence_number =  $psur_refrence_number;
 $psur->psur_dossier_referencing_number=  $dossiers->dossier_ref_num;
 $psur->psur_document_id =  $documents->id;
 $psur->nmfa_director_flag = 0;
 $psur->application_id = $request->application_id;
 $psur->save();
  
 
 $doc_upload_psur_document  = psur::join('documents',
 'documents.id','psurs.psur_document_id')
 ->select('documents.*','psurs.*','documents.name as dname',
 'documents.created_at as uploaded_Date', 'documents.id as did')
 ->where('psurs.application_id','=',$request->application_id)
 ->where('documents.document_type','=',31)
 ->get();
 
 $application=applications::where('application_id',$request->application_id)->first();
 
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
 $new_notification['data']='Applicant has sent PSUR  file';
 $new_notification['subject']='Applicant submitted  PSUR file';
 $new_notification['from_user'] = 'System Notification';
 $new_notification['alert_level']='high';
 $new_notification['related_document']=  '';
 $new_notification['related_id'] = $application->id;
 $new_notification['remark']='remark';
 // ::send($users, new ($invoice));
 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
 event(new ApplicationReceiptionEvent($user->id, 'Applicant submitted  PSUR file'));
 }
 }
 }
 }
 
 
 $i=1;   $return_data='';
 foreach($doc_upload_psur_document as $user_upload)
     
 {
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .= "<td  id='psur_ref_$user_upload->psur_refrence_number'>".$user_upload->psur_refrence_number."</td>";
 $return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank' title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
 $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
 $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
   data-id='$user_upload->did' 
   data-psur_reference='$user_upload->psur_refrence_number' 
   title='Delete PSUR' data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
}
 return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);
 
   }
 
 catch(Exception $e)
 {
 return response()->json(['Message'=>false,'item'=>'error'.$e]);
 }
 
 
  }





public function upload_file_psur___(Request $request)
{
//dd($request->all()) ;
try
{

$upload_id = User::where('id', '=', auth()->user()->id);


$check_whether_it_Exists_before  = psur::join('documents','documents.id','psurs.psur_document_id')->select('documents.*','psurs.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('psurs.application_id','=',$request->application_id)
->where('documents.document_type','=',31)
->count();

$uploaded_filename_by = $check_whether_it_Exists_before  = Users::select('users.*',)->where('users.id','=',auth()->user()->id)->first();



if( $check_whether_it_Exists_before >=1 )
{
    //$doc =DB::table('documents')->where('id', '=', $request->document_id)->delete();
        
    $psur =DB::table('psurs')->where('application_id', '=', $request->application_id)->delete();
}

$name = $request->file('file')->getClientOriginalName();
$time=time();
$path = public_path('Storage/Uploadpsur/');
$fileName =  "PSUR-FILE_".$uploaded_filename_by->first_name.$uploaded_filename_by->middle_name.$uploaded_filename_by->last_name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
$filePath = $request->file('file')->storeAs('Uploadpsur/', $fileName, 'public');
$generated_pdf_link = Storage::url('public/Uploadpsur/'.$fileName);

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link;
$documents->document_type = '31';
$documents->ref_num =  $fileName;
$documents->description = 'Upload PSUR file';
$documents->save();


$psur = new psur;
// $psur->name =  $fileName;
$psur->psur_document_id =  $documents->id;
$psur->application_id = $request->application_id;
$psur->nmfa_director_flag = 0;
// $psur->ref_num =  $fileName;
$psur->save();




$doc_upload_psur_document  = psur::join('documents',
'documents.id','psurs.psur_document_id')
->select('documents.*','psurs.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('psurs.application_id','=',$request->application_id)
->where('documents.document_type','=',31)
->get();


$i=1;   $return_data='';
foreach($doc_upload_psur_document as $user_upload)
       
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'
 title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";

$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
 data-id='$user_upload->did' 
 title='Delete PSUR'
 data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
}
return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);

     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}


    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



     public function fetch_psur_uploaded_files(Request $request)
{
try
{
$upload_id = User::where('id', '=', auth()->user()->id);

//dd($request->application_id);
$doc_upload_psur_document  = psur::join('documents','documents.id','psurs.psur_document_id')
->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
->where('psurs.application_id','=',$request->application_id)->where('documents.document_type','=',31)
->get();



$i=1;   $return_data='';
foreach($doc_upload_psur_document as $user_upload)
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .= "<td  id='psur_ref_$user_upload->psur_refrence_number' >".$user_upload->psur_refrence_number."</td>";
$return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'

title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";

$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
 data-id='$user_upload->did' 
 data-psur_reference='$user_upload->psur_refrence_number' 
 title='Delete PSUR'
 data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
}
return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);

     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function delete_file_data_uploaded_psur(Request $request)
    {
    
//dd($request->all());
$documents  = psur::join('documents','documents.id','psurs.psur_document_id')
->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
->where('psur_refrence_number', '=', $request->psur_reference)
->where('documents.document_type','=',31)
->first();



        $path = public_path('storage/app/public/Uploadpsur/'.$documents->dname);
        Storage::delete('public/Uploadpsur/'.$documents->dname);



        $doc =DB::table('documents')->where('id', '=', $request->document_id)->delete();
        
        $psur =DB::table('psurs')->where('psur_refrence_number', '=', $request->psur_reference)->delete();



        $return_data='';
         

        $doc_upload_psur_document  = psur::join('documents','documents.id','psurs.psur_document_id')
        ->select('documents.*','psurs.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
        ->where('psurs.application_id','=',$request->application_id)
        ->where('documents.document_type','=',31)
        ->get();
        
 
 
  $i=1;   $return_data='';
foreach( $doc_upload_psur_document as $user_upload)
{

    $return_data .=  "<tr><td>".$i++."</td>";
    $return_data .=  "<td id='psur_ref_$user_upload->psur_refrence_number'>".$user_upload->psur_refrence_number."</td>";
    $return_data .=  "<td id='seqence_number_$user_upload->id'> <a target='_blank'  href='".$user_upload->path."' style='display:block' title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
    $return_data .=  "<td>".$user_upload->uploaded_Date."</td>";
    $return_data .=  "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-id='$user_upload->id'
    data-psur_reference='$user_upload->psur_refrence_number'
    title='Delete PSUR' data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
    
}
 
    
    
    
    return response()->json(['Data_returned'=>$return_data ]);





   }



   public function upload_alert_nmfa_file(Request $request)
   {
//dd($request->all()) ;
try
{
$upload_id = User::where('id', '=', auth()->user()->id);

$psur = psur::where('application_id', '=', $request->application_id)
->where('nmfa_director_flag', '=', 0)
->orderBy('id','DESC')
->first();

$applications  = applications::select('applications.*')->where('applications.application_id','=',$request->application_id)->first();
$applications_count  = check_re_registered_application::select('check_re_registered_applications.*')->where('check_re_registered_applications.old_id','=',$request->application_id)->count();
$applications_dossier_assined_year = $applications->dossier_actual_path;


$year_explode =  explode('_',$applications_dossier_assined_year);
if($applications->application_type == 1) { $application_type='SR';} else {$application_type ='FR';}
$zero_filled_counter = sprintf('%04d', $applications_count);
$psur_refrence_number = $application_type."/R".$applications_count."/".$year_explode[2]."/".$zero_filled_counter."/PSUR".$psur->id;





$name = $request->file('file')->getClientOriginalName();
$time=time();
$path = public_path('Storage/Upload_alert_file/');
$fileName =  $name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
$filePath = $request->file('file')->storeAs('Upload_alert_file/', $fileName, 'public');
$generated_pdf_link = Storage::url('public/Upload_alert_file/'.$fileName);

$documents = new documents;
$documents->name =  $fileName;
$documents->path =  $generated_pdf_link;
$documents->document_type = '32';
$documents->ref_num =  $fileName;
$documents->description = 'Upload psur alert file';
$documents->save();







$psur = new psur_alert;
// $psur->psur_refrence_number	 =  $psur_refrence_number;
$psur->psur_document_id =  $documents->id;
$psur->nmfa_director_flag = 1;
$psur->application_id = $request->application_id;
// $psur->ref_num =  $fileName;
$psur->save();




// //$rendered_html_data = $this->rendered_html_data($request->application_id,$request->application_number);


// $pdf = PDF::loadHTML($rendered_html_data);
// $pdf->setPaper ('A4', 'portrait');
// // <--- load your view into theDOM wrapper;
// $time=time();
// $path = public_path('storage/Acknowledgement_Letter/Acknowledgement_Letter_fo_ the_receipt_Periodic_Safety_Update_Report/');
// // <--- folder to store the pdf documents into the server;
// $fileName =  $psur_refrence_number.$time."-".'.pdf' ; // <--giving the random filename,
// $pdf->save($path.$fileName);
// $generated_pdf_link = Storage::url('public/Acknowledgement_Letter/Acknowledgement_Letter_fo_ the_receipt_Periodic_Safety_Update_Report /'.$fileName);








$doc_upload_psur_document  = psur_alert::join('documents',
'documents.id','psur_alerts.psur_document_id')
->select('documents.*','psur_alerts.*','documents.name as dname',
'documents.created_at as uploaded_Date', 'documents.id as did')
->where('psur_alerts.application_id','=',$request->application_id)
->where('documents.document_type','=',32)
->get();

$application=applications::where('application_id',$request->application_id)->first();

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
$new_notification['data']='NMFA Director send psur alert file';
$new_notification['subject']='NMFA Director send psur alert file';
$new_notification['from_user'] = 'System Notification';
$new_notification['alert_level']='high';
$new_notification['related_document']=  '';
$new_notification['related_id'] = $application->id;
$new_notification['remark']='remark';
// ::send($users, new ($invoice));
Notification::send($user, new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'NMFA Director Submitted alert PSUR file' .'NMFA Director'. $user->first_name));
}
}
}
}


$i=1;   $return_data='';
foreach($doc_upload_psur_document as $user_upload)
    
{
$return_data .= "<tr><td>".$i++."</td>";
// $return_data .= "<td>".$user_upload->psur_refrence_number."</td>";
$return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'
title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";

$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
data-id='$user_upload->did' 
title='Delete PSUR'
data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
}
return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);

  }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}


 }



    public function fetch_alert_uploaded_files(Request $request)
    {
try
{
$upload_id = User::where('id', '=', auth()->user()->id);

//dd($request->application_id);
$doc_upload_psur_document  = psur_alert::join('documents','documents.id','psur_alerts.psur_document_id')
->select('documents.*','psur_alerts.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
->where('psur_alerts.application_id','=',$request->application_id)->where('documents.document_type','=',32)
->get();



$i=1;   $return_data='';
foreach($doc_upload_psur_document as $user_upload)
{
$return_data .= "<tr><td>".$i++."</td>";
// $return_data .= "<td>".$user_upload->psur_refrence_number."</td>";
$return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank'

title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";

$return_data .= "<td>".$user_upload->uploaded_Date."</td>";

$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query'
 data-id='$user_upload->did' 
 title='Delete PSUR'
 data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i> Remove </a></td>";    
}
return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);

     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}



    }






    public function rendered_html_data($application_id,$application_number)
{
  $path_header = "images/nmfa_header.png";
  $path_footer = "images/nmfa_footer.png";

    //$applications = applications::create($request->all());
$Acknowledgement_letter  = Acknowledgement_letter::where('application_id',$application_id)->get();

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

  <div class='panel-heading'  >Date: <span id='current_date'> ".$date."</span> </div>
  <br/>
  <div class='panel-body'  >Ref: <span id='RL_squential_number'>  ".$random_application_RL_squential_number." </span>  </div>
  <br/>
  <div class='panel-body'>
 To: <span id='applicant_name'> ".$fullname_contact." </span>  <br/>
 <ul>
    <li> <span id='state_plot_number'> ".$address."  </span> </li> <br/>
    <li> <span id='country'>  ".$country_name." </span> </li>  </br>
  <!-- <li> [country]  </li>    </br> -->
  </ul>
</div>
</div>
  </p>

<style>
p,block {
    text-align: justify;
}


</style>

<b> Subject: Acknowledgement Letter for the Completion of Preliminary Assessment </b>
<br/><br/>
<block style='text-align: justify;'>
<p>Dear Sir/Madam or  <span id='contact_person_name'>  ".$fullname_contact." </span> ,</p>
<br/>
This is to kindly inform you that the preliminary assessment of your application <span id='application_number'>  ".$application_number."   </span>  showed completeness and has therefore passed for the evaluation process. Please be informed that the evaluation decision of the NMFA will be communicated within <span style='border:0.5px'> ".$number_days_receipts." days of receipt of this notification.
</block>
<br/><br/>
<p> Best regards,  </p>

Iyassu Bahta
Director, National Medicines and Food Administration
Ministry of Health
Asmara, Eritrea

                </div>
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


















    /**
     * Display the specified resource.
     *
     * @param  \App\Models\psur  $psur
     * @return \Illuminate\Http\Response
     */
    public function show(psur $psur)
    {
        //
    }




    public function store(Request $request)
    { 
        
       // dd($request->all());


        $psurs_alert = psur::where('psur_refrence_number',$request->psur_refrence_number)->first();


        if($psurs_alert->assigned_To != null)
       {
         $fresh=true;
       }
       else
       {
        $fresh=false;
       }

        $assign= psur::updateOrCreate(
        ['psur_refrence_number' => $request->psur_refrence_number], 
        ['assigned_To' => $request->assigned_To, 
        'assigned_By' => $request->assigned_By,
        'Assginment_Date' => now(),
        'deadline' => $request->deadline
    
    ],
    );  
    
    $application=applications::where('application_id',$request->application_id)->first();
            
    $duration_days = 10;
            
    //MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);
    
    $main_task = $this->get_main_task_id($application->id,'Application');
    $end_time =  date('Y-m-d H:i:s', strtotime('+ '.$duration_days.' days'));
    $issued_datetime = date('Y-m-d H:i:s');
    $task_category = 'Post Marketing';
    $task_activity_title = 'Post Marketing';
    $content_details = 'Application assigned to PERC for PSUR alerts';
    $route_link = '';
    $activity_status = 'PSUR alert inprogress';
    $uploaded_document_id = null;



    if(!$fresh)
    {
    MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,$task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
    }


    if($fresh)
    {
    $tasks = TaskTracker::where('task_id', $main_task->id)
    ->where('task_category','Post Marketing')
    ->first();


        }

    $user=User::where('id', $assign->assigned_To)->first();
   
   
    $new_notification=[];
    
    
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='Application PSUR Assigned By Supervisor';
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = 'Appication with application No: '.$application->application_number.' has been assigned  by supervisor';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
    // ::send($users, new ($invoice));
    if(!$fresh)
    {
    Notification::send($user, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->id, 'appication with application No: '.$application->application_number.' has been assigned by supervisor for post marketing' ));
    }
    else
    {

        $new_notification=[];
        $new_notification['type'] = 'Notification';
        $new_notification['subject'] ='Application PSUR Assigned By Supervisor';
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = 'Application with application No: '.$application->application_number.' has been  re-assigned  by supervisor.';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
        Notification::send($user, new ApplicationReceiptionNotification($new_notification));
        event(new ApplicationReceiptionEvent($user->id, 'Application with application No: '.$application->application_number.' has been re-assigned by supervisor  for Post Marketing.'));
        
    }

       return response()->json($assign);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\psur  $psur
     * @return \Illuminate\Http\Response
     */
    public function edit(psur $psur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\psur  $psur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, psur $psur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\psur  $psur
     * @return \Illuminate\Http\Response
     */
    public function destroy(psur $psur)
    {
        //
    }
}
