<?php

namespace App\Http\Controllers;
use App\Http\Controllers\MainTaskController;
use Illuminate\Support\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dossier;
use App\Models\Suspension as suspension;
use App\Models\applications;
use App\Models\uploaded_documents;
use App\Models\withdrawal;
use App\Models\MainTask;
use App\Models\TaskTracker;
use App\Models\certification;
use App\Events\DossierAssignmentEvent;
use App\Notifications\InformationNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\DB;
use \Mpdf\Mpdf as PDFF;
use PDF;


class SuspensionController extends Controller
{
        public function test_files()
    {		
        return view('suspensions.index_test2');
    }

	
    public function index()
    {		
		$applications=certification::join('decisions','decisions.id','certifications.decision_id')
            ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('applications','applications.id','dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
           ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('applications.market_status','Active')
			->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
                'decisions.id as decision_id','decisions.locked','certifications.registration_number', 'company_suppliers.trade_name',
				'certifications.id as certification_id')
            ->get();

        return view('suspensions.index', compact('applications'));
    }

    public function suspended_index()
    {
		$applications=certification::join('decisions','decisions.id','certifications.decision_id')
            ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('applications','applications.id','dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
           ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->join('suspensions', 'applications.id', 'suspensions.application_id')
        ->where('applications.market_status','=','Suspended')
        ->where('suspensions.suspension_status','=','Active')
        ->where('suspensions.action_taken','=','Suspended')
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'suspensions.suspension_status','suspensions.id as suspension_id', 'company_suppliers.trade_name')
        ->get();
		$header = 'Suspended Products';
        return view('suspensions.suspensions_index', compact('applications','header'));

		$header = 'Suspended Products';
        return view('suspensions.suspensions_index', compact('applications','header'));
    }

    public function ceased_index()
    {
        $applications=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->join('suspensions', 'applications.id', 'suspensions.application_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->where('applications.market_status','=','Ceased')
        ->where('suspensions.suspension_status','=','Active')
        ->where('suspensions.action_taken','=','Ceased')
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'suspensions.suspension_status','suspensions.id as suspension_id', 'company_suppliers.trade_name')
        ->get();
		$header = 'Ceased Products';
        return view('suspensions.suspensions_index', compact('applications','header'));
    }

    public function index_history()
        {
//		dd($applications);

		$applicationsW=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->where('withdrawals.action_taken','=','Withdrawn')            
        ->select('applications.id', 'applications.application_id','applications.application_number','market_status',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                'withdrawals.suspension_status','withdrawals.id as suspension_id','withdrawals.action_taken', 'company_suppliers.trade_name');
				
            $applications=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
            ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('certifications','certifications.decision_id','decisions.id')
            ->join('suspensions', 'applications.id', 'suspensions.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->select('applications.id', 'applications.application_id','applications.application_number','market_status',
                    'medicinal_products.product_trade_name', 'medicines.product_name', 'certifications.registration_number', 
                    'suspensions.suspension_status','suspensions.id as suspension_id','suspensions.action_taken', 'company_suppliers.trade_name')
		->union($applicationsW)
        ->get();
            return view('suspensions.index_history', compact('applications'));
        }
    
    public function show_app($application_id)
    {
        $application = applications::join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
        ->select('applications.id', 'applications.application_number','market_status',
            'medicinal_products.product_trade_name', 'certifications.registration_number',
            'medicines.product_name','applications.application_id', 'company_suppliers.trade_name', 'company_suppliers.trade_name')
            ->where('applications.id','=',$application_id)
        ->first();
		
        $main_task = null;
		$tasks = null;
				$tasks = null;	
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

			
       return view('suspensions.show_app', compact('application','tasks','main_task'));
      }

      public function show($suspension_id)
      {
          $application = suspension::join('applications', 'applications.id', 'suspensions.application_id')
          ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
          ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
          ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
          ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
          ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
          ->join('certifications','certifications.decision_id','decisions.id')
          ->select('applications.id', 'applications.application_number','applications.market_status','appeal_document_user',
                    'suspensions.description as suspension_description','medicinal_products.product_trade_name', 
                    'suspensions.appeal_status', 'suspensions.id as suspension_id','suspensions.action_date','suspensions.appeal_description_moh','suspensions.appeal_description_user', 
                    'suspensions.action_date', 'suspensions.suspended_till_date','medicines.product_name','applications.application_id', 'suspensions.sealed_letter',
                     'company_suppliers.trade_name', 'certifications.registration_number','suspensions.decision_response_letter','suspensions.appeal_accepted','suspensions.appeal_document_user',
					 'suspensions.suspension_document','suspensions.appeal_document_moh','suspensions.appeal_description_moh', 'suspensions.decision_response', 
					 'suspensions.response_remark','suspensions.deadline_extension_requested','suspensions.request_deadline_extension_reason','suspensions.request_accepted','suspensions.request_accepted','dossier_assignments.id as dossier_asg_id')
              ->where('suspensions.id',$suspension_id)
          ->first();

		   $tasks = MainTask::join('suspensions', 'suspensions.id', 'main_tasks.related_id')
          ->join('task_trackers', 'task_trackers.task_id', 'main_tasks.id')
		  ->where('suspensions.application_id', $application->id)
          ->OrderBy('task_trackers.id', 'desc')
		  ->select('task_trackers.*')
		  ->get();
		  
           $alltasks = MainTask::join('suspensions', 'suspensions.id', 'main_tasks.related_id')
          ->join('task_trackers', 'task_trackers.task_id', 'main_tasks.id')
		  ->get();
		  
         $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
			if($main_task!=null){
          $tasks = TaskTracker::where('task_id', $main_task->id)
            ->OrderBy('task_trackers.id', 'desc')
            ->get();				
			}else{
				$tasks = null;
			}
		$tasks = null;	
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

          return view('suspensions.show', compact('application','tasks','main_task'));
        }
		
