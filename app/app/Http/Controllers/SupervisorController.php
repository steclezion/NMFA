<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Models\AssessmentReport;
use App\Models\Decision;
use App\Models\dossier;
use App\Models\User;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\MainTask;
use App\Models\uploaded_documents;
use App\Notifications\InformationNotification;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\DossierEvaluationController as DossierEvaluationController;

class SupervisorController extends Controller
{

    private function get_main_task_id($dossier_ass_id, $related_type = 'Dossier Evaluation')
    {
        $main_task = MainTask::where('related_id', $dossier_ass_id)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {

            return 0; //means false
        }
    }

    public function deadline_index()
    {
        $locked_dossier_evaluations = dossier_assignment::join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('dossier_evaluation_progresses', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines','medicines.id','medicinal_products.medicine_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('main_tasks.task_status', 'Locked')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->select('dossier_assignments.*', 'users.first_name', 'users.middle_name',
                'applications.application_number', 'dossiers.dossier_ref_num',
                'medicines.product_name as generic_name', 'medicinal_products.product_trade_name as brand_name',
                'company_suppliers.trade_name as company_name',
                'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status', 'main_tasks.deadline as deadline_extended',
                'dossier_evaluation_progresses.evaluation_deadline_extended as extension_request_sent')
            ->get();

        return view('supervisor.deadline_index', ['locked_dossier_evaluations' => $locked_dossier_evaluations]);

    }


    public function show($id)
    {

        $assessment_report_detail = AssessmentReport::where('assessment_reports.id', $id)
            ->join('users', 'users.id', 'assessment_reports.assessment_from_user_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'assessment_reports.assessment_related_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->select('assessment_reports.*', 'users.first_name', 'users.middle_name', 'applications.application_type',
                'dossier_assignments.id as dossier_assignment_id')
            ->first();

        $uploaded_document_ids = explode(',', $assessment_report_detail->sent_document_id);

        $commented_document_ids = explode(',', $assessment_report_detail->received_document_id);

        $uploaded_documents = DB::table('uploaded_documents')
            ->whereIn('id', $uploaded_document_ids)
            ->get();

        $commented_documents = DB::table('uploaded_documents')
            ->whereIn('id', $commented_document_ids)
            ->get();


       /* $uploaded_documents = DB::table('uploaded_documents')
            ->where('related_id', $assessment_report_detail->assessment_related_id)
            ->where('document_type', 7)
            ->get();*/

        // get evaluation_progress_status value
        // if value is 3 (all assessment report are upload)
        // lock upload comment for supervisor in VIEW
        $eval_progress_status = dossier_evaluation_progress::where('dossier_assignment_id',
            $assessment_report_detail->assessment_related_id)->first();

        // added to control view of 'upload button' for assessment comments during deferred evaluation
        $decision = Decision::where('dossier_assignment_id', $assessment_report_detail->dossier_assignment_id)->first();

        // if response is sent
        if ($assessment_report_detail->assessment_received_date != null) {
            $assessment_reponse_detail = AssessmentReport::where('assessment_reports.id', $id)
                ->select('assessment_reports.*')
                ->first();
            /*$uploaded_documents = DB::table('uploaded_documents')
                ->where('related_id', $assessment_report_detail->assessment_related_id)
                ->where('document_type', 7)Assessment_reports/submitted
                ->get();*/
//
//            $commented_documents = DB::table('uploaded_documents')
//                ->where('related_id', $id)
//                ->where('document_type', 28)
//                ->get();
            $response_sent = true;
            $can_comment = false;

            if($assessment_report_detail->name == 'Assessment Report Submission (Final_revised)' or
                $assessment_report_detail->name == 'Assessment Report Submission (Deferment_Final_revised)')
                $can_comment = false;



            //  dd('in if', $response_sent, $can_comment);

            return view('supervisor.assessment_report_detail',
                ['assessment_report_detail' => $assessment_report_detail,
                    'assessment_response_detail' => $assessment_reponse_detail,
                    'commented_documents' => $commented_documents,
                    'uploaded_documents' => $uploaded_documents,
                    'eval_progress_status' => $eval_progress_status,
                    'decision' => $decision,
                    'response_sent' => $response_sent,
                    'can_comment' => $can_comment
                ]);
        }


        // response is not sent
        $response_sent = false;
        $can_comment = true;

        if($assessment_report_detail->name == 'Assessment Report Submission (Final_revised)' or
            $assessment_report_detail->name == 'Assessment Report Submission (Deferment_Final_revised)')
            $can_comment = false;


        //dd('out if', $response_sent, $can_comment);
        return view('supervisor.assessment_report_detail',
            ['assessment_report_detail' => $assessment_report_detail,
                'uploaded_documents' => $uploaded_documents,
                'eval_progress_status' => $eval_progress_status,
                'decision' => $decision,
                'response_sent' => $response_sent,
                'can_comment' => $can_comment
            ]);


    }


    public function assessment_report_index()
    {
        $assessment_reports = AssessmentReport::where('assessment_to_user_id', auth()->user()->id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'assessment_reports.assessment_related_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines','medicines.id','medicinal_products.medicine_id')
            ->join('users', 'users.id', 'assessment_reports.assessment_from_user_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->select('assessment_reports.*', 'dossiers.dossier_ref_num',
                'dossier_assignments.id as dossier_id', 'users.first_name', 'users.middle_name',
                'medicines.product_name')
            ->orderByDesc('assessment_reports.id')
            ->get();

        return view('supervisor.assessment_report_index', ['assessment_reports' => $assessment_reports]);

    }

    public function completed_assessment_report_index()
    {
        $completed_assessment_assignments = dossier_assignment::join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
        ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
        ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
        ->join('medicines','medicines.id','medicinal_products.medicine_id')
        ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->where('dossier_assignments.supervisor_id',auth()->user()->id)
            ->where('main_tasks.related_task','Dossier Evaluation')
            ->whereIn('main_tasks.task_status', ['completed', 'Completed', 'queued', 'Decision'])
            ->select('dossier_assignments.*','dossier_assignments.id as doss_assignment_id', 'dossiers.dossier_ref_num', 'applications.application_number',
                'company_suppliers.trade_name as company_name', 'medicinal_products.product_trade_name', 'medicines.product_name',
            'users.first_name', 'users.middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_duration_days_actual as actual_end_time', 'main_tasks.task_status')
            /*->distinct('dossier_assignments.id')*/
            ->orderByDesc('dossier_assignments.id')
            ->get();


        return view('supervisor.completed_assessment_report_index', ['completed_assessment_assignments' => $completed_assessment_assignments]);

    }

    public function upload_commented_document(Request $request)
    {


        //get progress status value to name the reports accordingly
        $progress = dossier_evaluation_progress::where('dossier_assignment_id',
            $request->dossier_assignment_id)->first();

        list($uploaded_document_ids, $pdf_generated_uploaded_id) =
            DossierEvaluationController::copy_reports_to_server($request, $progress);

        /*$assessment_progress_status = $progress->assessment_submitted;


        if ($assessment_progress_status == 1) {
            $report_sequence = 'First';
        } elseif ($assessment_progress_status == 2) {
            $report_sequence = 'Final';
        } elseif ($assessment_progress_status == 3) {
            $report_sequence = 'Final_revised';
        }


        $i = 0;
        $name = array();
        $uploaded_document_ids = array();

        foreach ($request->file() as $file) {

            if ($request->file('assessment_report_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $name[0] = " Commented Full Assessment Report (" . $report_sequence . ")";
            }
            if ($request->file('assessment_report_smpc_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $name[1] = "Commented Assessment Report SmPC (" . $report_sequence . ")";
            }
            if ($request->file('assessment_report_pils_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $name[2] = "Commented Assessment Report PILs (" . $report_sequence . ")";
            }

            $description = $request->description;
            $dossier_assignment_id = $request->dossier_assignment_id;

            //todo change to storage disk
            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;

            $assessment_report_id = $request->input('assessment_report');

            $file->move($dir, $filename);

            $uploaded_document = new uploaded_documents;
            $uploaded_document->related_id = $assessment_report_id;
            $uploaded_document->name = $name[$i];
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 16; //TODO fetch from document_type
            $uploaded_document->description = $description;
            // insert records
            $saved = $uploaded_document->save();
            if (!$saved) {
                $this->rollback_db('danger', 'ERROR 1: Problem with Insert into table: uploaded_documents. ');
            }
            $pdf_generated_uploaded_id = $uploaded_document->id;
            array_push($uploaded_document_ids, $pdf_generated_uploaded_id);

            $i++;
        } // end for*/

        $uploaded_document_ids = implode(', ', $uploaded_document_ids);
        $assessment_report_id = $request->input('assessment_report');

        try {
            // handle transactions automatically
            DB::transaction(function () use ($request,$uploaded_document_ids, $assessment_report_id) {


                //update quality controls table


                $status = 'Response Received';
                $received_date = date('Y-m-d H:i:s');
                $response_description = $request->description;
                $assessment_report = AssessmentReport::find($assessment_report_id);

                AssessmentReport::where('id', $assessment_report_id)
                    ->update([
                        'status' => 'Commented Assessment Report Sent',
                        'assessment_received_date' => $received_date,
                        'received_document_id' => $uploaded_document_ids,
                        'response_description' => $response_description,

                    ]);


                //update activity for timeline
                $main_task = $this->get_main_task_id($assessment_report->assessment_related_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Document Submission Response';
                $task_activity_title = 'Supervisor has commented';
                $content_details = $response_description;
                $route_link = '';
                $activity_status = 'Inprogress';
                $uploaded_document_id = $assessment_report_id;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $response_description;
                $new_notification['subject'] = 'Assessment Report Comment';
                $new_notification['alert_level'] = 'high';
                $new_notification['related_document'] = $uploaded_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $assessment_report->assessment_related_id;
                $new_notification['remark'] = '';
                // ::send($users, new ($invoice));
                $asserssor = User::find($assessment_report->assessment_from_user_id);

                Notification::send($asserssor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($asserssor->id, 'Assessment Report Comment has been Uploaded by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name));


            });
        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        return Redirect()->back()->with('success', 'Comment has been Successfully uploaded.');

    }
    public function completed_dossier_evaluation_index()
    {
        //
        //here we will add  where clouse for specifing the user
        $completed_dossiers = dossier_assignment::join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users','users.id','dossier_assignments.assessor_id')
            ->join('dossier_evaluation_progresses', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('main_tasks.task_status', 'completed') //completed but waiting for certification
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->select('medicinal_products.product_trade_name','company_suppliers.trade_name as company_name','dossier_assignments.*', 'dossiers.assignment_status', 'dossiers.dossier_ref_num',
                'dossier_evaluation_progresses.day_count as day_count', 'main_tasks.start_time', 'main_tasks.end_time','users.first_name','users.middle_name')
            ->get();
        $breadcrumb_title = 'Dossier Evaluations';
        return view(
            'supervisor.completed_assessment_report_index',
            [
                'completed_assessment_assignments' => $completed_dossiers,
                'breadcrumb_title' => $breadcrumb_title,

            ]
        );
    }


    // This is for Request of extension from assessor (not extension done by supervisor)
    // The extension by supervisor is in DossierEvaluationController > update_deadline > in condition .. ($where_to_update_deadline == 'dossier')
    public function dossier_evaluation_deadline_extension(Request $request)
    {
        $dossier_assing_id = $request->input('dossier_assign_id');
        $description = $request->input('extension_reason');
        $deadline = $request->input('extended_deadline');
        $dossier_assign_details = dossier_assignment::find($dossier_assing_id);
        $dossier = dossier::find($dossier_assign_details->dossier_id);

        //update activity for timeline
        $main_task = $this->get_main_task_id($dossier_assing_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Dossier Evaluation Deadline Extension';
        $task_activity_title = 'Deadline Extension Request for Locked Dossier Evaluation';
        $supervisor = User::find($dossier_assign_details->supervisor_id); //assessor who assigned the section
        $content_details = 'Dossier Evaluation Extension was Requested by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name .
            '. Date Requested: ' . $deadline. ' Dossier Reference Number: '.$dossier->dossier_ref_num;
        $route_link = '';
        $activity_status = 'Inprogress';
        $uploaded_document_id = null;

        //update the dossier evalutaion progrsses evaluation_deadline_extended
        dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assing_id)
            ->update(
                [
                    'evaluation_deadline_extended' => 1  // 1 = request has been sent to supervisor (Deadline is NOT yet 'extended' as the name suggests)
                ]
            );
        //insert this into task tracker
        $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
            $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
        }

        $new_notification = [];
        $new_notification['type'] = 'Notification';
        $new_notification['data'] = $content_details;
        $new_notification['subject'] = $task_category;
        $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
        $new_notification['alert_level'] = 'high';
        $new_notification['related_document'] = '';
        $new_notification['related_id'] = $dossier_assing_id;
        $new_notification['remark'] = '';

        Notification::send($supervisor, new InformationNotification($new_notification));
        event(new DossierAssignmentEvent($supervisor->id, 'Dossier Evaluation Extension was Requested by ' . auth()->user()->first_name));

        return Redirect()->back()->with('success', 'Request for Dossier Evaluation Extension Sent Successfully.');


    }


//this code below is for decision release 3

public function decision_que(Request $request)
{

    try {

        $id = $request->id;
        $command = $request->command_type;
        $type = $request->type;

      if($type=='variation')
      {
          $related_type='Variation';
      }
      else{
        $related_type='Dossier Evaluation';
      }
        if($command=='add') {

            $return_data = "";
            MainTask::where('related_id', $id)
            ->where('related_task',$related_type)
            ->update([
                'task_status' => 'queued'
            ]);
            return response()->json(['queued' => 'added','id'=>$id]);
        }
        else if($command=='remove'){
            MainTask::where('related_id', $id)
            ->where('related_task',$related_type)
            ->update([
                'task_status' => 'completed'
            ]);
            return response()->json(['queued' => 'removed','id'=>$id]);
        }
        else
            {

        }
        }
    catch (\Exception $e) {
        return response()->json(['queued' => $e, 'item' => 'error' . $e]);
    }
    return response()->json(['queued' => false, 'item' => 'item_success']);
}
public function decision_que_onload(Request $request)
{

    try {

            $data=MainTask::join('dossier_assignments','dossier_assignments.id','main_tasks.related_id')
                ->where('dossier_assignments.supervisor_id',auth()->user()->id)
                ->where('main_tasks.related_task','Dossier Evaluation')
                ->where('main_tasks.task_status','completed')
                ->orwhere('main_tasks.task_status','queued')
                ->distinct('main_taks.related_id')
                ->get();
            return response()->json(['data'=>$data]);

    }
    catch (\Exception $e) {
        return response()->json(['queued' => $e, 'item' => 'error' . $e]);
    }
    return response()->json(['queued' => false, 'item' => 'item_success']);
}


public  function supvervisor_ongoing_dossier_tasks()
{
    $assessor_assignment_details = dossier_assignment::where('supervisor_id', auth()->user()->id)
        ->join('applications','applications.id','dossier_assignments.application_id')
        ->join('users', 'users.id', 'dossier_assignments.assessor_id')
        ->join('dossier_evaluation_progresses', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
        ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
        ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
        ->where('main_tasks.related_task', 'Dossier Evaluation')
        ->whereIn("main_tasks.task_status",["Inprogress","pause"])
        ->whereIn('dossiers.assignment_status', [2, 3])  // inprogres, pause
        ->select('dossier_assignments.*', 'dossiers.dossier_ref_num','main_tasks.task_duration_days_plan',
            'main_tasks.task_status','applications.application_number','users.first_name as assessor_first_name','users.middle_name as assessor_middle_name','applications.progress_percentage','dossier_evaluation_progresses.day_count')
        ->get();

    return view('supervisor.supervisor_ongoing_dossier', ['assessor_assignment_details' => $assessor_assignment_details]);


}
}
