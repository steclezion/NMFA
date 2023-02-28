<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Http\Controllers\MainTaskController;
use App\Models\dossier_assignment;
use App\Models\MainTask;
use App\Models\QualityControl;
use App\Models\uploaded_documents;
use App\Models\User;
use App\Notifications\InformationNotification;
use App\Notifications\QC;
use Illuminate\Http\Request;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use PDF;
use \Mpdf\Mpdf as PDFF;
// use mpf\mpf;
use PdfReport;
use Illuminate\Support\Facades\Storage;

class InspectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inspection_request_index()
    {
        //
        $qc_documents = QualityControl::where('inspection_to_user_id',auth()->user()->id)
            ->join('dossier_assignments','dossier_assignments.id','quality_controls.qc_related_id')
            ->join('main_tasks','main_tasks.related_id','quality_controls.qc_related_id')
            ->where('main_tasks.related_task','Dossier Evaluation')
            ->select('quality_controls.*','dossier_assignments.id as dossier_assign_id','main_tasks.task_status')
            ->orderByDesc('quality_controls.inspection_sent_date')
            ->get();


        $qc_staff=DB::table('roles')
            ->join('model_has_roles','roles.id','model_has_roles.role_id')
            ->join('users','users.id','model_has_roles.model_id')
            ->where('roles.name','Quality Control')
            ->get();


        return view('inspection_unit.inspection_request_index',['qc_documents'=>$qc_documents, 'users'=>$qc_staff]);

    }

    public function qc_request_index()
    {
        //
        $qc_documents = QualityControl::where('to_qc_staff_id',auth()->user()->id)
            ->join('dossier_assignments','dossier_assignments.id','quality_controls.qc_related_id')
            ->join('main_tasks','main_tasks.related_id','quality_controls.qc_related_id')
            ->where('related_task','Dossier Evaluation')
            ->select('quality_controls.*','dossier_assignments.id as dossier_assign_id','main_tasks.task_status')
            ->orderByDesc('quality_controls.to_qc_sent_date')
            ->get();

        $qc_staff=DB::table('roles')
            ->join('model_has_roles','roles.id','model_has_roles.role_id')
            ->join('users','users.id','model_has_roles.model_id')
            ->where('roles.name','Quality Control')
            ->get();

        return view('inspection_unit.inspection_request_index',['qc_documents'=>$qc_documents, 'users'=>$qc_staff]);

    }
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


    public function letter_to_qc($id){


        $qc = QualityControl::where('id', $id)->first();

        $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $qc->qc_related_id)
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->leftjoin('checklists', 'checklists.application_id', 'applications.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->select(
                'dossier_assignments.id as dossier_ass_id',
                'dossier_assignments.assigned_datetime',
                'dossiers.dossier_ref_num',
                'dossier_assignments.dossier_id',
                'dossiers.assignment_status',
                'assessors.first_name',
                'assessors.middle_name',
                'supervisors.first_name as name',
                'medicinal_products.*',
                'dosage_forms.name as dosage_name',
                'applications.progress_percentage',
                'applications.application_type',
                'applications.application_number',
                'company_suppliers.trade_name as company_name',
                'company_suppliers.city',
                'company_suppliers.state',
                'company_suppliers.address_line_one',
                'countries.country_name',
                'applicant.first_name as applicant_first_name',
                'applicant.middle_name  as applicant_middle_name',
                'route_administrations.name as route_administration_name',
                'checklists.sample_received_date'
            )
            ->first();

            // dd($dossier_evaluation_details);




        $qc_staff=DB::table('roles')
            ->join('model_has_roles','roles.id','model_has_roles.role_id')
            ->join('users','users.id','model_has_roles.model_id')
            ->where('roles.name','Quality Control')
            ->get();
        $qc_id=$id;
        return view('html_templates.to_qc_from_inspection_unit',
            ['dossier_evaluation_details' => $dossier_evaluation_details, 'users'=>$qc_staff,'qc_id'=>$qc_id]);
    }
    private function date_formatter($date)
    {
        $formatted_date = date($date);
//        dd($formatted_date);
        $date = new \DateTime($formatted_date);
        $formatted_date = $date->format('Y-m-d');
        return ($formatted_date);
    }

    public function send_to_qc_from_inspection(Request $request)
    {

        try {



            $report_file = $request->file('sample_request_file');
            $report_filename = time() . '_' . $report_file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $report_filename;
                $to_user=$request->input('to_user');
            $qc_id=$request->input('request_qc_id');
            $deadline=$request->input('deadline');



            // Upload files (copy files to destination)
            $report_file->move($dir, $report_filename);


    } catch (\Exception $e) {

        return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

    }


        try {
            // handle transactions automatically
            DB::transaction(function () use ( $request,$path,$deadline,$qc_id,$to_user){


        $qc=QualityControl::find($qc_id);
        $dossier_ass_id =$qc->qc_related_id ;



        $dossier_assignment = dossier_assignment::find($dossier_ass_id);
        //get main task id
        $main_task = $this->get_main_task_id($dossier_ass_id, 'Dossier Evaluation');
        //get the end time from the assessor

        // //first make the html and save it as pdf

        $uploaded_document = new uploaded_documents;
        $description = $request->input('subject');

        $uploaded_document->related_id = $dossier_ass_id;
        $uploaded_document->ref_num = '';
        $uploaded_document->name = 'Sample Test Request to QC Unit By '. auth()->user()->first_name .' '. auth()->user()->middle_name ;
        $uploaded_document->path = $path;
        $uploaded_document->document_type = 4;
        $uploaded_document->description = $description;
        // insert records
        $uploaded_document->save();


        $pdf_generated_uploaded_id = $uploaded_document->id;
        //save it in qualty_controls table
        QualityControl::where('id',$qc_id)->update([
            'to_qc_staff_id' => $request->input('to_user'),
            'to_qc_sent_Date' => date('Y-m-d H:i:s'),
            'status' => 'Request Sent To QC ',
            'to_qc_document_id' => $pdf_generated_uploaded_id,
            'to_qc_lab_subject' => $description,
            'qc_deadline'=>$deadline
        ]);


        $end_time = null;
        $task_category = 'Notice';
        $task_activity_title = 'Request for Sample Test Sent To Quality Control Unit By '.auth()->user()->first_name.' '.auth()->user()->middle_name ;
        $content_details = $description;
        $route_link = '';
        $activity_status = 'Inprogress';
        $issued_datetime = date('Y-m-d H:i:s');


        //instert the variables above to the queries table


        //insert this into task tracker

        $main_task_inserted=MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
        }
        $new_notification=[];
        $new_notification['type']='Notification';
        $new_notification['data']='Sample test request sent from Inspection Unit.';
        $new_notification['subject']=$description;
        $new_notification['from_user']=auth()->user()->first_name .' '. auth()->user()->middle_name;
        $new_notification['alert_level']='high';
        $new_notification['related_document']=  $pdf_generated_uploaded_id;
        $new_notification['related_id'] = $dossier_ass_id;
        $new_notification['remark']='';
        $user=User::find($to_user);
        Notification::send($user, new InformationNotification($new_notification));
        event (new DossierAssignmentEvent($to_user, 'You have received Sample test Request'));


    });
}
        catch(MainTaskNotInsertedException $e){
            return Redirect()->back()->with('danger',  $e->getMessage());

        }
             catch (\Exception $e) {
                return Redirect()->back()->with('danger', 'Problem with Dossier Section Assignment. ' . $e->getMessage());
            }
            return Redirect('/InspectionRequestController')->with('success', 'Request For Sample Test to QC Sent Successfully.');


    }

    // Yemane Extension


    public function sample_deadline_extension(Request $request)
    {

        $qc_id=$request->input('extension_qc_id');
        $description=$request->input('extension_reason');
        $deadline=$request->input('extended_deadline');
        $qc=QualityControl::find($qc_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($qc->qc_related_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Deadline Extension Request';
        $task_activity_title = 'Deadline Extension Request for Sample Test Response';
        $assessor = User::find($qc->from_user_id); //assessor who requested the test
        $inspection = User::find($qc->inspection_to_user_id); //Inspection who assigned the test
        $content_details = 'Sample Test Response Extension was Requested by '.auth()->user()->first_name .' '.auth()->user()->middle_name .
            '. Date Requested: '.$deadline.'. Reason: '.$description ;
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
        $new_notification['alert_level']='';
        $new_notification['related_document']=  '';
        $new_notification['related_id'] = $qc->qc_related_id;
        $new_notification['remark']='';

        Notification::send($assessor, new InformationNotification($new_notification));

        Notification::send($inspection, new InformationNotification($new_notification));

        event (new DossierAssignmentEvent($qc->inspection_to_user_id, 'Sample Test Response Extension was Requested by '.auth()->user()->first_name));
        event (new DossierAssignmentEvent($qc->from_user_id, 'Sample Test Response Extension was Requested by '.auth()->user()->first_name));
        return Redirect()->back()->with('success', 'Request for Sample Test Response Extension Sent Successfully.');


    }

    // End Yemane Extension
}