      public function show_void($suspension_id)
      {
          $application = suspension::join('applications', 'applications.id', 'suspensions.application_id')
          ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
          ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
          ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
          ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
          ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
          ->join('certifications','certifications.decision_id','decisions.id')
          ->select('applications.id', 'applications.application_number','applications.market_status','appeal_document_user',
                    'suspensions.description as suspension_description','medicinal_products.product_trade_name', 
                    'suspensions.appeal_status', 'suspensions.id as suspension_id','suspensions.action_date','suspensions.appeal_description_moh','suspensions.appeal_description_user', 
                    'suspensions.action_date', 'suspensions.suspended_till_date','medicines.product_name','applications.application_id', 'suspensions.sealed_letter',
                     'company_suppliers.trade_name', 'certifications.registration_number','suspensions.decision_response_letter','suspensions.appeal_accepted','suspensions.appeal_document_user',
					 'suspensions.suspension_document','suspensions.appeal_document_moh','suspensions.appeal_description_moh', 'suspensions.decision_response', 
					 'suspensions.response_remark','suspensions.deadline_extension_requested','suspensions.request_deadline_extension_reason','suspensions.request_accepted',
					 'suspensions.request_accepted','dossier_assignments.id as dossier_asg_id', 'suspensions.action_taken')
              ->where('suspensions.id',$suspension_id)
          ->first();
		  
         $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->action_taken)
            ->first();
			
		if($main_task!=null){
          $tasks = TaskTracker::where('task_id', $main_task->id)
            ->OrderBy('task_trackers.id', 'desc')
            ->get();				
			}else{
				$tasks = null;
		}

