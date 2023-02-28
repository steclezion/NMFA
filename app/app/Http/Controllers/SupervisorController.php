<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Models\AssessmentReport;
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
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('dossier_evaluation_progresses', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->where('main_tasks.task_status', 'Locked')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->select('dossier_assignments.*', 'users.first_name', 'users.middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status', 'dossier_evaluation_progresses.evaluation_deadline_extended')
            ->get();

        return view('supervisor.deadline_index', ['locked_dossier_evaluations' => $locked_dossier_evaluations]);

    }


    public function show($id)
    {
        //
        $assessment_report_detail = AssessmentReport::where('assessment_reports.id', $id)
            ->join('users', 'users.id', 'assessment_reports.assessment_from_user_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'assessment_reports.assessment_related_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->select('assessment_reports.*', 'users.first_name', 'users.middle_name', 'applications.application_type')
            ->first();

        $uploaded_document_ids = explode(',', $assessment_report_detail->sent_document_id);

        $uploaded_documents = DB::table('uploaded_documents')
            ->whereIn('id', $uploaded_document_ids)
            ->get();


       /* $uploaded_documents = DB::table('uploaded_documents')
            ->where('related_id', $assessment_report_detail->assessment_related_id)
            ->where('document_type', 7)
            ->get();*/

        // get evaluation_progress_status value
        // if value is 3 (all assessment report are upload)
        // lock upload comment for supervisor in VIEW
        $eval_progress_status = dossier_evaluation_progress::where('dossier_assignment_id', $assessment_report_detail->assessment_related_id)->first();

        if ($assessment_report_detail->assessment_received_date != null) {
            $assessment_reponse_detail = AssessmentReport::where('assessment_reports.id', $id)
                ->select('assessment_reports.*')
                ->first();
            /*$uploaded_documents = DB::table('uploaded_documents')
                ->where('related_id', $assessment_report_detail->assessment_related_id)
                ->where('document_type', 7)
                ->get();*/

            $commented_documents = DB::table('uploaded_documents')
                ->where('related_id', $id)
                ->where('document_type', 16)
                ->get();



            return view('supervisor.assessment_report_detail',
                ['assessment_report_detail' => $assessment_report_detail,
                    'assessment_response_detail' => $assessment_reponse_detail,
                    'commented_documents' => $commented_documents,
                    'uploaded_documents' => $uploaded_documents,
                    'eval_progress_status' => $eval_progress_status]);

        }
        return view('supervisor.assessment_report_detail',
            ['assessment_report_detail' => $assessment_report_detail,
                'uploaded_documents' => $uploaded_documents,
                'eval_progress_status' => $eval_progress_status]);


    }


    public function assessment_report_index()
    {
        $assessment_reports = AssessmentReport::where('assessment_to_user_id', auth()->user()->id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'assessment_reports.assessment_related_id')
            ->join('users', 'users.id', 'assessment_reports.assessment_from_user_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->select('assessment_reports.*', 'dossiers.dossier_ref_num', 'dossier_assignments.id as dossier_id', 'users.first_name', 'users.middle_name')->get();

        return view('supervisor.assessment_report_index', ['assessment_reports' => $assessment_reports]);

    }

    public function completed_assessment_report_index()
    {
        $completed_assessment_assignments = dossier_assignment:://where('dossier_assignments.locked',1)->
        join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->where('dossier_assignments.supervisor_id',auth()->user()->id)
            ->where('main_tasks.related_task','Dossier Evaluation')
            ->where('main_tasks.task_status', 'completed')
            ->orwhere('main_tasks.task_status', 'queued')
            ->select('dossier_assignments.*', 'users.first_name', 'users.middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status')
            ->distinct('dossier_assignments.id')
            ->get();

        return view('supervisor.completed_assessment_report_index', ['completed_assessment_assignments' => $completed_assessment_assignments]);

    }

    public function upload_commented_document(Request $request)
    {
//        dd($request->input('assessment_report'),$request->input('description'),$request->input('description'));

        //get progress status value to name the reports accordingly
        $progress = dossier_evaluation_progress::where('dossier_assignment_id',
            $request->dossier_assignment_id)->first();

        $assessment_progress_status = $progress->assessment_submitted;
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
        } // end for

        $uploaded_document_ids = implode(', ', $uploaded_document_ids);

        try {
            // handle transactions automatically
            DB::transaction(function () use ($request, $path, $description, $uploaded_document_ids, $assessment_report_id) {


                //update quality controls table


                $status = 'Response Received';
                $received_date = date('Y-m-d H:i:s', strtotime('-3'));
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
                $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
                $task_category = 'Document Submission Response';
                $task_activity_title = 'Supervisor has commented';
                $content_details = $description;
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
                $new_notification['data'] = $description;
                $new_notification['subject'] = 'Assessment Report Comment';
                $new_notification['alert_level'] = 'high';
                $new_notification['related_document'] = $uploaded_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $assessment_report->assessment_related_id;
                $new_notification['remark'] = '';
                // ::send($users, new ($invoice));
                $asserssor = User::find($assessment_report->assessment_from_user_id);

                Notification::send($asserssor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($asserssor->id, 'Assessment Report Comment been Uploaded by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name));


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
    // public function decision_que(Request $request)
    // {

    //     try {

    //         $id = $request->id;
    //         $command = $request->command_type;

    //         if($command=='add') {

    //             $return_data = "";
    //             MainTask::where('related_id', $id)->update([
    //                 'task_status' => 'queued'
    //             ]);
    //             return response()->json(['queued' => 'added']);
    //         }
    //         else if($command=='remove'){
    //             MainTask::where('related_id', $id)->update([
    //                 'task_status' => 'completed'
    //             ]);
    //             return response()->json(['queued' => 'removed']);
    //         }
    //         else
    //             {

    //         }
    //         }
    //     catch (\Exception $e) {
    //         return response()->json(['queued' => $e, 'item' => 'error' . $e]);
    //     }
    //     return response()->json(['queued' => false, 'item' => 'item_success']);
    // }
    public function dossier_evaluation_deadline_extension(Request $request)
    {
        $dossier_assing_id = $request->input('dossier_assign_id');
        $description = $request->input('extension_reason');
        $deadline = $request->input('extended_deadline');
        $dossier_assign_details = dossier_assignment::find($dossier_assing_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($dossier_assing_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
        $task_category = 'Dossier Evaluation Deadline Extension';
        $task_activity_title = 'Deadline Extension Request for Locked Dossier Evaluation';
        $supervisor = User::find($dossier_assign_details->supervisor_id); //assessor who assigned the section
        $content_details = 'Dossier Evaluation Extension was Requested by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name .
            '. Date Requested: ' . $deadline;
        $route_link = '';
        $activity_status = 'Inprogress';
        $uploaded_document_id = null;

        //update the dossier evalutaion progrsses evaluation_deadline_extended
        dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assing_id)
            ->update(
                [
                    'evaluation_deadline_extended' => 1
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

        if($command=='add') {

            $return_data = "";
            MainTask::where('related_id', $id)
            ->where('related_task','Dossier Evaluation')
            ->update([
                'task_status' => 'queued'
            ]);
            return response()->json(['queued' => 'added','id'=>$id]);
        }
        else if($command=='remove'){
            MainTask::where('related_id', $id)
            ->where('related_task','Dossier Evaluation')
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

// public function perc_decision_invitation(Request $request)
// {

//     // dd('in perc_decision_invitation');
//     $data_from_textarea=$request->input('data');
//     $ref_num=$request->input('ref_num');
//     $date=$request->input('date');
//       //  This is to show the document in the activities
//       $data='';
//       $data = '<img src="images/nmfa_header.png" width="100%"/>';
//       $data.='<div class="form-group" >
//       <label>Date:</label>
//                               <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
//                             '.$date.'
//                               </span>

//       </div>
//       <br>
//       <div class="form-group" >
//       <label>Ref:</label>
//                               <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
//                                    NMFA/
//                                   </span>
//       </div>
//       <br>
//       <div class="form-group" >
//       <label>To: </label>
//       All PERC committee
//       </div>
//       ';

//       $data .= $data_from_textarea;
//       $data .= '<img src="images/nmfa_footer.png" width="100%"/>';


//       //this for the file name
//       $upload_date = date('Y-m-s-H-m-s');
//       $dir = 'documents/uploads/';
//       $file_name = 'PERC_Invitarion_letter_all.pdf';
//       $uploaded_file_name = $upload_date . $file_name;

//       $pdf = PDF::loadHTML($data);
//       $pdf->setPaper ('A4', 'portrait');
//       $pdf->save ($dir.$uploaded_file_name);
//       $path = $dir . $uploaded_file_name;
//       $description='The product decision date has been set.';

//       $uploaded_document = new uploaded_documents;
//       $uploaded_document->related_id = 0;
//       $uploaded_document->ref_num = '';
//       $uploaded_document->name = 'Invitation to Register Drug';
//       $uploaded_document->path = $path;
//       $uploaded_document->document_type = 12; //TODO fetch from document_type
//       $uploaded_document->description = $description;
//       // insert records
//       $uploaded_document->save();

// $end_time = null;
// $task_category = 'Message';
// $task_activity_title = 'Registration Decision';
// $content_details = $description;
// $route_link = '';
// $activity_status = 'queued';
// $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));

// //this is to update the task activity for all the queued dosseirs supvised by the current user
// $evaluated_dossiers = dossier_assignment::join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
// ->join('main_tasks','main_tasks.related_id','dossier_assignments.id')
// ->where('dossier_assignments.supervisor_id',auth()->user()->id)
// ->where('main_tasks.task_status','queued')
// ->select('dossier_assignments.id')
// ->get();

// $pdf_generated_uploaded_id = $uploaded_document->id;
// foreach($evaluated_dossiers as $dossier_assign)
// {
//   $main_task = $this->get_main_task_id($dossier_assign->id, 'Dossier Evaluation');
//   MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
//   MainTask::where('id',$main_task->id)->update(
//       [
//           'task_status'=>'Decision'
//       ]
//   );
//   Decision::insert(
//       [
//           'dossier_assignement_id'=>$dossier_assign->id,

//       ]
//       );

// }


// //this code is send invitiation to all perc committees individually
//     $percs = DB::table('roles')
//     ->join('model_has_roles','roles.id','model_has_roles.role_id')
//     ->join('users','users.id','model_has_roles.model_id')
//     ->where('roles.name','PERC')
//     ->select('users.*')
//     ->get();

//     foreach ($percs as $perc)
//     {
//         $data='';
//     $data = '<img src="images/nmfa_header.png" width="100%"/>';
//     $data.='<div class="form-group" >
//     <label>Date:</label>
//                             <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
//                            2021/12/21
//                             </span>

//     </div>
//     <br>
//     <div class="form-group" >
//     <label>Ref:</label>
//                             <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
//                                  NMFA/
//                                 </span>
//     </div>
//     <br>
//     <div class="form-group" >
//     <label>To: '.$perc->first_name.' '. $perc->middle_name.'</label>
//     <br>
//     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Profession]<br>
//     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$perc->addressline_one.'<br>
//     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$perc->email.'
//     </div>
//     ';

//     $data .= $data_from_textarea;
//     $data .= '<img src="images/nmfa_footer.png" width="100%"/>';


//     //this for the file name
//     $upload_date = date('Y-m-s-H-m-s ');
//     $dir = 'documents/uploads/';
//     $file_name = 'PERC_Invitarion_letter_'.$perc->id.'.pdf';
//     $uploaded_file_name = $upload_date . $file_name;

//     $pdf = PDF::loadHTML($data);
//     $pdf->setPaper ('A4', 'portrait');
//     $pdf->save ($dir.$uploaded_file_name);
//     $path = $dir . $uploaded_file_name;




//     //what shall we make this related_id
//     $uploaded_document = new uploaded_documents;
//     $uploaded_document->related_id = 0;
//     $uploaded_document->ref_num = '';
//     $uploaded_document->name = 'Invitation pdf to '.$perc->first_name;
//     $uploaded_document->path = $path;
//     $uploaded_document->document_type = 12; //TODO fetch from document_type
//     $uploaded_document->description = $description;
//     // insert records
//     $uploaded_document->save();

//     //instert the variables above to the queries table
//     //insert this into task tracker
//     $new_notification = [];
//     $new_notification['type'] = 'Message';
//     $new_notification['data'] = $description;
//     $new_notification['subject'] = 'PERC Invitation';
//     $new_notification['alert_level'] = 'high';
//     $new_notification['related_document'] = $uploaded_document->id;
//     $new_notification['remark'] = 'remark';

//     $user = User::find($perc->id);

//     Notification::send($user, new QC($new_notification));
//     event(new DossierAssignmentEvent($perc->id, 'Invitation for product Registration Decision Meeting'));








//     }

//     return Redirect()->back()->with('success', 'Invitation Sent to PERC.');

// }


}
