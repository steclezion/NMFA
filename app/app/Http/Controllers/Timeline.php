<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\forgot_password_questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Models\applications;
use App\Models\User;
use App\Models\company_suppliers;
use App\Models\manufacturers;
use App\Models\medicinal_products;
use App\Models\agents_template; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\company_suppliers_template;
use App\Models\issue_query;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Models\TaskTracker;


class Timeline extends Controller
{
    //

    public function Timeline(Request $request)
    {

        $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
        @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
        $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
        ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        //->join('users','users.id','applications.user_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->distinct()
        ->select('applications.id as appid','applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('applications.user_id',auth()->user()->id)
        ->orderBy('applications.application_id','ASC')
        ->get();

return view('Timeline.Applicant.Timeline',['applications'=>$applications]);

    }

    private function get_main_task_id($application_id, $related_type = 'Application')
    {
        $main_task = MainTask::where('related_id', $application_id)
            ->where('related_task', $related_type)
            ->first();
            //dd($main_task);
        if ($main_task) {
            return $main_task;
        } else {
    
            return 0; //means false
        }}


    public function Timeline_Applicant(Request $request,$id)
    {
        $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
        @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
        $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
        ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
        ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
        ->join('contacts','contacts.application_id','applications.application_id')
        ->distinct()
        ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
        'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
        'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
        'contacts.last_name as clast_name')
        ->where('contacts.contact_type','=','Supplier')
        ->where('applications.user_id',auth()->user()->id)
        ->orderBy('applications.application_id','ASC')
        ->get();

        $main_task = $this->get_main_task_id($id,'Application');
        $tasks = TaskTracker::where('task_id', $main_task->id)
        ->where('task_category','Applying')
        ->get();
       // ->OrderBy('id','desc');
      //   $tasks  = DB::table('task_trackers')
     //   ->where('task_category', 'Applying')
    //   ->first();
   //dd( $tasks );
return view('Timeline.Applicant.Timeline_applicant',['tasks'=>$tasks]);
 }






 public function SuperVisor_Timeline(Request $request)
 {
    $i=1;$return_data='';
    $applications  =   applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('users','users.id','applications.user_id')
   ->join('contacts','contacts.application_id','applications.application_id')
    ->select('applications.*','applications.id as appid',
    DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname'),
    'medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
    'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
    'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.assigned_To','<>',NULL)
    ->orderBy('application_number','ASC')
    ->get();
 
    foreach($applications as $row)

{
     
    $assinged_To_full_Name = User::select('users.*')
    ->where('id','=',$row->assigned_To)
    ->get();

    $assinged_To = $assinged_To_full_Name[0]->first_name." ".$assinged_To_full_Name[0]->middle_name." ".$assinged_To_full_Name[0]->last_name;
    
    $assinged_By_full_Name = User::select('users.*')
    ->where('id','=',$row->assigned_By)
    ->get();


    if($row->payment_status == 0)
   { $status = "<span  title='shows:Application needs to be completed' class='badge badge-warning bg-blue-100' <i   class='fas fa-ruler'>Pending</i>  </span>";
   }else
   { $status = "<span  title='shows:Application is completed ' class='badge badge-success bg-blue-100' <i   class='fas fa-ruler'>Completed</i>  </span>";
   }




    $assinged_By = $assinged_By_full_Name[0]->first_name." ".$assinged_By_full_Name[0]->middle_name." ".$assinged_By_full_Name[0]->last_name;
   
    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .= "<td>".$row->application_number."</td>";
//    $return_data .= "<td>".$row->application_status."</td>";
   $return_data .= "<td>".$status."</td>";
   $return_data .= "<td>".$row->t_name ."</td>";
   $return_data .= "<td>".$row->cfirst_name." ".$row->cmiddle_name." ".$row->clast_name."</td>";
   $return_data .= "<td>".$assinged_To."</td>";
   $return_data .= "<td>".$assinged_By."</td>";
   $return_data .= "<td>".$row->Assginment_Date."</td>";
   $return_data .=  "<td> <a  title='Edit'  class='btn btn-sm btn-warning'   href='Timeline_show_supervisor/$row->appid'>  <i class='fa fa-timeline'></i></a></td></tr>";
    
}
    
  


    return view('Timeline.Supervisor.Timeline_index_supervisor',['return_data'=>$return_data]);

 }