			return view('suspensions.show_void', compact('application','tasks','main_task'));
        }
		
      public function store(Request $request)
      {
        try {		  
            DB::beginTransaction();
        if($request->input('suspended_till_date')==""){
            $suspension_till_date = null;
        }else{
            $suspension_till_date = $request->input('suspended_till_date');
        }

        $application_id = $request->input('application_id');
		$suspension_date = $request->input('action_date');
        		
        $attached_file = $request->file('suspension_file');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);

       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

        $suspension = new suspension;
        $suspension->application_id = $request->input('application_id');
        $suspension->action_taken = $request->input('action_taken');
        $suspension->description = $request->input('description');
        $suspension->action_date =$request->input('action_date');
        $suspension->suspension_document = $path;
        $suspension->suspended_till_date = $suspension_till_date;
        $suspension->suspension_status = 'active';
        $suspension->save();
		$suspension_id = $suspension->id;

		
		$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Suspension document';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = 'Suspension document';
        $uploaded_document->save();
		
		$application_id = $request->input('application_id');

        applications::where('id',$request->input('application_id'))->update(
            [
                'market_status' => $request->input('action_taken')
            ]
        );

		$suspension_date = $request->input('action_date');
            $decision_date = \Carbon\Carbon::create($suspension_date);
            $suspension_till_date = \Carbon\Carbon::create($suspension_till_date);

            $diff_displayed = $suspension_till_date->diffInDays($decision_date);

        $main_task = MainTaskController::insertTask($request->input('action_taken'), $request->input('action_taken'), $suspension_id, $diff_displayed, $suspension_date, $suspension_till_date, $suspension_till_date, "Active", $alert_before_days = 1);

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = "Market Authorization of the product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is ".$request->input('action_taken')." on ".$suspension->action_date.".";
                $new_notification['subject'] = $application->product_trade_name." ".$suspension->action_taken;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;//$application_details->dossier_assignment_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $application->product_trade_name." is ".$suspension->action_taken));

		
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $request->input('action_taken'))
            ->first();
			//dd($main_task->id);
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity","Market Authorization of the ".$application->product_trade_name." ".$suspension->action_taken,
	"Market Authorization of the product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is ".$request->input('action_taken')." on ".$suspension->action_date.".", "", "Active", 0);

        DB::commit();
    return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Product market authorization '.$suspension->action_taken.' successfully.');

	} catch (\Exception $e) {
                DB::rollBack();
                return false;
           return Redirect()->back()->with('danger', 'Problem with saving. ' . $e->getMessage());
    }
  }

      public function store_response_letter(Request $request)
      {
        $attached_file = $request->file('decision_response_letter');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);

        $suspension_id = $request->input('suspension_id');
		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();
        $suspension->decision_response = $request->input('decision_response');
        $suspension->response_remark = $request->input('response_remark');
        $suspension->decision_response_letter = $path;
        $suspension->save();
		$suspension_id = $suspension->id;

		$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Decision Response letter';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = 'Decision Response letter';
        $uploaded_document->save();
		
		$application_id = $request->input('application_id');
        
		       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number','dossier_assignments.supervisor_id')
        ->first();

			if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $supervisor = User::find($application->supervisor_id);

               $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Response letter of '.$action_noun.' for the product'.$application->product_trade_name.' received.';
                $new_notification['subject'] = $suspension->action_taken.' letter received.';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = $supervisor->first_name . ' ' . $supervisor->middle_name;
                $new_notification['related_id'] = $suspension_id;//$application_details->dossier_assignment_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->supervisor_id);

					
                Notification::send($supervisor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($supervisor->id, "Response letter of ".$application->product_trade_name." ".$action_noun." received."));


        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Decision response letter received.", "Response letter for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is saved.", "", "Active", 0);

   	  return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Decision Response Letter sent successfully.');
  }
  
    public function store_sealed_letter(Request $request)
      {
        $attached_file = $request->file('sealed_letter');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);

        $suspension_id = $request->input('suspension_id');
		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();
        $suspension->sealed_letter = $path;
        $suspension->save();
		$suspension_id = $suspension->id;

		$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = $suspension->action_taken.' letter.';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = $suspension->action_taken.' letter.';
        $uploaded_document->save();
		
        $application_id = $request->input('application_id');		
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $suspension->action_taken.' letter for the product'.$application->product_trade_name.' received.';
                $new_notification['subject'] = $suspension->action_taken.' letter received.';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;//$application_details->dossier_assignment_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

				if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $application->product_trade_name." ".$action_noun." letter received."));
		
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "$action_noun letter saved and sent.", "Response letter for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is saved.", "", "Active", 0);

   	  return Redirect('/suspensions/show/'.$suspension_id)->with('success', $action_noun.' letter sent successfully.');
  }
  
     public function update(Request $request)
      {
		  $path ="";

		if($request->file('decision_response_letter')!=null)
		{
        $attached_file = $request->file('decision_response_letter');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;
        $attached_file->move($dir, $attached_filename);			
		}

        $suspension_id = $request->input('suspension_id');
		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();
	    $suspension->description = $request->input('description');
        $suspension->appeal_accepted = $request->input('appeal_accepted');
        $suspension->request_accepted = $request->input('request_accepted');
        
        //$suspension->suspended_till_date = $suspension_till_date;
        $suspension->decision_response_letter = $path;
        $suspension->save();
		$suspension_id = $suspension->id;

		
		$application_id = $request->input('application_id');

		       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

		
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is Suspension detail updated.", "", "", "Active", 0);

   	  return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Market authorization details updated successfully.');
  }

       public function request_suspension_deadline_extension(Request $request)
      {
   	  $suspension_id = $request->input('suspension_id');
	  $deadline_extension_requested = $request->input('deadline_extension_requested');
	  $request_deadline_extension_reason = $request->input('request_deadline_extension_reason');
   	$suspension = new suspension;
	$suspension = suspension::where('id',$suspension_id)->first();
        $suspension->deadline_extension_requested = $deadline_extension_requested;
        $suspension->request_deadline_extension_reason = $request_deadline_extension_reason;
        $suspension->request_accepted = "";
        $suspension->save();

     $application_id = $request->input('application_id');		
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number','dossier_assignments.supervisor_id')
        ->first();

			if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $supervisor = User::find($application->supervisor_id);

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Deadline Extension of the suspension of the product '.$application->product_trade_name.' requested to'.$suspension->deadline_extension_requested.' because '.$suspension->deadline_update_reason;
                $new_notification['subject'] = 'Suspension deadline update requested.';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = '';
                $new_notification['from_user'] = $application->supervisor_id;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($application->supervisor_id, "Suspension deadline update of ".$application->product_trade_name."  requested."));
		
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Suspension deadline extension requested.", "Suspension deadline for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is requested.", "", "Active", 0);
   	  return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Deadline extension requested successfully.');
  }
  
       public function update_suspension_deadline(Request $request)
      {

   	  $suspension_id = $request->input('suspension_id');
	  $suspension_till_date = $request->input('new_deadline');
	  $deadline_update_reason = $request->input('deadline_update_reason');
   	$suspension = new suspension;
	$suspension = suspension::where('id',$suspension_id)->first();
        $suspension->suspended_till_date = $suspension_till_date;
        $suspension->deadline_update_reason = $deadline_update_reason;
        $suspension->save();

    	$application_id = $request->input('application_id');		
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

			if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Deadline Extension of the suspension of the product '.$application->product_trade_name.' updated to'.$suspension->suspended_till_date.' because '.$suspension->deadline_update_reason;
                $new_notification['subject'] = 'Suspension deadline of '.$application->product_trade_name.' updated.';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = '';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $action_noun." deadline of ".$application->product_trade_name." updated."));

        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Suspension deadline extended.", "Suspension deadline for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." updated.", "", "Active", 0);

   	  return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Deadline extension updated successfully.');
  }

      public function store_appeal(Request $request)
      {
        try {
            $attached_file = $request->file('appeal_document_user');
            $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

            //todo: change to disk storage
            $dir = 'documents/uploads';
            $path = $dir . '/' . $attached_filename;

            // Upload files (copy files to destination)
            $attached_file->move($dir, $attached_filename);

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
        }

        $suspension_id = $request->input('suspension_id');

        suspension::where('id',$suspension_id)->update(
            [
                'appeal_status' => 'Appealed',
                'appeal_description_user' => $request->input('appeal_description_user'),
                'appeal_document_user' => $path
            ]
        );

			$application_id = $request->input('application_id');	
        $suspension_id = $request->input('suspension_id');
		$suspension = new suspension;
			
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();
		
		    	$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = $suspension->action_taken.' letter.';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = $suspension->action_taken.' letter.';
        $uploaded_document->save();


			if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Appeal document of of the product '.$application->product_trade_name.' is uploaded.';
                $new_notification['subject'] = 'Appeal document of '.$application->product_trade_name.' uploaded';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, "Appeal document of ".$application->product_trade_name." uploaded"));

				$main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Appeal received.", "Appeal for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." received.", "", "Active", 0);
   	
    return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Appeal data saved successfully.');
      }
	  
	  public function store_appeal_moh(Request $request)
      {
	  try {
            $attached_file = $request->file('appeal_document_moh');
            $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

            //todo: change to disk storage
            $dir = 'documents/uploads';
            $path = $dir . '/' . $attached_filename;

            // Upload files (copy files to destination)
            $attached_file->move($dir, $attached_filename);

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
        }

        $suspension_id = $request->input('suspension_id');

		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();

        suspension::where('id',$suspension_id)->update(
            [
               // 'appeal_status' => 'Appealed',
                'appeal_description_moh' => $request->input('appeal_description_moh'),
                'appeal_document_moh' => $path
            ]
        );
		
	$application_id = $request->input('application_id');		
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

    	$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = $suspension->action_taken.' letter.';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = $suspension->action_taken.' letter.';
        $uploaded_document->save();


			if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'MOH appeal decision document of the product '.$application->product_trade_name.' is uploaded.';
                $new_notification['subject'] = 'MOH appeal decision doc of '.$application->product_trade_name.' uploaded';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, "Appeal decision document of ".$application->product_trade_name." uploaded"));

        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "MOH appeal response file saved.", "MOH appeal response for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." saved.", "", "Active", 0);

   	    return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'MOH Appeal decision data saved successfully.');
      }
	  
      public function revoke_decision(Request $request)
      {
        $application_id = $request->input('application_id');
        $suspension_id = $request->input('suspension_id');
        $decision_taken = $request->input('decision_taken');
        $date_reversed = $request->input('date_reversed');
		$decision_reverse_reason = $request->input('decision_reverse_reason');

	$application_id = $request->input('application_id');		
       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();
		
		  $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();
			
	MainTaskController::insertActivity($main_task->id, $date_reversed, $date_reversed, "Activity", "Product ".$decision_taken, 
	"The product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." ".$decision_taken." on ".$date_reversed." because ".$decision_reverse_reason, "", "Active", 0);

			$main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
			->update([
                        'task_status' => 'Terminated'
                    ]);

					$attached_file = $request->file('revoke_document');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;
        $attached_file->move($dir, $attached_filename);

      						
		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();
		$suspension->suspension_status = $decision_taken;
		$suspension->date_reversed = $date_reversed;
		$suspension->decision_reverse_reason = $decision_reverse_reason;
		$suspension->save();

        $uploaded_document = new uploaded_documents;
        $description = $request->input('subject');

        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Void document of market authorization';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = $description;
        $uploaded_document->save();

				if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
					$action_noun_reverse = "Unsuspended";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
					$action_noun_reverse = "Unceased";
				}
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $action_noun.' of the product '.$application->product_trade_name.' is '.$action_noun_reverse;
                $new_notification['subject'] = $action_noun.' of '.$application->product_trade_name.' '.$action_noun_reverse;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $action_noun." of ".$application->product_trade_name." ".$action_noun_reverse));


		applications::where('id',$request->input('application_id'))->update(
            [
                'market_status' => 'Active'
            ]
        );

		
        suspension::where('id',$request->input('suspension_id'))->update(
            [
                'suspension_status' => $decision_taken
            ]
        );

	//dd($main_task->id);
	
