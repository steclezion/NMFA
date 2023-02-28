<?php

namespace App\Http\Controllers;
use App\Http\Controllers\MainTaskController;
use Illuminate\Support\Carbon;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dossier;
use App\Models\suspension;
use App\Models\applications;
use App\Models\uploaded_documents;
use App\Models\Withdrawal;
use App\Models\MainTask;
use App\Models\TaskTracker;
use App\Notifications\InformationNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Events\DossierAssignmentEvent;
use App\Models\Meeting;


class WithdrawalController extends Controller
{
    //
	    public function withdrawn_index()
    {
		$applications = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
        ->leftjoin('certifications', 'certifications.decision_id', 'decisions.id')
        ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->leftjoin('medicines','medicinal_products.medicine_id','medicines.id')
        ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
		->join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->where('decision_status', 'Accepted')
        ->where('decisions.sealed_document_id', '!=', null)
        ->where('withdrawals.action_taken','=','Withdrawn')            
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status','withdrawals.action_taken',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'withdrawals.suspension_status','withdrawals.id as suspension_id', 'company_suppliers.trade_name','withdrawals.withdrawal_decision')
        ->get();
		//dd($accepted_decisions);
		
/*        $applications2=applications::join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
		->join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->where('withdrawals.action_taken','=','Withdrawn')            
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status','withdrawals.action_taken',
                'medicinal_products.product_trade_name', 'medicines.product_name',
                'withdrawals.suspension_status','withdrawals.id as suspension_id', 'company_suppliers.trade_name')
        ->get();
		
		dd($applications2);

        $applications=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->where('withdrawals.action_taken','=','Withdrawn')            
        ->where('decisions.decision_status','=','Accepted')            
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status','withdrawals.action_taken',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'withdrawals.suspension_status','withdrawals.id as suspension_id', 'company_suppliers.trade_name')
        ->get();
*/
        return view('withdrawals.withdrawals_index',  ['withdraw_header'=>'Withdrawan Products', 'applications'=>$applications]);
        }

	    public function withdrawn_requests()
    {
        $applications=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->where('withdrawals.action_taken','!=','Withdrawn')            
        ->where('withdrawals.withdrawal_decision','=',NULL)            
        ->Where('withdrawals.action_taken','=','Withdrawal Requested')            
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status','withdrawals.action_taken',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'withdrawals.suspension_status','withdrawals.id as suspension_id', 'company_suppliers.trade_name','withdrawals.withdrawal_decision')
        ->get();
        return view('withdrawals.withdrawals_index', ['withdraw_header'=>'Withdrawal Requests', 'applications'=>$applications]);
        }

      public function show_withdrawal($withdrawal_id)
      {
          $application = Withdrawal::join('applications', 'applications.id', 'withdrawals.application_id')
          ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
          ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
          ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
          ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
          ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
          ->join('certifications','certifications.decision_id','decisions.id')
          ->select('applications.id', 'applications.application_number','market_status',
                    'withdrawals.description','medicinal_products.product_trade_name', 
                    'withdrawals.id as withdrawal_id','withdrawals.action_date', 'withdrawals.action_taken', 'withdrawals.withdrawal_decision_date',
                    'withdrawals.action_date','medicines.product_name','applications.application_id',
                     'company_suppliers.trade_name', 'certifications.registration_number',
					 'withdrawals.withdrawal_date_requested','withdrawals.withdrawal_request_reason','withdrawals.withdrawal_request_attachment',
					 'withdrawals.withdrawal_decision','withdrawals.withdrawal_decision_reason','withdrawals.withdrawal_decision_document')
          ->where('withdrawals.id','=',$withdrawal_id)
          ->first();
		  
		  
          $main_task = MainTask::where('related_id', $withdrawal_id)
            ->where('task_name', "Withdrawal")
            ->first();
			
			if($main_task!=null){
          $tasks = TaskTracker::where('task_id', $main_task->id)
            ->OrderBy('task_trackers.id', 'desc')
            ->get();				
			}else{
				$tasks = null;
			}
			
		$tasks_1 = MainTask::join('withdrawals', 'withdrawals.id', 'main_tasks.related_id')
          ->join('task_trackers', 'task_trackers.task_id', 'main_tasks.id')
		  ->where('main_tasks.task_name', 'withdrawal')
		  ->where('withdrawals.application_id', $application->id)
          ->OrderBy('task_trackers.id', 'desc')
		  ->select('task_trackers.*');
		  
	   $tasks = MainTask::join('suspensions', 'suspensions.id', 'main_tasks.related_id')
          ->join('task_trackers', 'task_trackers.task_id', 'main_tasks.id')
		  ->where('suspensions.application_id', $application->id)
		  ->where('main_tasks.task_name', 'Suspended')
		  ->Orwhere('main_tasks.task_name', 'Ceased')
		  ->union($tasks_1)
          ->OrderBy('id', 'desc')
		  ->select('task_trackers.*')
		  ->get();

			
          return view('withdrawals.show_withdrawal', compact('application','tasks','main_task'));
        }
		

