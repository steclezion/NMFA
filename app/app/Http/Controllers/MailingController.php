<?php

namespace App\Http\Controllers;

use App\Models\mailing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Carbon\Carbon;
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
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Models\TaskTracker;
use App\Models\documents;

use PDF;
use DataTables;

class MailingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mailing_send(Request $request)
    {
        //
     //   dd($request->all());

        try
        {
        
$Mailing = new mailing;
$Mailing->user_id=  auth()->user()->id;
$Mailing->to_ =  $request->to;
$Mailing->application_id =  $request->application_id;
$Mailing->subject= $request->subject;
$Mailing->message =  $request->message;
$Mailing->save();


$fetch_data = mailing::where('application_id', '=', $request->application_id)->orderBy('created_at','DESC')->get();

$Get_supervisor_name  = DB::table('applications')->leftjoin('users','users.id','applications.assigned_By')
->select('applications.*','users.*')
->where('application_id', '=', $request->application_id)
->first();

$fullname = $Get_supervisor_name->first_name." ".$Get_supervisor_name->middle_name." ".$Get_supervisor_name->last_name;


$i=1;   $btn = ''; $return_data='';

foreach($fetch_data as $user_upload)
{
    $Receiver_Name = User::where('id', '=', $user_upload->to_)->first();

    if($user_upload->to_ == auth()->user()->id){ 

        $return_data.="<tr style='background-color:#e8f080'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Mail Received From Supervisor' class='edit btn btn-warning btn-sm replied'> <i class='fas fa-mail-forward'></i> </a></td>";
    
    } else {
        $return_data.="<tr style='background-color:lightblue'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Forwaded Mail to Supervisor' class='edit btn btn-primary btn-sm replied'> <i class='fas fa-mail-reply'></i> </a></td>";
    
    }

$return_data .= "<td>".$i++."</td>";
$return_data .= "<td>".$Receiver_Name->first_name." ".$Receiver_Name->middle_name." ".$Receiver_Name->last_name."</td>";
$return_data .="<td id='number_$user_upload->id' >". $user_upload->subject.  "</td>";

$return_data .= "<td>".$user_upload->message."</td>";


$return_data .= "<td>".$user_upload->created_at."</td>";
    






}
$user=User::where('id', $Get_supervisor_name->assigned_By)->first();
$new_notification=[];
$new_notification['type'] = 'Notification';
$new_notification['subject'] ='Assessor has sent comment report on preliminary screening for application number: '.$Get_supervisor_name->application_number;
$new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
$new_notification['data']='Comment Report: '. $Get_supervisor_name->application_number;
$new_notification['related_document'] = '';
$new_notification['related_id'] = $request->application_id;
$new_notification['alert_level'] = null;
$new_notification['remark'] = null;

Notification::send($user , new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'Assessor has sent comment report on preliminary screening for application number: '.$Get_supervisor_name->application_number ));

return response()->json(['Message'=>true,'Data_returned'=>$return_data,'Supervisor_name'=>$fullname,'Supervisor_user_id'=>$Get_supervisor_name->assigned_By ]);
     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}

    }



    public function mailing(Request $request)
    {
//dd($request->all());

 try
{

    $fetch_data = mailing::where('application_id', '=', $request->application_id)->orderBy('created_at','DESC')->get();

$Get_supervisor_name  = DB::table('applications')->leftjoin('users','users.id','applications.assigned_By')
->select('applications.*','users.*')
->where('application_id', '=', $request->application_id)
->first();




$fullname = $Get_supervisor_name->first_name." ".$Get_supervisor_name->middle_name." ".$Get_supervisor_name->last_name;


$i=1;   $return_data='';

foreach($fetch_data as $user_upload)
{
    $Receiver_Name = User::where('id', '=', $user_upload->to_)->first();
    if($user_upload->to_ == auth()->user()->id){ 

        $return_data.="<tr style='background-color:#e8f080'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Mail Received From Supervisor' class='edit btn btn-warning btn-sm replied'> <i class='fas fa-mail-forward'></i> </a></td>";
    
    } else {
        $return_data.="<tr style='background-color:lightblue'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Forwaded Mail to Supervisor' class='edit btn btn-primary btn-sm replied'> <i class='fas fa-mail-reply'></i> </a></td>";
    
    }
    
$return_data .= "<td>".$i++."</td>";
$return_data .= "<td>".$Receiver_Name->first_name." ".$Receiver_Name->middle_name." ".$Receiver_Name->last_name."</td>";
$return_data .="<td id='number_$user_upload->id' >". $user_upload->subject.  "</td>";

$return_data .= "<td>".$user_upload->message."</td>";
$return_data .= "<td>".$user_upload->created_at."</td>";
}
return response()->json(['Message'=>true,'Data_returned'=>$return_data,'Supervisor_name'=>$fullname,'Supervisor_user_id'=>$Get_supervisor_name->assigned_By ]);

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
     * @param  \App\Models\mailing  $mailing
     * @return \Illuminate\Http\Response
     */
    public function show(mailing $mailing)
    {
        //
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mailing  $mailing
     * @return \Illuminate\Http\Response
     */
    public function edit(mailing $mailing)
    {
        //
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\mailing  $mailing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mailing $mailing)
    {
        //
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mailing  $mailing
     * @return \Illuminate\Http\Response
     */
    public function destroy(mailing $mailing)
    {
        //
    }












    public function mailing_send_su(Request $request)
    {
        //
     //   dd($request->all());

        try
        {
        
$Mailing = new mailing;
$Mailing->user_id=  auth()->user()->id;
$Mailing->to_ =  $request->to;
$Mailing->application_id =  $request->application_id;
$Mailing->subject= $request->subject;
$Mailing->message =  $request->message;
$Mailing->save();


$fetch_data = mailing::where('application_id', '=', $request->application_id)->orderBy('created_at','DESC')->get();

$Get_assessor_name  = DB::table('applications')->leftjoin('users','users.id','applications.assigned_To')
->select('applications.*','users.*')
->where('application_id', '=', $request->application_id)
->first();


$fullname = $Get_assessor_name->first_name." ".$Get_assessor_name->middle_name." ".$Get_assessor_name->last_name;



$i=1;   $btn = ''; $return_data='';

foreach($fetch_data as $user_upload)
{
    $Receiver_Name = User::where('id', '=', $user_upload->to_)->first();



    if($user_upload->to_ == auth()->user()->id){ 

        $return_data.="<tr style='background-color:#e8f080'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Mail Received From Assessor' class='edit btn btn-warning btn-sm replied'> <i class='fas fa-mail-forward'></i> </a></td>";
    
    } else {
        $return_data.="<tr style='background-color:lightblue'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Forwaded Mail to Assessor' class='edit btn btn-primary btn-sm replied'> <i class='fas fa-mail-reply'></i> </a></td>";
    
    }
    

$return_data .= "<td>".$i++."</td>";
$return_data .= "<td>".$Receiver_Name->first_name." ".$Receiver_Name->middle_name." ".$Receiver_Name->last_name."</td>";
$return_data .="<td id='number_$user_upload->id' >". $user_upload->subject.  "</td>";

$return_data .= "<td>".$user_upload->message."</td>";


$return_data .= "<td>".$user_upload->created_at."</td>";
    






}
$user=User::where('id',$Get_assessor_name->assigned_To)->first();
$new_notification=[];
$new_notification['type'] = 'Notification';
$new_notification['subject'] ='Supervisor has sent comment report on preliminary screening for application number: '.$Get_assessor_name->application_number;
$new_notification['from_user'] = auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name;
$new_notification['data']='Comment Report: '. $Get_assessor_name->application_number;
$new_notification['related_document'] = '';
$new_notification['related_id'] = $request->application_id;
$new_notification['alert_level'] = null;
$new_notification['remark'] = null;

Notification::send($user , new ApplicationReceiptionNotification($new_notification));
event(new ApplicationReceiptionEvent($user->id, 'Supervisor has sent comment report on preliminary screening for application number: '.$Get_assessor_name->application_number ));

return response()->json(['Message'=>true,'Data_returned'=>$return_data,'Supervisor_name'=>$fullname,'Supervisor_user_id'=>$Get_assessor_name->assigned_To ]);
     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}

    }



    public function mailing_su(Request $request)
    {
//dd($request->all());

 try
{

    $fetch_data = mailing::where('application_id', '=', $request->application_id)->orderBy('created_at','DESC')->get();

$Get_assessor_name  = DB::table('applications')->leftjoin('users','users.id','applications.assigned_To')
->select('applications.*','users.*')
->where('application_id', '=', $request->application_id)
->first();


$fullname = $Get_assessor_name->first_name." ".$Get_assessor_name->middle_name." ".$Get_assessor_name->last_name;




$i=1;   $return_data='';

foreach($fetch_data as $user_upload)
{
    $Receiver_Name = User::where('id', '=', $user_upload->to_)->first();
    if($user_upload->to_ == auth()->user()->id){ 

        $return_data.="<tr style='background-color:#e8f080'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Mail Received From Assessor' class='edit btn btn-warning btn-sm replied'> <i class='fas fa-mail-forward'></i> </a></td>";
    
    } else {
        $return_data.="<tr style='background-color:lightblue'><td> <a href='javascript:void(0)' data-toggle='tooltip' id='query' title='Forwaded Mail to Assessor' class='edit btn btn-primary btn-sm replied'> <i class='fas fa-mail-reply'></i> </a></td>";
    
    }
    
$return_data .= "<td>".$i++."</td>";
$return_data .= "<td>".$Receiver_Name->first_name." ".$Receiver_Name->middle_name." ".$Receiver_Name->last_name."</td>";
$return_data .="<td id='number_$user_upload->id' >". $user_upload->subject.  "</td>";
$return_data .= "<td>".$user_upload->message."</td>";
$return_data .= "<td>".$user_upload->created_at."</td>";


}
return response()->json(['Message'=>true,'Data_returned'=>$return_data,'Supervisor_name'=>$fullname,'Supervisor_user_id'=>$Get_assessor_name->assigned_To ]);

     }

catch(Exception $e)
{
return response()->json(['Message'=>false,'item'=>'error'.$e]);
}

    }

}