//	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Market Authorization decision revoked.",
//	"Market Authorization for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." revoked.", "", "Active", 0);

		
        return Redirect()->back()->with('success', 'Product '.$decision_taken.' successfully.');
      }
	  
      public function void_decision(Request $request)
      {
        $application_id = $request->input('application_id');
        $suspension_id = $request->input('suspension_id');

        $attached_file = $request->file('appeal_document_user');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        $attached_file->move($dir, $attached_filename);

		$application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();
		
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();

			# Update the task status to be terminated
        $update_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
			->update([
                        'task_status' => 'Terminated'
                    ]);
			

		$suspension = new suspension;
		$suspension = suspension::where('id',$suspension_id)->first();


        $uploaded_document = new uploaded_documents;
        $description = $request->input('subject');

        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Void document of market authorization';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = $description;
        $uploaded_document->save();

				if($suspension->action_taken=="Suspended"){
					$action_noun = "Suspension";
				}elseif($suspension->action_taken=="Ceased"){
					$action_noun = "Cessation";
				}
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $action_noun.' of the product '.$application->product_trade_name.' is voided.';
                $new_notification['subject'] = $action_noun.' of '.$application->product_trade_name.' voided.';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

					
                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $application->product_trade_name." ".$action_noun." voided."));

        applications::where('id',$request->input('application_id'))->update(
            [
                'market_status' => 'Active'
            ]
        );

        suspension::where('id',$request->input('suspension_id'))->update(
            [
                'suspension_status' => 'Void',
                'void_reason' => $request->input('void_reason'),
                'void_remark' => $request->input('void_remark')
            ]
        );
			
    return Redirect('/suspensions/show/'.$suspension_id)->with('success', $action_noun.' decision has been cancelled successfully.');
      }

    public function suspend_to_cease(Request $request)
      {
		  #// Update current suspension status to ceased
		  # End the existing suspension main task
		  #// Insert new suspension
		  #// Create new ceasation task
		  #// Insert new activity
		  
		  $application_id = $request->input('application_id');
		  $suspension_id = $request->input('suspension_id');
        if($request->input('suspended_till_date')==""){
            $suspension_till_date = null;
        }else{
            $suspension_till_date = $request->input('suspended_till_date');
        }

       $application=applications::join('dossier_assignments','dossier_assignments.application_id','applications.id')
        ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->leftjoin('certifications','certifications.decision_id','decisions.id')
		->where('applications.id','=',$application_id)
        ->select('applications.*','medicines.product_name','decisions.decision_status','medicinal_products.product_trade_name',
        'decisions.id as decision_id','decisions.locked','certifications.registration_number')
        ->first();

        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
            ->first();

		$update_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', $application->market_status)
			->update([
                        'task_status' => 'Terminated'
                    ]);
        
        suspension::where('id',$request->input('suspension_id'))->update(
            [
                'suspension_status' => 'Ceased',
            ]
        );

        $attached_file = $request->file('suspension_file');
        $attached_filename = time() . '_' . $attached_file->getClientOriginalName();

        //todo: change to disk storage
        $dir = 'documents/uploads';
        $path = $dir . '/' . $attached_filename;

        // Upload files (copy files to destination)
        $attached_file->move($dir, $attached_filename);


        $suspension = new suspension;
        $suspension->application_id = $request->input('application_id');
        $suspension->action_taken = 'Ceased';
        $suspension->description = $request->input('description');
        $suspension->action_date =$request->input('action_date');
        $suspension->suspension_document = $path;
        $suspension->suspended_till_date = $suspension_till_date;
        // insert records
        $suspension->save();
		$suspension_id = $suspension->id;
	
		
		$uploaded_document = new uploaded_documents;
        $uploaded_document->related_id = $suspension_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Suspension document';
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 23;
        $uploaded_document->description = 'Suspension document';
        // insert records
        $uploaded_document->save();


		$application_id = $request->input('application_id');

        applications::where('id',$request->input('application_id'))->update(
            [
                'market_status' =>  'Ceased'
            ]
        );

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = "Market Authorization of the product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." is ".$request->input('action_taken')." on ".$suspension->action_date.".";
                $new_notification['subject'] = $application->product_trade_name." ".$suspension->action_taken;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $suspension_id;//$application_details->dossier_assignment_id;
                $new_notification['remark'] = '';
                $applicant = User::find($application->user_id);

                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $application->product_trade_name." is ".$suspension->action_taken));


				$suspension_date = $request->input('action_date');
		            $decision_date = \Carbon\Carbon::create($suspension_date);
            $suspension_till_date = \Carbon\Carbon::create($suspension_till_date);

            $diff_displayed = $suspension_till_date->diffInDays($decision_date);

		
       $main_task = MainTaskController::insertTask("Ceased", "Ceased", $suspension_id, $diff_displayed, $decision_date, $suspension_till_date, $suspension_till_date, "Active", $alert_before_days = 1);
	
        $main_task = MainTask::where('related_id', $suspension_id)
            ->where('task_name', 'Ceased')
            ->first();

			
	MainTaskController::insertActivity($main_task->id, $suspension->action_date, $suspension->action_date, "Activity", "Market Authorization Ceased.", "Market Authorization for the ".$application->market_status." product ".$application->product_trade_name." [".$application->product_name."] with registration number ".$application->registration_number." Ceased.", "", "Active", 0);

    return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Product market authorization '.$suspension->action_taken.' successfully.');
  }
  
      public function show_report(Request $request)
      {	  
	  
    $company_suppliers = DB::table('company_supplier_template')
										->get();
										
	$countries = DB::table('countries')
						->get();

	$list = [];
	$list_count = count($list);
	$from="";
	$to="";
    $route = "";
	$local_agent = "";
	$report_type = "";
    $country= "";
    	  $country_id= "";
	  $country_name= "";

    $applicant = (object) array( 'id'=>'', 'trade_name'=>'');

	return view('suspensions.show_report', compact('company_suppliers','countries','list','list_count','from','to','route','local_agent','report_type','country','applicant','country_id','country_name'));
	}

      public function get_report(Request $request)
      {	  
		$list = DB::table('applications')
		->selectRaw('market_status')
		->get();  

		dd($list);
		return Redirect('/suspensions/show/'.$suspension_id)->with('success', 'Product market authorization '.$suspension->action_taken.' successfully.');
  }

      public function debug_report(Request $request)
      {	  	  
	  $submit_value = $request->input('submit_value');
	  $raw_where = "";
	  
	  $from_test = $request->input('from_test');
	  $to_test = $request->input('to_test');
	  $from = Carbon::create($from_test)->format('Y-m-d');
	  $to = Carbon::create($to_test)->format('Y-m-d');
	  $route = "";
	  $local_agent = "";
	  $report_type = $request->input('report_type');
	  $country_id= "";
	  $country_name= "";
	  $country= "";
	  $applicant = (object) array( 'id'=>'', 'trade_name'=>'');
	  
	  
if($report_type=="Suspended" ||   $report_type=="Ceased")
{
	  $raw_where = "decision_status='Accepted' and suspensions.action_taken='".$report_type."' and suspensions.action_date between '".$from."' and '".$to."' ";
	  
		if($request->input('route')!="")
		{
			$route = $request->input('route');
				 $raw_where .= " and application_type=$route";
		}

		if($request->input('route_test')!="")
		{
						$route_test = $request->input('route_test');
        $search_history['route_test']=$route_test;
			if (in_array("All", $route_test)) {
}else{
$searched_merged = implode(",", $route_test);	
 $raw_where .= " and application_type in (".$searched_merged.")";
	}
}
		

		if($request->input('company')!="")
		{
			$company = $request->input('company');
				 $raw_where .= " and company_suppliers.trade_name='".$company."'";
				 $applicant = DB::table('company_suppliers')
		->where('trade_name','=',$company)		
		->selectRaw('id, trade_name')
		->first();  
		}

		if($request->input('company_test')!="")
		{
			$all_companies = $request->input('company_test');
        $search_history['company_test']=$all_companies;
		//dd($search_history['company_test']);
			$all_companies2 = array();
			//dd($request->input('company_test'));
			if (in_array("All", $all_companies)) {
}else{
	foreach($all_companies as $company_name){
		$company_add = "'".$company_name."'";
	 array_push($all_companies2,$company_add);
	}
$searched_merged = implode(",", $all_companies2);	
 $raw_where .= " and company_suppliers.trade_name in (".$searched_merged.")";
}
	}

		if($request->input('country_test')!="")
		{
			$all_countries = $request->input('country_test');
			$all_countries2 = array();
		$search_history['country_test']=$all_countries;
		//	dd($request->input('country_test'));
			if (in_array("All", $all_countries)) {
}else{
$searched_merged = implode(",", $all_countries);	
 $raw_where .= " and company_suppliers.country_id in (".$searched_merged.")";
	}
}
	//dd($raw_where);

		if($request->input('country')!="")
		{
			$country = $request->input('country');
			$country_id = $country;
				 $raw_where .= " and company_suppliers.country_id=$country";
				 
				  $country_details = DB::table('countries')
		->where('id','=',$country_id)		
		->selectRaw('id, country_name')
		->first();  
		$country_name = $country_details->country_name;
		}
		
		if($request->input('local_agent')!="")
		{
			$local_agent = $request->input('local_agent');
				 $raw_where .= " and agents.trade_name like '%".$local_agent ."%'";
        $search_history['local_agent']= trim($local_agent);
		//dd($request->input('local_agent'));
		}
		
		//dd($raw_where);

		if($raw_where==""){
	  $list = DB::table('applications')
	    ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
	    ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->join('suspensions', 'applications.id', 'suspensions.application_id')
        ->join('company_suppliers', 'applications.company_supplier_id', 'company_suppliers.id')
        ->join('agents', 'applications.application_id', 'agents.application_id')
		->whereRaw($raw_where)		
		->selectRaw('applications.market_status,applications.application_id,decisions.decision_status, medicinal_products.product_trade_name, suspensions.suspension_status, action_taken, company_suppliers.trade_name')
		->get();  		 
	 }else{
	  $list = DB::table('applications')
	    ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
	    ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->join('suspensions', 'applications.id', 'suspensions.application_id')
        ->join('company_suppliers', 'applications.company_supplier_id', 'company_suppliers.id')
        ->join('agents', 'applications.application_id', 'agents.application_id')
		->selectRaw('applications.application_id,certifications.registration_number, suspensions.action_date, medicinal_products.product_trade_name, suspensions.suspension_status, action_taken, company_suppliers.trade_name')
		->whereRaw($raw_where)		
		->get();  		
	 }
	 
        $search_history['start_date']=$request->input('from_test');
        $search_history['end_date']=$request->input('to_test');
//		dd($search_history['start_date'].",,,".$search_history['end_date']);

//	 dd($search_history['country_test']);
$list_count = count($list);
$company_suppliers = DB::table('company_supplier_template')
										->get();
										
	$countries = DB::table('countries')
						->get();
		
				if($submit_value=="searching")
				{
		

        return view('suspensions.show_report',['search_history'=>$search_history], compact('company_suppliers','countries','list','list_count','from','to','route','local_agent','report_type','country', 'applicant','country_id','country_name'));
		}else{
			
						$html_data = "
								 <style>
								 .body-class{
								}
								.header{
									text-align:center;
									text-decoration: underline;
								}
								.report_body{
								}
								
								.collapsed { 
								border-collapse:collapse; 
								width:90%;
								
								}

								.summary-table { 
								border-collapse:collapse; 
								width:50%;
								
								}
								
								table tr td{
									padding:5px;
									text-align:left;
								margin-left:15px;																
								}
								
								table tr th{
									text-align:left;
								}
								  </style>
								
								<div class='header'> <h3>Medicinal Products Registration Unit Report <br/> 
												From  $from To $to  of  ".$list[0]->action_taken." Products </h3></div>
												
								<div>
								<table class='collapsed'>
								<tr>
								<th>S.N</th>
								<th>Reg. Number</th>
								<th>Company</th>
								<th>Trade Name</th>
								<th>Action Date</th>
								<th>Status</th>
								</tr>
								";
                                    $counter=1;
                                    $suspended_active=0;
                                    $suspended_void=0;
                                    $suspended_unsuspended=0;
                                    $suspended_ceased=0;
                                    $ceased_active=0;
                                    $ceased_void=0;
                                    $ceased_unceased=0;

									for($i=0; $i<$list_count;$i++){
										
										if($list[$i]->action_taken=='Suspended' && $list[$i]->suspension_status=='active'){
											$suspended_active=$suspended_active+1;
										}elseif($list[$i]->action_taken=='Suspended' && $list[$i]->suspension_status=='Unsuspended'){
											$suspended_unsuspended=$suspended_unsuspended+1;									
										}elseif($list[$i]->action_taken=='Suspended' && $list[$i]->suspension_status=='Void')
										{
										$suspended_void=$suspended_void+1;	
										}elseif($list[$i]->action_taken=='Suspended' && $list[$i]->suspension_status=='Ceased')
										{
										$suspended_ceased=$suspended_ceased+1;	
										}elseif($list[$i]->action_taken=='Ceased' && $list[$i]->suspension_status=='Active')
										{
										$ceased_active=$ceased_active+1;	
										}elseif($list[$i]->action_taken=='Ceased' && $list[$i]->suspension_status=='Unceased')
										{
										$ceased_unceased=$ceased_unceased+1;	
										}elseif($list[$i]->action_taken=='Ceased' && $list[$i]->suspension_status=='Void')
										{
										$ceased_void=$ceased_void+1;	
										}

									$html_data.= " <tr><td>".$counter."</td><td>".$list[$i]->registration_number."</td><td>".$list[$i]->trade_name."</td><td>".$list[$i]->product_trade_name."</td><td>".$list[$i]->action_date."</td><td>".$list[$i]->suspension_status."</td></tr>";
									$counter++;
								}
$html_data.="</table>";

if($report_type=="Suspended"){
$summary = '<br/><br/>		<div class="card col-md-5 ml-5">
              <div class="card-header">
                <h3 class="card-title"><B> <u> Report Summary </B> </u></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table summary-table" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Suspended Application Status</th>
                      <th>Qty</th>
                    </tr>
                  </thead>
                  <tbody>
				  <tr>
				  <td>1</td>
				  <td>Active</td>
				  <td><span class="badge badge-btn btn-lg bg-success"> '.$suspended_active.' </span></td>
				  </tr>
				  <tr>
				  <td>2</td>
				  <td>Unsuspended</td>
				  <td><span class="badge badge-btn btn-lg bg-danger"> '.$suspended_unsuspended.' </span></td>
				  </tr>
				  <tr>
				  <td>2</td>
				  <td>Ceased</td>
				  <td><span class="badge badge-btn btn-lg bg-danger"> '.$suspended_ceased.' </span></td>
				  </tr>
				  <tr>
				  <tr>
				  <td>4</td>
				  <td>Void</td>
				  <td><span class="badge badge-btn btn-lg bg-warning"> '.$suspended_void.' </span></td>
				  </tr>
				  <tr>
				  <td></td>
				  <td><B>Total</B></td>
				  <td><span class="badge badge-btn btn-lg bg-secondary"> <B> <U>'.($suspended_unsuspended +$suspended_ceased +$suspended_active+$suspended_void).' </span></U></B></td>
				  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
';
}elseif($report_type=="Ceased"){
$summary = '<br/><br/>		<div class="card col-md-5 ml-5">
              <div class="card-header">
                <h3 class="card-title"><B> <u> Report Summary </B> </u></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table summary-table" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Ceased Application Status</th>
                      <th>Qty</th>
                    </tr>
                  </thead>
                  <tbody>
				  <tr>
				  <td>1</td>
				  <td>Active</td>
				  <td><span class="badge badge-btn btn-lg bg-success"> '.$ceased_active.' </span></td>
				  </tr>
				  <tr>
				  <td>2</td>
				  <td>Unceased</td>
				  <td><span class="badge badge-btn btn-lg bg-primary"> '.$ceased_unceased.' </span></td>
				  </tr>
				  <tr>
				  <td>3</td>
				  <td>Void</td>
				  <td><span class="badge badge-btn btn-lg bg-warning"> '.$ceased_void.' </span></td>
				  </tr>
				  <tr>
				  <td></td>
				  <td><B>Total</B></td>
				  <td><span class="badge badge-btn btn-lg bg-secondary"> <B> <U>'.($ceased_active +$ceased_unceased+$ceased_void).' </span></U></B></td>
				  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
';
}

$html_data.=$summary;

								//dd($html_data);
								
		$document = new PDFF([
    'format'=>"A4",
    'margin_header'=>"1",
    'margin_top'=>"30",
    'margin_bottom'=>"20",
    'margin_footer'=>"2",
	'orientation' => "L",
		]
		);

 $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');		
$document->WriteHTML($html_data);
$document->Output();

 //return response()->download($document);
 //$document->Output();

		}
	
}elseif($report_type=="Withdrawn"){

	  $raw_where = " withdrawals.action_taken='Withdrawn' and withdrawals.withdrawal_date_requested between '".$from."' and '".$to."' ";
	  
	if($request->input('route_test')!="")
		{
						$route_test = $request->input('route_test');
        $search_history['route_test']=$route_test;
			if (in_array("All", $route_test)) {
}else{
$searched_merged = implode(",", $route_test);	
 $raw_where .= " and application_type in (".$searched_merged.")";
	}
}

		if($request->input('company_test')!="")
		{
			$all_companies = $request->input('company_test');
        $search_history['company_test']=$all_companies;
		//dd($search_history['company_test']);
			$all_companies2 = array();
			//dd($request->input('company_test'));
			if (in_array("All", $all_companies)) {
}else{
	foreach($all_companies as $company_name){
		$company_add = "'".$company_name."'";
	 array_push($all_companies2,$company_add);
	}
$searched_merged = implode(",", $all_companies2);	
 $raw_where .= " and company_suppliers.trade_name in (".$searched_merged.")";
}
	}
	
			if($request->input('country_test')!="")
		{
			$all_countries = $request->input('country_test');
			$all_countries2 = array();
		$search_history['country_test']=$all_countries;
			if (in_array("All", $all_countries)) {
}else{
$searched_merged = implode(",", $all_countries);	
 $raw_where .= " and company_suppliers.country_id in (".$searched_merged.")";
	}
}

		if($request->input('local_agent')!="")
		{
			$local_agent = $request->input('local_agent');
				 $raw_where .= " and agents.trade_name like '%".$local_agent ."%'";
        $search_history['local_agent']= trim($local_agent);
		}

	 
        $search_history['start_date']=$request->input('from_test');
        $search_history['end_date']=$request->input('to_test');


//dd($raw_where);
		
	  $list = DB::table('applications')
	    ->join('dossier_assignments','dossier_assignments.application_id','applications.id')
	    ->join('decisions','dossier_assignments.id','decisions.dossier_assignment_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
        ->join('certifications','certifications.decision_id','decisions.id')
        ->join('withdrawals', 'applications.id', 'withdrawals.application_id')
        ->join('company_supplier_template', 'applications.company_supplier_id', 'company_supplier_template.id')
        ->join('agents', 'applications.application_id', 'agents.application_id')
		->selectRaw('applications.application_id,certifications.registration_number, withdrawals.withdrawal_date_requested as action_date, medicinal_products.product_trade_name, 
		withdrawals.suspension_status, action_taken, withdrawals.withdrawal_decision, company_supplier_template.trade_name, withdrawals.withdrawal_decision_date')
		->whereRaw($raw_where)		
		->get();
	 
		$list_count = count($list);
		$company_suppliers = DB::table('company_supplier_template')
										->get();
										
	$countries = DB::table('countries')
						->get();
						
//dd($list);

        return view('suspensions.show_report',['search_history'=>$search_history], compact('company_suppliers','countries','list','list_count','from','to','route','local_agent','report_type','country', 'applicant','country_id','country_name'));

						}		
  }

  
}
