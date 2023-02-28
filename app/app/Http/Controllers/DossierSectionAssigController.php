<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Models\applications;
use App\Models\dossier;
use App\Http\Controllers\NotificationController;
use App\Models\queries;
use App\Models\TaskTracker;
use App\Models\dossier_assignment;
use App\Models\template;
use App\Models\uploaded_documents;
use App\Models\dossier_section_assignment;
use App\Notifications\InformationNotification;
use App\Notifications\QC;
use Illuminate\Support\Facades\DB;
use App\Models\MainTask;
use App\Models\QualityControl;
use App\Models\query;
use App\Models\Attachment;
use App\Models\dossier_evaluation_progress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class DossierSectionAssigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function index()
    {
        //
        $section_assigns =dossier_section_assignment::join('users','dossier_section_assignments.section_from_user_id','users.id')
        ->join('uploaded_documents','uploaded_documents.id','dossier_section_assignments.sent_document_id')
            ->select(
                'users.first_name',
                'dossier_section_assignments.*',
                'uploaded_documents.path'
            )
            ->where('dossier_section_assignments.section_to_user_id',auth()->user()->id)
            ->where('dossier_section_assignments.status','!=','Evaluated')
            ->orderByDesc('dossier_section_assignments.id')
            ->get();
            $breadcrumb_title='Dossier Section Assignment';
            return view('dossier_section_assignment.index',['section_assigns'=>$section_assigns,'breadcrumb_title'=>$breadcrumb_title]);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //#
        $assigned_section =dossier_section_assignment::join('users','dossier_section_assignments.section_from_user_id','users.id')
        ->join('uploaded_documents as requests','requests.id','dossier_section_assignments.sent_document_id')
        ->leftjoin('uploaded_documents as responses','responses.id','dossier_section_assignments.received_document_id')
        ->select(
            'users.first_name',
            'users.middle_name',
            'dossier_section_assignments.*',
            'requests.path as sent_document_path',
            'responses.path as received_document_path'
        )
        ->where('dossier_section_assignments.id',$id)
        ->orderByDesc('dossier_section_assignments.id')
        ->first();

        $assigned_section->section_deadline;

        $diff_in_days = Carbon::create($assigned_section->section_deadline)->diffInDays(Carbon::now());

        $breadcrumb_title='Dossier Section Assignment';
        return view('dossier_section_assignment.detail',
            ['assigned_section'=>$assigned_section , 'diff_in_days' => $diff_in_days ]);
    }


    public function dossier_section_upload(Request $request){
        //TODO: validation
        $assigned_section_id=$request->input('hidden_section_id');
        $section=dossier_section_assignment::find($assigned_section_id);
        // try uploading files, upon success update db details
        try {
            $dossier_assignment_id =$section->section_related_id;

            $report_file = $request->file('section_response_file1');
            $description = $request->input('section_description');
            $report_filename = time() . '_' . $report_file->getClientOriginalName();


            $dir = 'documents/uploads';
            $path = $dir . '/' . $report_filename;

            // Upload files (copy files to destination)
            $report_file->move($dir, $report_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. '.$e->getMessage());

        }


        DB::beginTransaction();
        try {
            $uploaded_document = new uploaded_documents;

            $uploaded_document->related_id = $dossier_assignment_id;
            $uploaded_document->name = 'Assigned Dossier Evaluation Response ';
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 6; //TODO fetch from document_type
            $uploaded_document->description = $description;
            // insert records
            $uploaded_document->save();

            $dossier_section_assignment = dossier_section_assignment::find($assigned_section_id);
            $current_date=date('Y-m-d H:i:s', strtotime('-3'));
            dossier_section_assignment::where('id',$assigned_section_id)
                ->update(
                    [
                        'section_received_date'=>$current_date,
                        'received_document_id'=>$uploaded_document->id,
                        'status'=>'Evaluated',
                        'response_description'=>$description
                    ]

                );


            //update activity for timeline
            $main_task = $this->get_main_task_id($dossier_assignment_id);
            $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
            $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
            $task_category = 'Dossier Section Evalutaion Respones Uploaded';
            $task_activity_title = 'Dossier Section Assignment By '.auth()->user()->first_name.' '.auth()->user()->middle_name;
            $content_details = $description;
            $route_link = '';
            $activity_status = 'inprogress';
            $uploaded_document_id = $uploaded_document->id;

            //insert this into task tracker // $main_task->id
            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

            $new_notification=[];
            $new_notification['type']='Notification';
            $new_notification['data']='Dossier Section Evaluation is uploaded by '.auth()->user()->first_name .' '.auth()->user()->middle_name;
            $new_notification['subject']=$description;
            $new_notification['alert_level']='high';
            $new_notification['related_document']=  $uploaded_document_id;
            $new_notification['from_user']= auth()->user()->first_name . ' '. auth()->user()->middle_name;
            $new_notification['related_id'] = $dossier_assignment_id;
            $new_notification['remark']='remark';
            // ::send($users, new ($invoice));

            //todo from user in notification
            $user=User::find($dossier_section_assignment->section_from_user_id);
            Notification::send($user, new InformationNotification($new_notification));
            event (new DossierAssignmentEvent($dossier_section_assignment->section_from_user_id, 'Evaluation of Assigned Dossier Section was Uploaded by '.auth()->user()->first_name));





        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        DB::commit();
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }
    public function dossier_section_deadline_extension(Request $request)
    {
        $dossier_section_id=$request->input('dossier_section_id');
        $description=$request->input('extension_reason');
        $deadline=$request->input('extended_deadline');
        $section=dossier_section_assignment::find($dossier_section_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($section->section_related_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s', strtotime('-3'));
        $task_category = 'Deadline Extension';
        $task_activity_title = 'Deadline Extension Request for Dossier Section Evaluation';
        $assessor = User::find($section->section_from_user_id); //assessor who assigned the section
        $content_details = 'Dossier Section Evaluation Extension was Requested by '.auth()->user()->first_name .' '.auth()->user()->middle_name .
            '. Date Requested: '.$deadline;
        $route_link = '';
        $activity_status = 'Inprogress';
        $uploaded_document_id = null;

        //insert this into task tracker
        $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
            $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
        }

        $new_notification=[];
        $new_notification['type']='Notification';
        $new_notification['data']=$content_details;
        $new_notification['subject']='Deadline Extension Request';
        $new_notification['from_user']= auth()->user()->first_name .' '. auth()->user()->middle_name;
        $new_notification['alert_level']='high';
        $new_notification['related_document']=  '';
        $new_notification['related_id'] = $section->section_related_id;
        $new_notification['remark']='';

        Notification::send($assessor, new InformationNotification($new_notification));
        event (new DossierAssignmentEvent($section->section_from_user_id, 'Dossier Section Evaluation Extension was Requested by '.auth()->user()->first_name));

        return Redirect()->back()->with('success', 'Request for Dossier Evaluation Extension Sent Successfully.');


    }
    public function finished_index(){

        $section_assigns =dossier_section_assignment::join('users','dossier_section_assignments.section_from_user_id','users.id')
            ->join('uploaded_documents','uploaded_documents.id','dossier_section_assignments.sent_document_id')
            ->select(
                'users.first_name',
                'dossier_section_assignments.*',
                'uploaded_documents.path'
            )
            ->where('dossier_section_assignments.section_to_user_id',auth()->user()->id)
            ->where('dossier_section_assignments.status','Evaluated')
            ->orderByDesc('dossier_section_assignments.id')
            ->get();
        $breadcrumb_title='Dossier Section Assignment';
        return view('dossier_section_assignment.finished_index',['section_assigns'=>$section_assigns,'breadcrumb_title'=>$breadcrumb_title]);

    }
}