 public function Timeline_Supervisor(Request  $request,$id)
 {

    $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
    @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
    $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
    ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->distinct()
    ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.user_id',auth()->user()->id)
    ->orderBy('applications.application_id','ASC')
    ->get();

    $main_task = $this->get_main_task_id($id,'Application');
    $tasks = TaskTracker::where('task_id', $main_task->id)
    ->where('task_category','Screening')
    ->get();
   // ->OrderBy('id','desc');
  //   $tasks  = DB::table('task_trackers')
 //   ->where('task_category', 'Applying')
//   ->first();
//dd( $tasks );
return view('Timeline.Supervisor.Timeline_supervisor',['tasks'=>$tasks]);

    
 }




 public function Assessor_Timeline(Request $request)
 {

    $return_data='';   $i=1;

    $applications  =   applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('users','users.id','applications.user_id')
   ->join('contacts','contacts.application_id','applications.application_id')
    ->select('applications.*','applications.id as appid',
    DB::raw('concat(contacts.first_name," ",contacts.middle_name," ",contacts.last_name) as fullname'),
    'medicinal_products.*','medicinal_products.product_trade_name as t_name','company_suppliers.*',
    'company_suppliers.trade_name as cs_tradename','applications.*','contacts.*',
    'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.assigned_To','=',auth()->user()->id)
    ->orderBy('application_number','ASC')
    ->get();

 
    foreach($applications as $row)

{
     
    $assinged_To_full_Name = User::select('users.*')
    ->where('id','=',$row->assigned_To)
    ->get();

    $assinged_To = $assinged_To_full_Name[0]->first_name." ".$assinged_To_full_Name[0]->middle_name." ".$assinged_To_full_Name[0]->last_name;
    
    $assinged_By_full_Name = User::select('users.*')
    ->where('id','=',$row->assigned_By)
    ->get();


    if($row->payment_status == 0)
   { $status = "<span  title='shows:Application needs to be completed' class='badge badge-warning bg-blue-100' <i   class='fas fa-ruler'>Pending</i>  </span>";
   }else
   { $status = "<span  title='shows:Application is completed ' class='badge badge-success bg-blue-100' <i   class='fas fa-ruler'>Completed</i>  </span>";
   }




    $assinged_By = $assinged_By_full_Name[0]->first_name." ".$assinged_By_full_Name[0]->middle_name." ".$assinged_By_full_Name[0]->last_name;
    $return_data .= "<tr><td>".$i++."</td>";
    $return_data .= "<td>".$row->application_number."</td>";
//    $return_data .= "<td>".$row->application_status."</td>";
   $return_data .= "<td>".$status."</td>";
   $return_data .= "<td>".$row->t_name ."</td>";
   $return_data .= "<td>".$row->cfirst_name." ".$row->cmiddle_name." ".$row->clast_name."</td>";
   $return_data .= "<td>".$assinged_To."</td>";
   $return_data .= "<td>".$assinged_By."</td>";
   $return_data .= "<td>".$row->Assginment_Date."</td>";
   $return_data .=  "<td> <a  title='Edit'  class='btn btn-sm btn-warning'   href='Timeline_show_assessor/$row->appid'>  <i class='fa fa-timeline'></i></a></td></tr>";
    
}
    
  


    return view('Timeline.Assessor.Timeline_index_assessor',['return_data'=>$return_data]);

 }


 public function Timeline_Assessor(Request $request,$id)
 {


    $application_check_wizard  = applications::where('applications.user_id',auth()->user()->id)->get();
    @$explode = explode(',', $application_check_wizard[0]->hold_progress_wizard);
    $applications = applications::join('manufacturers','manufacturers.application_id','applications.application_id')
    ->join('medicinal_products','medicinal_products.application_id','applications.application_id')
    ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
    ->join('contacts','contacts.application_id','applications.application_id')
    ->distinct()
    ->select('applications.application_id','medicinal_products.*','medicinal_products.product_trade_name as t_name',
    'company_suppliers.*','company_suppliers.trade_name as cs_tradename','applications.*',
    'contacts.*','contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name',
    'contacts.last_name as clast_name')
    ->where('contacts.contact_type','=','Supplier')
    ->where('applications.user_id',auth()->user()->id)
    ->orderBy('applications.application_id','ASC')
    ->get();

    $main_task = $this->get_main_task_id($id,'Application');
    $tasks = TaskTracker::where('task_id', $main_task->id)
    ->where('task_category','Screening')
    ->get();
   // ->OrderBy('id','desc');
  //   $tasks  = DB::table('task_trackers')
 //   ->where('task_category', 'Applying')
//   ->first();
//dd( $tasks );
return view('Timeline.Assessor.Timeline_assessor',['tasks'=>$tasks]);

    


 }





}