		      public function store_withdrawal(Request $request)
      {
      $suspension_till_date = NULL;
        
        $attached_file = $request->file('withdrawal_request_attachment');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);


        $withdrawal = new Withdrawal;
        $withdrawal->application_id = $request->input('application_id');
        $withdrawal->action_taken = "Withdrawal Requested";
        $withdrawal->withdrawal_date_requested = $request->input('withdrawal_date_requested');
        $withdrawal->withdrawal_request_reason = $request->input('withdrawal_request_reason');
        //$withdrawal->action_date =$request->input('action_date');
        $withdrawal->withdrawal_request_attachment = $path;
        //$withdrawal->suspended_till_date = $withdrawal_till_date;
        // insert records
        $withdrawal->save();
		$withdrawal_id = $withdrawal->id;

		
		$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $withdrawal_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Withdrawal document';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = 'Withdrawal document';
        // insert records
        $uploaded_document->save();
		
		$application_id = $request->input('application_id');

		
       $main_task = MainTaskController::insertTask("Withdrawal", "Withdrawal", $withdrawal_id, 0, $request->input('withdrawal_date_requested'), $request->input('withdrawal_date_requested'), $request->input('withdrawal_date_requested') 
	   ,"Active", $alert_before_days = 1);

       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number', 'company_suppliers.trade_name','dossier_assignments.supervisor_id')
        ->first();
		
                $supervisor = User::find($application->supervisor_id);
		
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = "Withdrawal of M.A. of ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." requested.";
                $new_notification['subject'] = "Withdrawal of M.A. of ".$application->product_trade_name." requested.";
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $application->id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

                Notification::send($supervisor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($application->supervisor_id,  "Withdrawal of M.A. of ".$application->product_trade_name." requested."));


				$main_task = MainTask::where('related_id', $withdrawal_id)
            ->where('task_name', "Withdrawal")
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $request->input('withdrawal_date_requested'), $request->input('withdrawal_date_requested'),
	"Activity","Withdrawal of market authorization of the product ".$application->product_trade_name." requested.",
	"Withdrawal of  the product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." requested on ".$withdrawal->withdrawal_date_requested.".", "", "Active", 0);

   	  return Redirect('/withdrawals/show/'.$withdrawal_id)->with('success', 'Product withdrawal request sent successfully.');
  }

  
         public function withdrawal_decision(Request $request)
      {
		$application_id = $request->input('application_id');
   	  $withdrawal_id = $request->input('withdrawal_id');
	  
	          $attached_file = $request->file('withdrawal_decision_document');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);

   	$withdrawal = new Withdrawal;
	$withdrawal = withdrawal::where('id',$withdrawal_id)->first();	  
	$withdrawal->withdrawal_decision_date = $request->input('withdrawal_decision_date');
	$withdrawal->withdrawal_decision = $request->input('withdrawal_decision');
	$withdrawal->withdrawal_decision_document = $path;
	$withdrawal->withdrawal_decision_reason = $request->input('withdrawal_decision_reason');
	if($withdrawal->withdrawal_decision=="Accepted"){
		$withdrawal->action_taken = "Withdrawn";
        applications::where('id',$request->input('application_id'))->update(
            [
                'market_status' => 'Withdrawn'
            ]
        );	
	}
	$withdrawal->save();

	
	       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();
		
        $main_task = MainTask::where('related_id', $withdrawal_id)
            ->where('task_name', "Withdrawal")
            ->first();
			
		$main_task_update = MainTask::where('related_id', $withdrawal_id)
            ->where('task_name', "Withdrawal")
			->update([
                        'task_status' => 'Terminated'
                    ]);

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = "Withdrawal of M.A. of ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." ".$withdrawal->withdrawal_decision;
                $new_notification['subject'] = "Withdrawal of M.A. of ".$application->product_trade_name." ".$withdrawal->withdrawal_decision;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = '';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $application->id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($application->user_id,  "Withdrawal of M.A. of ".$application->product_trade_name." ".$withdrawal->withdrawal_decision));

			
	MainTaskController::insertActivity($main_task->id, $request->input('withdrawal_decision_date'), $request->input('withdrawal_decision_date'), "Activity","Withdrawal of market authorization of the product ".$application->product_trade_name." ".$request->input('withdrawal_decision'),
	"Withdrawal of  the product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." ".$request->input('withdrawal_decision')." on ".$withdrawal->withdrawal_decision_date." because ".$withdrawal->withdrawal_decision_reason.".", "", "Active", 0);

   	  return Redirect('/withdrawals/show/'.$withdrawal_id)->with('success', 'Withdrawal decision saved successfully.');
  }
  

         public function update_withdrawal(Request $request)
      {
		}
		
		
}
