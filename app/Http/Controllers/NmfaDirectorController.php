<?php

namespace App\Http\Controllers;
use App\Models\nmfa_director;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\psur;
use App\Models\psur_alert;
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

class NmfaDirectorController extends Controller
{
    public  $i=1;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function alert_from_nmfa_director()
{

    $applications = applications::leftjoin('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
   ->leftjoin('company_suppliers','company_suppliers.application_id','applications.application_id')
   ->leftjoin('users','users.id','applications.user_id')
   ->join('contacts','contacts.application_id','applications.application_id')
   ->join('nmfa_directors','nmfa_directors.application_id','applications.application_id')
   ->leftjoin('dossier_assignments','applications.id','dossier_assignments.application_id')
               ->leftjoin('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
               ->leftjoin('certifications','decisions.id','certifications.decision_id')
   ->select(
   'nmfa_directors.*',
   'medicines.product_name as med_name','certifications.registration_number as regnumber',
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
   ->orderBy('applications.application_id','ASC')
   ->get();



   $disabled = '';$return_data =""; $i=1;
   foreach($applications as $application)


 {

     $doc_upload_nmfa_document  = nmfa_director::join('documents','documents.id','nmfa_directors.nmfa_directors_document_id')
   ->select('documents.*','nmfa_directors.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did')
   ->where('nmfa_directors.nmfa_directors_document_id','=',$application->nmfa_directors_document_id)
   ->where('documents.document_type','=',32)
   ->first();


   
   $select_common_application_numbers =   DB::table('nmfa_directors')->select(DB::raw('count(*) as alert_count, application_id'))->where('nmfa_directors.application_id', '=', $application->application_id)->groupBy('application_id')->first();
   
   if( $select_common_application_numbers->alert_count  > 1 ) {  $tr_color='skyblue'; $color='black';} else {$tr_color='';$color='';}

   if($application->regnumber==''){$application->regnumber='N/A';}else {$application->regnumber=$application->regnumber;}

   $return_data .= 
   "<tr style='background-color:$tr_color; color:$color' > 

   <td>".$i++."</td>";
   $return_data .= "<td>".$application->regnumber."</td>";
   $return_data .= "<td>".$application->application_number."</td>";
   $return_data .= "<td>".$application->med_name."</td>";
   $return_data .= "<td>".$application->product_trade_name."</td>";
  
   $return_data .= "<td>".$application->trade_name ."</td>";
   $return_data .= "<td hidden>".$application->first_name.' '.$application->middle_name.' '.$application->last_name ."</td>";
   $return_data .= "<td>". $doc_upload_nmfa_document->uploaded_Date ."</td>";
   
   $return_data .= "<td>  <a target='_blank' href='$doc_upload_nmfa_document->path' data-toggle='tooltip' id='query'  title='Alert File From Nmfa Director' data-original-title='Edit' class='edit btn btn-danger btn-sm' > <i class='fas fa-download'></i>  </a></td>";

 
   $return_data .="</tr>";
}


$dataa = User::where('id',auth()->user()->id)->orderBy('id','ASC')->get();


return view('supervisor_check_progress_of_assessor.alert_from_nmfa_director',[
   'return_data' => $return_data,
]);


}




public function fetch_alert_uploaded_files_nmfa(Request $request)
{
try
{


$upload_id = User::where('id', '=', auth()->user()->id);


//dd($request->application_id);
$doc_upload_nmfa_alert_document  = nmfa_director::join('documents','documents.id','nmfa_directors.nmfa_directors_document_id')
->select('documents.*','nmfa_directors.*',
'documents.name as dname',
'documents.created_at as uploaded_Date',
'documents.id as did','nmfa_directors.id as nmfa_id' )
->where('nmfa_directors.application_id','=',$request->application_id)
->where('documents.document_type','=',32)
->get();




$i=1;   $return_data='';
foreach($doc_upload_nmfa_alert_document  as $user_upload)
{
$return_data .= "<tr><td>".$i++."</td>";
$return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank' title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
$return_data .= "<td>".$user_upload->Send_To."</td>";
$return_data .= "<td>".$user_upload->uploaded_Date."</td>";
$return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' data-id='$user_upload->did'
data-nmfa_id= '$user_upload->nmfa_id'
title='Delete alert file' data-original-title='Edit'  
class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i></a>
</td>";    

}
return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);

     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}



    }




    public function delete_file_data_uploaded_nmfa_director(Request $request)
    {


$documents  = nmfa_director::join('documents','documents.id','nmfa_directors.nmfa_directors_document_id')
->select('documents.*','nmfa_directors.*','documents.name as dname','documents.created_at as uploaded_Date', 
 'documents.id as did', 'nmfa_directors.id as nmfa_id' )
->where('nmfa_directors.application_id','=',$request->application_id)
->where('documents.document_type','=',32)
->first();

        $path = public_path('storage/app/public/Upload_Nmfa_Director_Alert_File/'.$documents->dname);
        Storage::delete('public/Upload_Nmfa_Director_Alert_File/'.$documents->dname);



        $doc =DB::table('documents')->where('id', '=', $request->document_id)->delete();
        
        $psur =DB::table('nmfa_directors')->where('id', '=', $request->nmfa_id)->delete();



        $return_data='';
         

  //dd($request->application_id);
$doc_upload_nmfa_alert_document  = nmfa_director::join('documents','documents.id','nmfa_directors.nmfa_directors_document_id')
->select('documents.*','nmfa_directors.*','documents.name as dname','documents.created_at as uploaded_Date', 'documents.id as did','nmfa_directors.id as nmfa_id')
->where('nmfa_directors.application_id','=',$request->application_id)->where('documents.document_type','=',32)
->get();
        
 
 
  $i=1;   $return_data='';
foreach( $doc_upload_nmfa_alert_document as $user_upload)
{

    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank' title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
    $return_data .= "<td>".$user_upload->Send_To."</td>";
    $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
    $return_data .= "<td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' 
    data-id='$user_upload->did'
    data-nmfa_id= '$user_upload->nmfa_id'

  title='Delete alert file' data-original-title='Edit'  class='edit btn btn-danger btn-sm deletequery'> <i class='fas fa-trash'></i></a></td>";    
    
}
 
    
    
    
    return response()->json(['Data_returned'=>$return_data ]);





   }



    public function upload_alert_nmfa_director_file(Request $request)
    {
 //dd($request->all()) ;
 try
 {

 $upload_id = User::where('id', '=', auth()->user()->id);
 
 $nmfa_director = nmfa_director::where('application_id', '=', $request->application_id)->first();
 
 $applications  = applications::select('applications.*')->where('applications.application_id','=',$request->application_id)->first();

 $uploaded_filename_by =  User::select('users.*',)->where('users.id','=',auth()->user()->id)->first();
 
 $Applicant =$applications->user_id;
 $supervisor =$applications->assigned_By;

 $new_notification=[];
 $new_notification['type']='Notification';
 $new_notification['data']='NMFA Director has sent Alert file';
 $new_notification['subject']='NMFA Director submmited Alert file';
 $new_notification['from_user'] = 'Eritrean NMFA Director';
 $new_notification['alert_level']='high';
 $new_notification['related_document']=  '';
 $new_notification['related_id'] = $applications->id;
 $new_notification['remark']='remark';

 $user = applications::join('users','users.id','applications.user_id')->select('applications.*','users.id as uid')->where('applications.application_id','=',$request->application_id)->first();

 $dataa = User::orderBy('id','ASC')->get();

 if($request->Applicant == true && $request->Supervisor == true) {

    Notification::send($dataa, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->uid, 'NMFA Director submmited Alert file'));

    Notification::send($dataa, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->assigned_By, 'NMFA Director submmited Alert file'));

    $Get_name_of_applicant  =  User::where('id','=',$user->uid)->first();
    $Get_name_of_supervisor  =  User::where('id','=',$user->assigned_By)->first();
    $send_to = $Get_name_of_applicant->first_name." ".$Get_name_of_applicant->last_name." and ".$Get_name_of_supervisor->first_name." ".$Get_name_of_supervisor->last_name;
    $send_to_id = 'Both';
 }
  else if($request->Applicant == true) {    

      Notification::send($dataa, new ApplicationReceiptionNotification($new_notification));
      event(new ApplicationReceiptionEvent($user->uid, 'NMFA Director submmited Alert file'));

      $Get_name_of_applicant  =  User::where('id','=',$user->uid)->first();
      $send_to = $Get_name_of_applicant->first_name." ".$Get_name_of_applicant->last_name;
     
      $send_to_id = $user->uid;
      
    }

  else if($request->Supervisor == true) {
    Notification::send($dataa, new ApplicationReceiptionNotification($new_notification));
    event(new ApplicationReceiptionEvent($user->assigned_By, 'NMFA Director submmited Alert file'));

    $Get_name_of_supervisor  =  User::where('id','=',$user->assigned_By)->first();
    $send_to = $Get_name_of_supervisor->first_name." ".$Get_name_of_supervisor->last_name;
    $send_to_id = $user->assigned_By;
     
  }





 
 
 
 $name = $request->file('file')->getClientOriginalName();
 $time=time();
 $path = public_path('Storage/Upload_Nmfa_Director_Alert_File/');
 //$fileName =  $name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
 $fileName =  "NMFA-ALERT-FILE-".$uploaded_filename_by->first_name."-".$uploaded_filename_by->middle_name."-".$uploaded_filename_by->last_name."-".$time.".".$request->file('file')->extension();  // <--giving the random filename,
 $filePath = $request->file('file')->storeAs('Upload_Nmfa_Director_Alert_File/', $fileName, 'public');
 $generated_pdf_link = Storage::url('public/Upload_Nmfa_Director_Alert_File/'.$fileName);
 
 $documents = new documents;
 $documents->name =  $fileName;
 $documents->path =  $generated_pdf_link;
 $documents->document_type = '32';
 $documents->ref_num =  $fileName;
 $documents->description = 'Upload NMFA alert file';
 $documents->updated_at = now();
 $documents->save();
 

 
 $nmfa_director = new nmfa_director;
 $nmfa_director->nmfa_directors_document_id =  $documents->id;
 $nmfa_director->nmfa_director_flag = 0;
 $nmfa_director->Send_To = $send_to;
 $nmfa_director->Send_To_id = $send_to_id;
 $nmfa_director->application_id = $request->application_id;
 $nmfa_director->updated_at = now();
 $nmfa_director->save();
  
 
 $doc_upload_nmfa_document  = nmfa_director::join('documents','documents.id','nmfa_directors.nmfa_directors_document_id')
 ->select('documents.*','nmfa_directors.*','documents.name as dname', 'documents.created_at as uploaded_Date', 'documents.id as did','nmfa_directors.id as nmfa_id')
 ->where('nmfa_directors.application_id','=',$request->application_id)
 ->where('documents.document_type','=',32)
 ->get();
 

 
 
 $i=1;   $return_data='';
 foreach($doc_upload_nmfa_document as $user_upload)
     
 {
 $return_data .= "<tr><td>".$i++."</td>";
 $return_data .="<td id='seqence_number_$user_upload->id' > <a  href='".$user_upload->path."' style='display:block'   target='_blank' title='Uploaded PSUR'   id='Download_File' >   ".$user_upload->dname."</a></td>";
 $return_data .= "<td>".$user_upload->Send_To."</td>";
 $return_data .= "<td>".$user_upload->uploaded_Date."</td>";
 $return_data .= "<td>
    <a href='javascript:void(0)' data-toggle='tooltip' id='query'
    data-id='$user_upload->did'  
    data-nmfa_id= '$user_upload->nmfa_id'
    title='Delete alert file' data-original-title='Edit'  
    class='edit btn btn-danger btn-sm deletequery'>
    <i class='fas fa-trash'></i> </a></td>";    
}
 return response()->json(['Message'=>true,'Data_returned'=>$return_data ]);
 
   }
 
 catch(Exception $e)
 {
 return response()->json(['Message'=>false,'item'=>'error'.$e]);
 }
 
 
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
     * @param  \App\Models\nmfa_director  $nmfa_director
     * @return \Illuminate\Http\Response
     */
    public function show(nmfa_director $nmfa_director)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\nmfa_director  $nmfa_director
     * @return \Illuminate\Http\Response
     */
    public function edit(nmfa_director $nmfa_director)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\nmfa_director  $nmfa_director
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, nmfa_director $nmfa_director)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\nmfa_director  $nmfa_director
     * @return \Illuminate\Http\Response
     */
    public function destroy(nmfa_director $nmfa_director)
    {
        //
    }
}
