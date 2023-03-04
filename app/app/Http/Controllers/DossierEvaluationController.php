<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Exceptions\MainTaskNotInsertedException;
use App\Exceptions\QueryNotInsertedException;
use App\Http\Controllers\UtilsController as Utils;
use App\Models\agents;
use App\Models\applications;
use App\Models\contacts;
use App\Models\documents;
use App\Models\Variation;
use App\Models\VariationQuery;
use App\Models\AssessmentReport;
use App\Models\Attachment;
use App\Models\Decision;
use App\Models\DefermentQuery;
use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\dossier_section_assignment;
use App\Models\MainTask;
use App\Models\QualityControl;
use App\Models\queries;
use \Mpdf\Mpdf as PDFF;
use App\Models\query_drafts;
use App\Models\TaskTracker;
use App\Models\template;
use App\Models\uploaded_documents;
use App\Models\User;
use App\Notifications\InformationNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Str;


// use Mpdf;
//this is for notificaiton


class DossierEvaluationController extends Controller
{

    //here we have all the private function
    private function insert_queries
    (
        $name,
        $query_from_user_id,
        $query_to_user_id,
        $query_sent_date,
        $status,
        $query_deadline,
        $query_related_id,
        $sent_document_id,
        $request_subject
    )
    {
        try {
            DB::beginTransaction();
            $query = new queries();

            $query->name = $name;
            $query->query_from_user_id = $query_from_user_id; //Generated name
            $query->query_to_user_id = $query_to_user_id; //to applicant
            $query->query_sent_date = $query_sent_date; //current date
            $query->status = $status;
            $query->query_deadline = $query_deadline; //pdf
            $query->query_related_id = $query_related_id; //duration or expire date
            $query->sent_document_id = $sent_document_id; //uploaded document id
            $query->request_subject = $request_subject; //document id
            $query->save();
            // return $query;
            if ($query) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    //this function below retrieves the main task id based on the given assignment id
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

    public function evaluation_queries_index()
    {

        $issue_query_documents = queries::join('dossier_assignments', 'dossier_assignments.id', 'queries.query_related_id')
            ->leftjoin('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('applications.user_id', auth()->user()->id)
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->select('queries.*', 'main_tasks.task_status')
            ->get();


        return view('applicant.query_response', ['issue_query_documents' => $issue_query_documents]);
    }

    public function completed_dossier_evaluation_index()
    {

        $completed_dossiers = DB::table('dossier_assignments')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->whereIn('main_tasks.task_status', ['completed', 'Completed' , 'queued', 'Decision'])
            ->where('dossier_assignments.assessor_id', auth()->user()->id)
            ->select('dossier_assignments.*', 'dossiers.dossier_ref_num',
                'medicines.product_name', 'medicinal_products.product_trade_name',
                'company_suppliers.trade_name as company_name',
                 'main_tasks.task_status', 'main_tasks.start_time', 'main_tasks.task_duration_days_actual as actual_end_time')
            ->orderByDesc('dossier_assignments.id')
            ->get();


        $breadcrumb_title = 'Dossier Evaluations';
        return view(
            'dossier_evaluation.completed_evaluation_index',
            [
                'completed_dossiers' => $completed_dossiers,
                'breadcrumb_title' => $breadcrumb_title,

            ]
        );
    }

    public function index()
    {

        $assigned_dossiers = dossier_assignment::join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('dossier_evaluation_progresses', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('main_tasks.task_status', '!=', 'Decision')
            ->where('dossier_assignments.assessor_id', auth()->user()->id)
            ->whereIn('dossiers.assignment_status', [2, 3, 5]) //Assigned, Inprogress, pending
            ->select('dossier_assignments.*',  'dossiers.assignment_status', 'dossiers.dossier_ref_num',
                'dossier_evaluation_progresses.evaluation_deadline_extended as extension_request_sent',
                'dossier_evaluation_progresses.day_count as day_count',
                'medicines.product_name', 'medicinal_products.product_trade_name',
                'company_suppliers.trade_name as company_name',
                'main_tasks.task_duration_days_plan', 'main_tasks.task_status', 'applications.progress_percentage')
            ->get();


        $breadcrumb_title = 'Dossier Evaluations';
        return view(
            'dossier_evaluation.index',
            [
                'evaluations' => $assigned_dossiers,
                'breadcrumb_title' => $breadcrumb_title,
            ]
        );
    }

    private function date_formatter($date)
    {

        $formatted_date = date($date);
        $date = new \DateTime($formatted_date);
        $formatted_date = $date->format('Y-m-d');
        return ($formatted_date);

    }


    public function download_pdf(Request $request)
    {
        $id = $request->input('std_type');
        $dossier_assignment_id = $request->input('dossier_assignment_id');
        $document = template::where('id', $id)->firstOrFail();
        $path = $document->path;

        $main_task = $this->get_main_task_id($dossier_assignment_id, 'Dossier Evaluation');
        $end_time = null;

        $task_category = 'Message';
        $task_activity_title = 'Dossier Evaluation Template Downloaded';
        $content_details = $document->name . ' is downloaded For evaluation  ';
        $route_link = '';
        $activity_status = 'Inprogress';
        $issued_datetime = date('Y-m-d H:i:s');

        //instert the variables above to the queries table
        $downloaded_documtne_id = $id;

        //insert this into task tracker
        MainTaskController::insertActivity($main_task->id, $issued_datetime,
            $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $downloaded_documtne_id);

        return response()->download($path, $document
            ->original_filename, ['Content-Type' => $document->mime]);
    }

    /**
     * @param $dossier_assignment_id
     * @param $evaluation_document_progress
     * @param $document_type_id
     * @return array
     */
    public function get_assessment_reports($dossier_assignment_id, $evaluation_document_progress, $document_type_id)
    {

        $assessment_reports = AssessmentReport::where('assessment_reports.assessment_related_id', $dossier_assignment_id) //$id is dossier_assignment_id
        ->get();


        $submitted_assessment_reports_array = array();
        $i = 0;

        foreach ($assessment_reports as $assessment_report) {

            $uploaded_document_ids = explode(',', $assessment_report->sent_document_id);


            $uploaded_documents = DB::table('uploaded_documents')
                ->whereIn('id', $uploaded_document_ids) // multiple reports exist for standard evaluation
                ->where('document_type', $document_type_id)
                ->get();


            if (count($uploaded_documents) > 0) {
                $submitted_assessment_reports_array[$i]['uploaded_document'] = $uploaded_documents;
                $submitted_assessment_reports_array[$i]['assessment_report_name'] = $assessment_report->name;
                $submitted_assessment_reports_array[$i]['assessment_received_date'] = $assessment_report->assessment_received_date;
                $submitted_assessment_reports_array[$i]['assessment_sent_date'] = $assessment_report->assessment_sent_date;
                $submitted_assessment_reports_array[$i]['assessment_report_id'] = $assessment_report->id;
                $submitted_assessment_reports_array[$i]['assessment_submitted_to_supervisor'] = $evaluation_document_progress->assessment_submitted_to_supervisor;
            }
            $i++;
        }


        return $submitted_assessment_reports_array;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    private function retrieve_documents($related_id, $document_type_id)
    {

        return uploaded_documents::where(['uploaded_documents.related_id' => $related_id, 'document_type' => $document_type_id])
            ->join('task_trackers', 'task_trackers.uploaded_document_id', 'uploaded_documents.id')
            ->select('task_trackers.*', 'uploaded_documents.name', 'uploaded_documents.description')
            ->get();
    }

    private function retrieve_dossier_section_assignment($dossier_id)
    {
        $dossier_section_assignment = dossier_section_assignment::where(['dossier_id' => $dossier_id])
            ->join('uploaded_documents as sent_documents', 'dossier_section_assignments.sent_document_id', 'sent_documents.id')
            ->join('users', 'users.id', 'dossier_section_assignments.section_to_user_id')
            ->select('dossier_section_assignments.*', 'sent_documents.path', 'users.first_name', 'users.middle_name')
            ->get();

        return $dossier_section_assignment;
    }

    public function edit($id)
    {


        $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $id)
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->select(
                'dossier_assignments.id as dossier_ass_id',
                'dossier_assignments.assessor_id',
                'dossier_assignments.assigned_datetime',
                'dossier_assignments.locked',
                'dossier_assignments.current_tab_id',
                'dossiers.dossier_ref_num',
                'dossier_assignments.dossier_id',
                'dossiers.assignment_status',
                'assessors.first_name',
                'assessors.middle_name',
                'supervisors.first_name as name',
                'supervisors.middle_name as m_name',
                'medicinal_products.*',
                'dosage_forms.name as dosage_name',
                'route_administrations.name as route_of_admin',
                'applications.progress_percentage',
                'applications.application_type',
                'applications.id as app_id',
                'applications.application_number',
                'assessors.email as assessor_email'
            )
            ->first();


        $inspection_users = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Inspection')
            ->get();
//dd(  $dossier_evaluation_details);

        $current_user_id = auth()->user()->id;
        $application = applications::find($dossier_evaluation_details->app_id);
        $users = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Inspection')
            ->orWhere('roles.name', 'Quality Control')
            ->select('users.*')
            ->distinct('users.id')
            ->get();
        $roles = DB::table('roles')
            ->where('roles.name', 'Inspection')
            ->orWhere('roles.name', 'Quality Control')
            ->get();


        $perc_users = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('users.id', '<>', auth()->user()->id)
            ->where('roles.name', 'Assessor')
            ->orWhere('roles.name', 'PERC')
            ->select('users.*')
            ->distinct('users.id')
            ->get();


//          dd($dossier_evaluation_details->application_type);
        $company = DB::table('company_suppliers')->where('id', $application->company_supplier_id)->first();
//        dd($company);
        $agent = agents::find($application->agent_id);
        //  dd($dossier_evaluation_details);
        $agent_contact_person = contacts::where('application_id', $application->application_id)
            ->where('contact_type', 'Agent')
            ->where('user_id', $agent->user_id)
            ->first();

        $fast = template::where('template_type', 2)->get();
        $standard = template::where('template_type', 1)->get();
        $qc_report_template = template::where('template_type', 4)
            ->join('document_types', 'document_types.id', 'templates.template_type')
            ->select('templates.*', 'document_types.document_type')
            ->first();
        $query_cover_letter = template::where('template_type', 5)
            ->join('document_types', 'document_types.id', 'templates.template_type')
            ->select('templates.*', 'document_types.document_type')
            ->first();
        $query_details = template::where('template_type', 15)
            ->select('templates.*')
            ->first();
        $templates = DB::table('templates')->where('is_active', 1)->get();
        $breadcrumb_title = 'Dossier Evaluation Details';
        $evaluation_document_progress = dossier_evaluation_progress::where('dossier_assignment_id', $id)->first();


        // join from assessment report to uploaded or vice-versa does not list expected results, so
        // as a workaround ...
        // fetch all reports of a specific assig-id
        // for each assessment report, finds its corresponding documents and create an array that relates both
        // This is done to control edit/reupload button using received_date from assessment_report (not need to do this for commented reports)
        $document_type_id = 7; // regular
        $submitted_assessment_reports_array = $this->get_assessment_reports($id, $evaluation_document_progress, $document_type_id);


        $commented_assessment_report_documents = uploaded_documents::where(['related_id' => $id, 'document_type' => 16])
            ->orderByDesc('id')
            ->get();


        $document_type_id = 27; // deferral
        $submitted_deferred_assessment_reports_array = $this->get_assessment_reports($id, $evaluation_document_progress, $document_type_id);

        //retrieve commented deferred evaluation reports
        $commented_deferred_evaluation_reports = uploaded_documents::where(['related_id' => $id, 'document_type' => 28])
            ->orderByDesc('id')
            ->get();

        // --------------------- retrieve 'Commented Assessment report' related documents
        /*$assessment_reports = AssessmentReport::where('assessment_reports.assessment_related_id', $id)
            ->select('assessment_reports.*')
            ->get();

        //dd(count($assessment_reports));

        $Commentedassessment_report_docs = uploaded_documents::where(['related_id' => $assessment_reports[1]->assessment_related_id,
            'document_type' => 16])->get();



        $i = 0;
        $commented_assessment_report_documents = [];
        $assessment_report_docs = [];
        while ($i < count($assessment_reports)) {

            $document_type_id = 16;
            $Commentedassessment_report_docs = uploaded_documents::where(['related_id' => $assessment_reports[$i]->assessment_related_id,
                'document_type' => $document_type_id])->get();

            foreach ($Commentedassessment_report_docs as $commented) {
                $commented_assessment_report_documents[] = $commented;
            }
            $i++;
        }*/


        // end --------------------- retrieve 'Commented Assessment report' related documents

        //check whether response/comment has been sent to submitted reports
        // if not submit assessment report button (at view) should be disabled


        $comment_received = true;
        $assessment_reports = AssessmentReport::where('assessment_reports.assessment_related_id', $id)
            ->where('name', '!=', 'Assessment Report Submission (Final_revised)')
            ->where('name', '!=', 'Assessment Report Submission (Deferment_Final_revised)')
            //->where('name','!=', 'Assessment Report Submission (Variation_Final_revised)')
            ->select('assessment_reports.*')
            ->get();

        foreach ($assessment_reports as $assessment_report) {

            if ($assessment_report->received_document_id == null) {
                $comment_received = false;
                break; // one evidence is enough to disable the submit button
            }

        }


        // retrieve 'Quality Control' related documents
        $document_type_id = 4;
        // $qc_documents = $this->retrieve_documents($id, $document_type_id);
        $qc_documents = QualityControl::where('qc_related_id', $id)
            ->orderbyDesc('inspection_sent_date')
            ->get();

        // retrieve 'issue query' related documents
        $document_type_id = 5;
        $issue_query_documents = queries::where('query_related_id', $id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'queries.query_related_id')
            ->leftjoin('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->select('queries.*', 'applications.application_number',
                'medicines.product_name', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name as company_name')
            ->get();
        $document_type_id = 6;

        // path of all dossier files to list in tab_dossier
        $dossier = dossier::find($dossier_evaluation_details->dossier_id);


        try {
            $paths = Storage::disk('dossier')->allFiles($dossier->path);

        } catch (\Exception $e) {
            return Redirect()->back()->with(['danger' => 'Error accessing dossier home directory. ERROR: ' . $e->getMessage()]);
        }

        //task trackers

        $main_task = $this->get_main_task_id($id);
        $tasks = TaskTracker::where('task_id', $main_task->id)
            ->OrderBy('task_trackers.id', 'desc')
            ->get();


        $task_tracker_ids = array();
        foreach ($tasks as $task_tracker) {
            array_push($task_tracker_ids, $task_tracker->id);
        }


        // for timeline
        // fetch document associated with $tasks
        $uploaded_documents = uploaded_documents::join('task_trackers', 'task_trackers.uploaded_document_id', 'uploaded_documents.id')
            ->whereIn('task_trackers.id', $task_tracker_ids)
            ->Where('task_trackers.task_category', '<>', 'Assessment Report')
            ->select('uploaded_documents.path', 'uploaded_documents.id as uploaded_document_id')
            ->get();


        $assessment_reports_for_this_dossier = AssessmentReport::where('assessment_related_id', $id)->get();


        $assessment_reports_ids = uploaded_documents::join('task_trackers', 'task_trackers.uploaded_document_id', 'uploaded_documents.id')
            ->join('assessment_reports', 'assessment_reports.id', 'task_trackers.uploaded_document_id')
            ->whereIn('task_trackers.id', $task_tracker_ids)
            ->Where('task_trackers.task_category', 'Assessment Report')
            ->select('assessment_reports.sent_document_id', 'assessment_reports.received_document_id')
            ->get();


        $uploaded_documents_assessment_reports_sent = '';
        $uploaded_documents_assessment_reports_received = '';
        foreach ($assessment_reports_ids as $assessment_reports_id) {
            $uploaded_documents_assessment_reports_sent = uploaded_documents::whereIn('id', explode(',', $assessment_reports_id->sent_document_id))->get();
            $uploaded_documents_assessment_reports_received = uploaded_documents::whereIn('id', explode(',', $assessment_reports_id->received_document_id))->get();

        }

        $dossier_id = $dossier_evaluation_details->dossier_id;
        $dossier_section_assignment = $this->retrieve_dossier_section_assignment($dossier_id);

        //this code is for dossier section assignment for the select button
        $applicaion_type = $dossier_evaluation_details->application_type;
        if ($applicaion_type == 1) {
            $evaluation_document = template::where('template_type', 1)->get();
        } elseif ($applicaion_type == 2) {
            $evaluation_document = template::where('template_type', 2)->get();

        } else {
            dd('wrong application type');
        }

        //for deferred products
        $decision = Decision::where('dossier_assignment_id', $id)->first();

        $deferment_queries = null;
        if ($decision != null) {
            $deferment_queries = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
                ->leftjoin('uploaded_documents as sent_document', 'sent_document.id', 'deferment_queries.sent_document_id')
                ->leftjoin('uploaded_documents as received_document', 'received_document.id', 'deferment_queries.received_document_id')
                ->select('deferment_queries.*', 'sent_document.path as sent_document_path', 'received_document.path as received_document_path')
                ->get();
        }

        $remaining_evaluation_days = $this->get_evaluation_days_count($day_count_type='remaining_days', $id);
        if($remaining_evaluation_days < 0) {
            $remaining_evaluation_days = 0;
        }


        return view(
            'dossier_evaluation.create',
            [
                'breadcrumb_title' => $breadcrumb_title,
                'standard' => $standard,
                'fast' => $fast,
                'dossier_evaluation_details' => $dossier_evaluation_details,
                'templates' => $templates,
                'qc_report_template' => $qc_report_template,
                'query_cover_letter' => $query_cover_letter,
                'query_details' => $query_details,
//                '$query_cover_letter' => $query_cover_letter,
                'qc_documents' => $qc_documents,
                'issue_query_documents' => $issue_query_documents,
                'tasks' => $tasks,
                'company' => $company,
                'agent' => $agent,
                'dossier_section_assignment' => $dossier_section_assignment,
                'main_task' => $main_task,
                'evaluation_document' => $evaluation_document,
                'evaluation_document_progress' => $evaluation_document_progress,
                'roles' => $roles,
                'application' => $application,
                'paths' => $paths, // individual path of all files of a dossier
                'decision' => $decision,
                'deferment_queries' => $deferment_queries,
                'dossier_path' => $dossier->path,
                'current_user_id' => $current_user_id,
                'submitted_assessment_reports_array' => $submitted_assessment_reports_array,
                'commented_assessment_report_documents' => $commented_assessment_report_documents,
                'submitted_deferred_assessment_reports_array' => $submitted_deferred_assessment_reports_array,
                'commented_deferred_evaluation_reports' => $commented_deferred_evaluation_reports,
                'comment_received' => $comment_received,
                'perc_users' => $perc_users,
                'inspection_users' => $inspection_users,
                'uploaded_documents' => $uploaded_documents,
                'uploaded_documents_assessment_reports_sent' => $uploaded_documents_assessment_reports_sent,
                'uploaded_documents_assessment_reports_received' => $uploaded_documents_assessment_reports_received,
                'assessment_reports_for_this_dossier' => $assessment_reports_for_this_dossier,
                'agent_contact_person' => $agent_contact_person,
                'remaining_evaluation_days' => $remaining_evaluation_days


            ]
        );
    }


    public function delete_document($id)
    {

        DB::beginTransaction();
        try {
            $document = uploaded_documents::findOrFail($id);
            $path = $document->path;

            //delete record from db
            $document->delete();

            //delete file from disk
            unlink($path);

            DB::commit();
            return Redirect()->back()->with('success', 'File Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect()->back()->with(['danger' => 'File Not Deleted. ERROR: ' . $e->getMessage()]);
        }
    }

    public function upload_assessment_report(Request $request)
    {
        // Uploading assessment reports will be accomplished in two steps
        // step 1: copy attached reports files to server (on failure, report error and exit)
        // step 2: insert details of report to db (on failure, rollback and exit)
        try {
            DB::beginTransaction();

            $report_type = $request->report_type;
            $description = $request->report_desc;
            $dossier_assignment_id = $request->dossier_assignment_id;
            $status = Utils::eval_progress_status();


            $progress = dossier_evaluation_progress::where('dossier_assignment_id',
                $request->dossier_assignment_id)->first();


            // step 1/2: copy attached reports files to server

            list($uploaded_document_ids, $pdf_generated_uploaded_id) = $this->copy_reports_to_server($request, $progress);


            /*=================END Uploading documents==================================*/


            // step 2/2: insert details of report to db
            $uploaded_document_ids = implode(', ', $uploaded_document_ids);

            /*-----------------Start inserting assessment report details----------------*/

            $dossier_assignment = dossier_assignment::find($dossier_assignment_id);
            $application = applications::find($dossier_assignment->application_id);

            if ($report_type == 'initial_report') {
                $assessment_progress_status = $progress->assessment_submitted;
                $count_field = 'assessment_submitted';
                $name_prefix = '';

            } elseif ($report_type == 'deferment_report') {
                $assessment_progress_status = $progress->deferred_assessment_submitted;
                $count_field = 'deferred_assessment_submitted';
                $name_prefix = 'Deferment_';

            } elseif ($report_type == 'variation_report') {
                //todo: add status field to table
                //$assessment_progress_status = $progress->variation_assessment_submitted;
                //$count_field = 'variation_assessment_submitted';
                //$name_prefix = 'Variation_';
            } else {
                //do nothing
            }

            if ($assessment_progress_status == 0) {
                $updated = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)
                    ->update(
                        [
                            $count_field => 1,
                        ]
                    );
                if (!$updated) {
                    $this->rollback_db('danger', 'ERROR 2: Problem with Updating table: dossier_evaluation_progress. ');
                }

                $name = 'Assessment Report Submission (' . $name_prefix . 'First)';
                $assessment_report = new AssessmentReport();
                $assessment_report->name = $name;
                $assessment_report->assessment_from_user_id = auth()->user()->id;
                $assessment_report->assessment_to_user_id = $dossier_assignment->supervisor_id;
                $assessment_report->assessment_sent_date = date('Y-m-d H:i:s');
                $assessment_report->status = $name_prefix . 'Report Sent for Initial Comment';
                $assessment_report->assessment_related_id = $dossier_assignment->id;
                $assessment_report->sent_document_id = $uploaded_document_ids;
                $assessment_report->request_subject = $description;

                $inserted = $assessment_report->save();
                $inserted_assessment_report_id = $assessment_report->id;


                if (!$inserted) {
                    $this->rollback_db('danger', 'ERROR 3: Problem with Insert into table: dossier_evaluation_progress. ');
                } else { //notify

                    // insert notification and fire event
                    $new_notification = [];
                    $new_notification['type'] = 'Notification';
                    $new_notification['data'] = $description;
                    $new_notification['subject'] = $name;
                    $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                    $new_notification['alert_level'] = '';
                    $new_notification['related_document'] = $pdf_generated_uploaded_id;
                    $new_notification['related_id'] = $dossier_assignment_id;
                    $new_notification['remark'] = '';
                    $user = User::find($dossier_assignment->supervisor_id);
                    //$sent = $user->notify(new InformationNotification($new_notification));  // also works
                    $sent = Notification::send($user, new InformationNotification($new_notification));

                    event(new DossierAssignmentEvent($user->id, $name));

                }
            } else if ($assessment_progress_status == 1) {

                $updated = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)
                    ->update(
                        [
                            $count_field => 2,
                        ]
                    );

                if (!$updated) {
                    $this->rollback_db('danger', 'ERROR 4: Problem with Updating table: dossier_evaluation_progress. ');
                }

                //$name = 'Assessment Report Submission (Final)';
                $name = 'Assessment Report Submission (' . $name_prefix . 'Final)';
                $assessment_report = new AssessmentReport();
                $assessment_report->name = $name;
                $assessment_report->assessment_from_user_id = auth()->user()->id;
                $assessment_report->assessment_to_user_id = $dossier_assignment->supervisor_id;
                $assessment_report->assessment_sent_date = date('Y-m-d H:i:s');
                $assessment_report->status = $name_prefix . 'Report Sent for Final Comment';
                $assessment_report->assessment_related_id = $dossier_assignment->id;
                $assessment_report->sent_document_id = $uploaded_document_ids;
                $assessment_report->request_subject = $description;

                $inserted = $assessment_report->save();
                $inserted_assessment_report_id = $assessment_report->id;


                if (!$inserted) {
                    $this->rollback_db('danger', 'ERROR 5: Problem with Insert into table: AssessmentReport. ');
                } else { //notify
                    // insert notification and fire event
                    $new_notification = [];
                    $new_notification['type'] = 'Notification';
                    $new_notification['data'] = $description;
                    $new_notification['subject'] = $name;
                    $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                    $new_notification['alert_level'] = '';
                    $new_notification['related_document'] = $pdf_generated_uploaded_id;
                    $new_notification['related_id'] = $dossier_assignment_id;
                    $new_notification['remark'] = '';
                    $user = User::find($dossier_assignment->supervisor_id);
                    //$sent = $user->notify(new InformationNotification($new_notification));  // also works
                    $sent = Notification::send($user, new InformationNotification($new_notification));

                    event(new DossierAssignmentEvent($user->id, $name));
                }

            } else if ($assessment_progress_status == 2) {

                $updated = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)
                    ->update(
                        [
                            $count_field => 3,
                        ]
                    );

                if (!$updated) {
                    $this->rollback_db('danger', 'ERROR 4.1: Problem with Updating table: dossier_evaluation_progress. ');
                }

                //$name = 'Assessment Report Submission (Final_revised)';
                $name = 'Assessment Report Submission (' . $name_prefix . 'Final_revised)';
                $assessment_report = new AssessmentReport();
                $assessment_report->name = $name;
                $assessment_report->assessment_from_user_id = auth()->user()->id;
                $assessment_report->assessment_to_user_id = $dossier_assignment->supervisor_id;
                $assessment_report->assessment_sent_date = date('Y-m-d H:i:s');
                $assessment_report->status = $name_prefix . 'Final_revised Report Sent';
                $assessment_report->assessment_related_id = $dossier_assignment->id;
                $assessment_report->sent_document_id = $uploaded_document_ids;
                $assessment_report->request_subject = $description;

                $inserted = $assessment_report->save();
                $inserted_assessment_report_id = $assessment_report->id;


                if (!$inserted) {
                    $this->rollback_db('danger', 'ERROR 5.1: Problem with Insert into table: AssessmentReport. ');
                } else { //notify
                    // insert notification and fire event
                    $new_notification = [];
                    $new_notification['type'] = 'Notification';
                    $new_notification['data'] = $description;
                    $new_notification['subject'] = $name;
                    $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                    $new_notification['alert_level'] = '';
                    $new_notification['related_document'] = $pdf_generated_uploaded_id;
                    $new_notification['related_id'] = $dossier_assignment_id;
                    $new_notification['remark'] = '';
                    $user = User::find($dossier_assignment->supervisor_id);
                    //$sent = $user->notify(new InformationNotification($new_notification));  // also works
                    $sent = Notification::send($user, new InformationNotification($new_notification));

                    event(new DossierAssignmentEvent($user->id, $name));
                }

            } else {
                //do nothing
            }

            /*=========================END inserting assessment report details==================*/


            $main_task = $this->get_main_task_id($dossier_assignment_id);
            $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Assessment Report';
            $task_activity_title = $name;
            $content_details = $description;
            $route_link = '';
            if ($report_type == 'initial_report')
                $activity_status = $status['INPROGRESS'];
            else  //todo confirm the status of report after deferement, variation ...
                $activity_status = $status['COMPLETED'];

            //insert the above details into task tracker
            $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime,
                $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status,
                $inserted_assessment_report_id);
            if (!$main_task_inserted) {
                $this->rollback_db('danger', 'ERROR 6: Problem with Insert into table: TaskTracker');
            }


            //if everything is ok, commit to db
            // else if anything is wrong and a return statement is present for the error
            // commit will not be reached, hence changes are not saved to db
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = 'FILE: ' . $e->getFile() . '  LINE: ' . $e->getLine() . ' MESSAGE: ' . $e->getMessage();
            return Redirect()->back()->with('danger', 'ERROR Exception: Problem with Assessment Report submission. ' . $error);
        }
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }


    public function upload_qc_report(Request $request)
    {

        //TODO: validation

        // try uploading files, upon success update db details
        try {
            $report_file = $request->file('qc_report_file');
            $description = $request->description;
            $report_filename = time() . '_' . $report_file->getClientOriginalName();

            $attached_file = $request->file('qc_report_attachments');

            $dir = 'documents/uploads';
            $path = $dir . '/' . $report_filename;

            $attachment_available = true;
            if ($attached_file == null) {
                $attached_filename = null;
                $attachment_available = false;
                $attach_path = null;
            } else {
                $attached_filename = time() . '_' . $attached_file->getClientOriginalName();
                $attach_path = $dir . '/' . $attached_filename;
                $attached_file->move($dir, $attached_filename);
            }

            // Upload files (copy files to destination)
            $report_file->move($dir, $report_filename);


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }


        try {
            // handle transactions automatically
            DB::transaction(function () use ($attach_path, $attachment_available, $description, $path, $request) {
                $qc_id = $request->input('hidden_qc_id');
                $qc_details = QualityControl::find($qc_id);
                $dossier_assignment_id = $qc_details->qc_related_id;
                $uploaded_document = new uploaded_documents;

                $uploaded_document->related_id = $dossier_assignment_id;
                //$uploaded_document->ref_num = $request->ref_num;
                $uploaded_document->name = 'Quality Control Report';
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 4; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $uploaded_document_id = $uploaded_document->id;

                //Insert attachment to attachments table
                if ($attachment_available) {
                    Attachment::insert([
                        'uploaded_documents_id' => $uploaded_document_id,
                        'path' => $attach_path,
                    ]);
                }

                //update quality controls table
                $status = 'Response Received';
                $received_date = date('Y-m-d H:i:s');
                $response_description = $request->description;

                QualityControl::where('id', $qc_id)
                    ->update([
                        'status' => $status,
                        'qc_received_date' => $received_date,
                        'received_document_id' => $uploaded_document_id,
                        'attachments_available' => $attachment_available,
                        'response_description' => $response_description,

                    ]);
                $qc_details = QualityControl::find($qc_id);

                //update activity for timeline
                $main_task = $this->get_main_task_id($qc_details->qc_related_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Sample Testing';
                $task_activity_title = 'Quality Control Response';
                $content_details = $description;
                $route_link = '';
                $activity_status = 'Inprogress';
                $uploaded_document_id = $uploaded_document->id;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated to database.');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $description;
                $new_notification['subject'] = 'QC report upload';
                $new_notification['alert_level'] = 'high';
                $new_notification['related_document'] = $uploaded_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_assignment_id;
                $new_notification['remark'] = '';
                $asserssor = User::find($qc_details->from_user_id);
                $inspector = User::find($qc_details->inspection_to_user_id);
//
                Notification::send($asserssor, new InformationNotification($new_notification));
                Notification::send($inspector, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($asserssor->id, 'Lab analysis results has been Uploaded by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name));
                event(new DossierAssignmentEvent($inspector->id, 'Lab analysis results has been Uploaded by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name));

                $dossier_assignment = dossier_assignment::find($dossier_assignment_id);
                $application = applications::find($dossier_assignment->application_id);

                // for standard evaluation, update progress status by 10%
                // fast track has no qc report

                //todo prevent from increasing % by mistake ??
                // lock upload

                if ($application->application_type == 1) {
                    $is_qc_done = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)->first();
                    if ($is_qc_done->qc_sample_is_done == 0) {

                        $new_progress = $application->progress_percentage + 10;
                        applications::where('id', $application->id)
                            ->update([
                                'progress_percentage' => $new_progress,
                            ]);
                        $progress = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)->first();

                        dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)->update(
                            [
                                'qc_sample_is_done' => 1,
                                'progress_percentage' => $progress->progress_percentage + $new_progress,
                            ]);
                    }

                }
            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }

    public function upload_query_response(Request $request)
    {

        try {

            // query response attached in zip file
            $query_file = $request->file('query_response_file');
            $response_description = $request->description;
            $query_filename = time() . '_' . $query_file->getClientOriginalName();

            // official cover letter from the company
            $cover_file = $request->file('query_response_cover_letter');
            $cover_filename = time() . '_' . $cover_file->getClientOriginalName();
            //todo: change dir to storage disk
            $dir = 'documents/uploads';
            $query_attach_path = $dir . '/' . $query_filename;
            $cover_path = $dir . '/' . $cover_filename;

            // Upload files (copy files to destination)
            $query_file->move($dir, $query_filename);
            $cover_file->move($dir, $cover_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($query_attach_path, $response_description, $cover_path, $request) {
                $dossier_assignment_id = $request->input('dossier_assignment_id');
                $uploaded_document = new uploaded_documents;

                $uploaded_document->related_id = $dossier_assignment_id;
                //$uploaded_document->ref_num = $request->ref_num;
                $uploaded_document->name = 'Query Response Cover Letter';
                $uploaded_document->path = $cover_path;
                $uploaded_document->document_type = 5; //TODO fetch from document_type
                $uploaded_document->description = $response_description;
                // insert records
                $uploaded_document->save();
                $uploaded_document_id = $uploaded_document->id;

                //Insert attachment to attachments table
                Attachment::insert([
                    'uploaded_documents_id' => $uploaded_document->id,
                    'path' => $query_attach_path,
                ]);

                //update query details table

                $status = 'Response Received';
                $query_received_date = date('Y-m-d H:i:s');
                $response_description = $request->description;

                queries::where('id', $request->input('hidden_query_id'))
                    ->update([
                        'status' => $status,
                        'query_received_date' => $query_received_date,
                        'received_document_id' => $uploaded_document_id,
                        'attachments_available' => 1,
                        'response_description' => $response_description,

                    ]);


                //update activity for timeline

                $main_task = $this->get_main_task_id($dossier_assignment_id);

                MainTask::where('id', $main_task->id)
                    ->update([
                        'task_status' => 'Inprogress',
                    ]);


                //Change Query x to Query Exchange x
                $query_issued = queries::where('id', $request->input('hidden_query_id'))->first();
                $query_name_by_sequence = explode(' ', $query_issued->name);
                $query_string = $query_name_by_sequence[0];
                $query_number = $query_name_by_sequence[1];
                $modified_query_name = $query_string . ' Exchange ' . $query_number;

                $issued_datetime = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $task_category = 'Query';
                $task_activity_title = 'Query Response: ' . $modified_query_name;
                $content_details = $response_description;
                $route_link = '';
                $activity_status = 'Inprogress';
                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $endtime = null, $task_category,
                    $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated.');
                }

                $dossier_assignment = dossier_assignment::where('id', $dossier_assignment_id)->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $response_description;
                $new_notification['subject'] = 'Query Response';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_assignment_id;
                $new_notification['remark'] = '';

                $assessor = User::find($dossier_assignment->assessor_id);
                Notification::send($assessor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor->id, 'Query Response has been uploaded.'));


            }); // end transaction


        } catch (\Exception $e) {

            return Redirect()->back()->with(['danger' => 'Problem with File Upload. ' . $e->getMessage()]);
        }
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }

    public function upload_assigned_evaluation_response(Request $request)
    {

        //TODO: validation
        $assigned_section_id = $request->input('hidden_section_id');

        // try uploading files, upon success update db details
        try {
            $dossier_assignment_id = $request->input('dossier_assignment_id');

            $report_file = $request->file('section_assigned_response_file');
            $description = $request->input('section_assigned_description');
            $report_filename = time() . '_' . $report_file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $report_filename;

            // Upload files (copy files to destination)
            $report_file->move($dir, $report_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        DB::beginTransaction();
        try {
            $uploaded_document = new uploaded_documents;

            $uploaded_document->related_id = $dossier_assignment_id;
            $uploaded_document->name = 'Assigned Dossier Section Report';
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 6; //TODO fetch from document_type
            $uploaded_document->description = $description;
            // insert records
            $uploaded_document->save();

            $current_date = date('Y-m-d H:i:s');

//update dossier_section table for the comming response
            //            dd(dossier_section_assignment::where('id',$assigned_section_id)->first());

            $dossier_section_assignment = dossier_section_assignment::find($assigned_section_id);
            dossier_section_assignment::where('id', $assigned_section_id)
                ->update(
                    [
                        'section_received_date' => $current_date,
                        'received_document_id' => $uploaded_document->id,
                        'status' => 'Evaluated',
                        'response_description' => $description,
                    ]

                );

            //update activity for timeline
            $main_task = $this->get_main_task_id($dossier_assignment_id);
            $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
            $issued_datetime = date('Y-m-d H:i:s');
            $task_category = 'Dossier Section Assignment';
            $task_activity_title = 'Dossier Section Evaluated';
            $content_details = $description;
            $route_link = '';
            $activity_status = 'Inprogress';
            $uploaded_document_id = $uploaded_document->id;

            //insert this into task tracker // $main_task->id
            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

            $new_notification = [];
            $new_notification['type'] = 'Notification';
            $new_notification['data'] = 'Dossier Section Evaluation is uploaded by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name;
            $new_notification['subject'] = $description;
            $new_notification['alert_level'] = 'high';
            $new_notification['related_document'] = $uploaded_document_id;
            $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
            $new_notification['related_id'] = $dossier_assignment_id;
            $new_notification['remark'] = 'remark';
            // ::send($users, new ($invoice));
            $user = User::find($dossier_section_assignment->section_from_user_id);
            Notification::send($user, new InformationNotification($new_notification));
            event(new DossierAssignmentEvent($dossier_section_assignment->section_from_user_id, 'Assigned Dossier Section was Uploaded by ' . auth()->user()->first_name));

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        DB::commit();
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }


    public function issue_query_index($id)
    {
        //get dossier assign id, get type doc. type
        // fetch details
        // return --
        //
        $issue_query_details = uploaded_documents::where(['related_id' => 1, 'document_type' => 6]);

        return view('dossier_evaluation.create', ['query_issue_details', $issue_query_details]);
    }

    //code for evaluation process send queries
    public function send_query_issue(Request $request)
    {

        try {

            $upload_date = date('Y-m-s-H-m-s ');
            $dir = 'documents/uploads/';
            $file_name = 'Query_letter.pdf';
            $uploaded_file_name = $upload_date . $file_name;

            $data = '<img src="images/nmfa_header.png" width="100%"/>';
            $data .= $request->input('data');
            $data .= '<img src="images/nmfa_footer.png" width="100%"/>';

            //this code is used for word to doc

            $path = $dir . $uploaded_file_name;
            $query_latter = $request->file('query_latter');
            $query_filename = time() . '_' . $query_latter->getClientOriginalName();

            $dir = 'documents/uploads';
            $query_path = $dir . '/' . $query_filename;


            // Upload files (copy files to destination)
            $query_latter->move($dir, $query_filename);

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        $dossier_ass_id = $request->input('dossier_assignment_id');

        try {
            // handle transactions automatically
            DB::transaction(function () use ($query_path, $request) {

                $dossier_ass_id = $request->input('dossier_assignment_id');
                // dd($dossier_ass_id);
                $dossier_assignment = dossier_assignment::find($dossier_ass_id);
                // dd($dossier_assignment);
                //first make the html and save it as pdf
                //

                //we must check if this is the intial query for this dossier
                //if the query is initial update the progress by the specified amount

                $is_initial_query = queries::where('query_related_id', $dossier_ass_id)->first();

                $application = applications::find($dossier_assignment->application_id);

                if ($is_initial_query == null) {
                    if ($application->application_type == 1) {
                        $progress_increment = 30;
                    } else {
                        $progress_increment = 40;
                    }
                    //todo check application type - 1 - standard, 2 fast track ==>done
                    $new_progress = $application->progress_percentage + $progress_increment;
                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $new_progress,
                        ]);
                    $name = 'Query 1';

                    //this code below is for checklist in the progress tab

                    $progress = dossier_evaluation_progress::where('dossier_assignment_id', $dossier_ass_id)->first();
                    if ($progress == null) {
                        dossier_evaluation_progress::insert([
                            'dossier_assignment_id' => $dossier_ass_id,
                            'issue_query_is_done' => 1,
                            'progress_percentage' => $new_progress,
                        ]);
                    } else {
                        dossier_evaluation_progress::where('dossier_assignment_id', $dossier_ass_id)->update(
                            [
                                'issue_query_is_done' => 1,
                                'progress_percentage' => $progress->progress_percentage + $new_progress,
                            ]
                        );
                    }
                } else {
                    $number_queries = queries::where('query_related_id', $dossier_ass_id)->count();
                    $number_queries++;
                    $name = 'Query ' . $number_queries;
                }


                //upload the document
                // //first make the html and save it as pdf
                // //

                $uploaded_document = new uploaded_documents;
                $description = $request->input('query_subject');

                $uploaded_document->related_id = $dossier_ass_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Query Issued ';
                $uploaded_document->path = $query_path;
                $uploaded_document->document_type = 5; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                $deadline_input = $request->input('query_deadline');
                $deadline = $this->date_formatter(($deadline_input));

                $sender = auth()->user()->id; //this is from session
                $receiver = $application->user_id; //this is from session
                $query_sent_time = date('Y-m-d H:i:s');
                $query_type = 'Query Issued';
                $query_deadline = $deadline;
                $query_subject = $request->input('query_subject');
                $query_document_id = $uploaded_document->id; //get it from session
                $query_related_id = $dossier_ass_id;
                //insert into query
                $inserted_query = $this->insert_queries($name, $sender, $receiver, $query_sent_time, $query_type, $query_deadline, $query_related_id, $query_document_id, $query_subject);
                if (!$inserted_query) {
                    throw new QueryNotInsertedException('Cannot insert  Query details. Your Changes have not been updated. ');
                }
                //get main task id
                $main_task = $this->get_main_task_id($dossier_ass_id, 'Dossier Evaluation');
                // Main task is paused
                //

                MainTask::where('id', $main_task->id)
                    ->update([
                        'task_status' => 'pause',
                        'stopping_reason' => 'Query Issued',

                    ]);

                //Change Query x to Query Exchange x
                $query_name_by_sequence = explode(' ', $name);
                $query_string = $query_name_by_sequence[0];
                $query_number = $query_name_by_sequence[1];
                $modified_query_name = $query_string . ' Exchange ' . $query_number;

                //get the end time from the assessor
                $end_time = null;
                $task_category = 'Query';
                $task_activity_title = 'Query Issued: ' . $modified_query_name;
                $content_details = $query_subject;
                $route_link = '';
                $activity_status = 'pause';

                //instert the variables above to the queries table
                $pdf_generated_uploaded_id = $uploaded_document->id;

                //insert this into task tracker
                // $main_task->id
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $query_sent_time, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'You have query regarding  dossier evaluation.';
                $new_notification['subject'] = $query_subject;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $pdf_generated_uploaded_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_ass_id;
                $new_notification['remark'] = '';
                // ::send($users, new ($invoice));
                $user = User::find($receiver);
//
                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($receiver, 'You have query regarding dossier evaluation.  '));
            }); // end transaction
        } catch (QueryNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }


        return Redirect('/dossier_evaluation/edit/' . $dossier_ass_id)->with('success', 'Query Issued to Applicant Successfully.');


    }

    public function download_qualitycontrol_pdf(Request $request)
    {
        $upload_date = date('Y-m-s-H-m-s ');
        $dir = 'documents/uploads/';
        $file_name = 'letter_to_inspection_unit.pdf';
        $uploaded_file_name = $upload_date . $file_name;
        $to_user = $request->input('to_user');
        $subject = $request->input('subject');
        // dd($to_user);


        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

        $data = $request->input('data');


        $document = new PDFF([
                'format' => "A4",
                'margin_header' => "1",
                'margin_top' => "30",
                'margin_bottom' => "20",
                'margin_footer' => "2",
            ]
        );


        $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
        $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');
        $document->WriteHTML($data);


        $path = $dir . '/' . $uploaded_file_name;
        Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));

        return Storage::disk('documents')->download($uploaded_file_name, 'Request', $header);

    }

    public function send_to_inspection(Request $request)
    {

        //header
        try {
            $upload_date = date('Y-m-s-H-m-s ');
            $dir = 'documents/uploads/';
            $file_name = 'letter_to_inspection_unit.pdf';
            $uploaded_file_name = $upload_date . $file_name;
            $to_user = $request->input('to_user');
            $subject = $request->input('subject');
            // dd($to_user);


            $request_latter = $request->file('qc_latter');
            $request_filename = time() . '_' . $request_latter->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $request_filename;


            // Upload files (copy files to destination)
            $request_latter->move($dir, $request_filename);


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        $dossier_ass_id = $request->input('dossier_assignment_id');
        try {
            // handle transactions automatically
            DB::transaction(function () use ($subject, $path, $request, $to_user) {


                $dossier_ass_id = $request->input('dossier_assignment_id');

                $deadline_input = $request->input('deadline');

                $deadline = $this->date_formatter(($deadline_input));

                $dossier_assignment = dossier_assignment::find($dossier_ass_id);
                //get main task id
                $main_task = $this->get_main_task_id($dossier_ass_id, 'Dossier Evaluation');
                //get the end time from the assessor

                // //first make the html and save it as pdf
                // //

                $uploaded_document = new uploaded_documents;
                $description = $request->input('subject');

                $uploaded_document->related_id = $dossier_ass_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Sample Test Request to Inspection Unit ';
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 4; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                //save it in qualty_controls table
                $qc = new QualityControl();
                $qc->from_user_id = auth()->user()->id;
                $qc->inspection_to_user_id = $to_user;
                $qc->inspection_sent_Date = date('Y-m-d H:i:s');
                $qc->status = 'Request Sent to Inspection';
                $qc->qc_related_id = $dossier_ass_id;
                $qc->sent_document_id = $uploaded_document->id;
                $qc->request_subject = $description;
                $qc->save();

                // Upload file (copies file to destination)

                // $path = $file->move($dir, $filename);

                $end_time = null;
                $task_category = 'Message';
                $task_activity_title = 'Request for Sample Test Sent To Inspection Unit';
                $content_details = $description;
                $route_link = '';
                $activity_status = 'Inprogress';
                $issued_datetime = date('Y-m-d H:i:s');

                //instert the variables above to the queries table
                $pdf_generated_uploaded_id = $uploaded_document->id;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $description;
                $new_notification['subject'] = 'You have sample test request. ';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_ass_id;
                $new_notification['alert_level'] = 'high';
                $new_notification['related_document'] = $pdf_generated_uploaded_id;
                $new_notification['remark'] = '';
                $user = User::find($to_user);
                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($to_user, 'You have sample test request'));


            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }


        return Redirect('/dossier_evaluation/edit/' . $dossier_ass_id)->with('success', 'Sample Test Request Sent Successfully.');


    }

    public function assign_dossier_section(Request $request)
    {

        try {
            $dossier_ass_id = $request->input('hidden_dossier_assignment_id');
            $dossier_ass_details = dossier_assignment::find($dossier_ass_id);

            //insert details to uploaded_document

            $file = $request->file('dossier_section_file');
            $filename = time() . '_' . $file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;
            $path = $file->move($dir, $filename);

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            // handle transactions automatically
            DB::transaction(function () use ($path, $request, $dossier_ass_id, $dossier_ass_details) {


                $description = $request->description;

                $uploaded_document = new uploaded_documents();
                $uploaded_document->name = 'Dossier section Assignment'; // todo get from categories table
                $uploaded_document->description = $description;

                $uploaded_document->related_id = $dossier_ass_id; //$request->dossier_id; //todo get from dossier table
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 6; //TODO fetch from document_type

                // insert records
                $uploaded_document->save();
                // Upload file (copies file to destination)


                $generated_document_id = $uploaded_document->id;

                // insert section assignment details
                $assignment = new dossier_section_assignment();
                $to_user = $request->assigned_user;

                //todo get dossier id join to dossier table
                $assignment->dossier_id = $dossier_ass_details->dossier_id;
                $assignment->assignment_description = $description;
                $assignment->section_from_user_id = $dossier_ass_details->assessor_id; //todo get assessor id
                $assignment->section_to_user_id = $to_user; //todo get  from units list
                $assignment->section_sent_date = date('Y-m-d H:i:s');
                $assignment->section_deadline = $request->date_due;
                $assignment->sent_document_id = $generated_document_id;
                $assignment->section_related_id = $dossier_ass_id;
                $assignment->status = 'Inprogress';

                $assignment->save();

                $user = User::find($to_user);
                //todo update progress, activity
                $main_task = $this->get_main_task_id($dossier_ass_id, 'Dossier Evaluation');
                //get the end time from the assessor
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $task_category = 'Message';
                $task_activity_title = 'Dossier section Assignment';
                $content_details = 'Dossier Section was Assigned to ' . $user->first_name . ' ' . $user->middle_name;
                $route_link = '';
                $activity_status = 'Inprogress';
                $issued_datetime = date('Y-m-d H:i:s');

                //instert the variables above to the queries table
                $pdf_generated_uploaded_id = $uploaded_document->id;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $description;
                $new_notification['subject'] = "Dossier Section Assignment";
                $new_notification['alert_level'] = null;
                $new_notification['related_document'] = $pdf_generated_uploaded_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_ass_id;
                $new_notification['remark'] = null;
                // ::send($users, new ($invoice));
                $user = User::find($to_user);

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($to_user, 'New Dossier Section Assigned from ' . auth()->user()->first_name));


            });
        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Dossier Section Assignment. ' . $e->getMessage());
        }
        // todo send notification to Assigned NMFA unit
        return Redirect()->back()->with('success', 'Successfully Assigned Dossier Section.');

    }

    public function update_deadline(Request $request)
    {


        try {
            // handle transactions automatically
            DB::transaction(function () use ($request) {
                $extended_date_time = $request->input('new_deadline');
                $deadline = date($extended_date_time . ' 23:59:59'); //time added to indicate end-of-day as deadline.
                $dossier_ass_id = $request->input('hidden_dossier_asg_id');
                //$deadline = $this->date_formatter(($extended_date_time));
                $extend_reason = $request->input('extend_reason');
                $type = 'Dossier Evaluation';
                //this is for task tracker
                $end_time = null;
                $task_category = 'Extension';

                $issued_datetime = date('Y-m-d H:i:s');

                $where_to_update_deadline = $request->input('type');
                if ($where_to_update_deadline == 'query') {
                    $id = $request->input('query_id');
                    //$task_activity_title = 'Query Issue Response Deadline Extended';
                    $task_activity_title = 'Deadline extension for response of Issued Query.';
                    //$content_details = 'Query Issue Response Deadline ' . $deadline . ' ' . $extend_reason;
                    $content_details = ' Response Deadline of Issued Query extended to: ' . $deadline . '.  Reason: ' . $extend_reason;
                    $route_link = '';
                    $activity_status = 'pause';
                    $query = queries::find($id);
                    $new_deadline_extension_count = $query->query_extend_count + 1;
                    queries::where('id', $id)->update(
                        ['query_deadline' => $deadline,
                            'query_extend_reason' => $extend_reason,
                            'query_extend_count' => $new_deadline_extension_count,
                            'status' => 'Query Issued'
                        ]
                    );
                    $query = queries::find($id);
                    $receiver_id = $query->query_to_user_id;
                    $title = 'Query Response';

                } elseif ($where_to_update_deadline == 'qc') {

                    $id = $request->input('qc_id');
                    $task_activity_title = 'Sample Testing Deadline Extended';
                    $content_details = 'QC Sample deadline extended to: ' . $deadline . ' Reason: ' . $extend_reason;
                    $route_link = '';
                    $activity_status = 'Inprogress';
                    QualityControl::where('id', $id)->update(
                        ['qc_deadline' => $deadline,
                            'qc_extend_reason' => $extend_reason,
                            'status' => 'Request Sent to QC'
                        ]
                    );
                    $title = 'Sample Test';
                    $qc = QualityControl::find($id);
                    $receiver_id = $qc->to_qc_staff_id;
                } elseif ($where_to_update_deadline == 'section') {
                    $section_assignment_id = $request->input('section_deadline_id');
                    $section_assignment = dossier_section_assignment::where('id', $section_assignment_id)->first();

                    //get assigned user and initial assigned date to post on timeline
                    $user = User::where('id', $section_assignment->section_to_user_id)->first();
                    $user_fullname = $user->first_name . " " . $user->middle_name;

                    $initial_assigned_date = $section_assignment->section_sent_date;


                    $id = $request->input('section_deadline_id');
                    $task_activity_title = 'Section Evaluation Deadline Extended ';
                    $content_details = 'Dossier Section Evaluation Deadline Extended from ' . $initial_assigned_date . ' to ' . $deadline .
                        '. Description: ' . $extend_reason . '. Assigned Member: ' . $user_fullname;
                    $route_link = '';
                    $activity_status = 'Inprogress';
                    dossier_section_assignment::where('id', $id)->update(
                        ['section_deadline' => $deadline,
                            'section_extend_reason' => $extend_reason,
                            'status' => 'Inprogress'
                        ]
                    );
                    $section = dossier_section_assignment::find($id);
                    $receiver_id = $section->section_to_user_id;
                    $title = 'Section Evaluation';
                } elseif ($where_to_update_deadline == 'dossier') {
                    $id = $request->input('hidden_dossier_asg_id');
                    $task_activity_title = 'Dossier Evaluation Deadline Extended ';
                    $content_details = 'Locked Dossier Evaluation Extended to ' . $deadline . '. Description: ' . $extend_reason;
                    $route_link = '';
                    $activity_status = 'Inprogress';
                    MainTask::where('related_id', $id)
                        ->where('related_task', 'Dossier Evaluation')->update(
                            [
                                'deadline' => $deadline,
                                'end_time' => $deadline,
                                'task_status' => 'Inprogress'
                            ]
                        );
                    $dossier_assignment = dossier_assignment::find($id);
                    $receiver_id = $dossier_assignment->assessor_id;
                    $title = 'Evaluation Deadline Extension';
                } elseif ($where_to_update_deadline == 'variation') {
                    $id = $request->input('query_id');
                    $task_activity_title = 'Deadline extension for response of Issued Query.';
                    $content_details = ' Response Deadline of Issued Query extended to: ' . $deadline . '.  Reason: ' . $extend_reason;
                    $route_link = '';
                    $activity_status = 'pause';
                    $query = VariationQuery::find($id);
                    $new_deadline_extension_count = $query->query_extend_count + 1;
                    VariationQuery::where('id', $id)->update(
                        ['query_deadline' => $deadline,
                            'query_extend_reason' => $extend_reason,
                            'query_extend_count' => $new_deadline_extension_count,
                            'status' => 'Query Issued'
                        ]
                    );
                    $query = VariationQuery::find($id);
                    $receiver_id = $query->query_to_user_id;
                    $title = 'Query Response';
                    $type = 'Variation';
                    $dossier_ass_id = $query->query_related_id;


                } elseif ($where_to_update_deadline == 'deferment_query') {
                    $id = $request->input('deferment_query_id');
                    //$task_activity_title = 'Query Issue Response Deadline Extended';
                    $task_activity_title = 'Deadline extension for response of Issued Deferment Query.';
                    $content_details = ' Response Deadline of Issued Deferment Query extended to: ' . $deadline . '.  Reason: ' . $extend_reason;
                    $route_link = '';
                    $activity_status = 'Inprogress';

                    DefermentQuery::where('id', $id)->update
                    (
                        [
                            'deadline' => $deadline,
                            'deadline_requested' => 0
                        ]
                    );

                    $deferment_query_applicant = DefermentQuery::join('decisions', 'decisions.id', 'deferment_queries.decision_id')
                        ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                        ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                        ->select('applications.*')
                        ->first();
                    $receiver_id = $deferment_query_applicant->user_id;
                    $title = 'Deferment Query Response';

                } else {

                }
                //insert the variables above to the queries table
                $pdf_generated_uploaded_id = null;
                $main_task = $this->get_main_task_id($dossier_ass_id, $type);

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $content_details;
                $new_notification['subject'] = $task_activity_title;
                $new_notification['alert_level'] = '';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_document'] = $pdf_generated_uploaded_id;
                $new_notification['related_id'] = $dossier_ass_id;
                $new_notification['remark'] = '';
                $user = User::find($receiver_id);

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($receiver_id, 'Deadline for ' . $title . ' is extended to ' . $deadline));


            });
        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Deadline Extension. ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Deadline Extended Successfully');
    }

    public function edit_query_response(Request $request)
    {

        //try upload of files , upon success update db
        try {

            // query response attached in zip file
            $query_file = $request->file('query_response_file1');
            $response_description = $request->description1;
            $query_filename = time() . '_' . $query_file->getClientOriginalName();

            // official cover letter from the company
            $cover_file = $request->file('query_response_cover_letter1');
            $cover_filename = time() . '_' . $cover_file->getClientOriginalName();
            //todo: change to storage disk
            $dir = 'documents/uploads';
            $query_attach_path = $dir . '/' . $query_filename;
            $cover_path = $dir . '/' . $cover_filename;

            // Upload files (copy files to destination)
            $query_file->move($dir, $query_filename);
            $cover_file->move($dir, $cover_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($query_attach_path, $cover_path, $response_description, $request) {
                $dossier_assignment_id = $request->input('dossier_assignment_id');

                // update uploaded documents details
                $query_id = $request->input('hidden_query_id1');
                $query = queries::find($query_id);
                uploaded_documents::where('id', $query->received_document_id)->update(
                    [

                        'description' => $response_description,
                        'path' => $cover_path,
                    ]
                );

                // update attachment details
                Attachment::where('uploaded_documents_id', $query->received_document_id)->update(
                    [

                        'path' => $query_attach_path,
                    ]
                );

                //update query description, dates ...
                $query_received_date = date('Y-m-d H:i:s');
                queries::where('id', $query_id)->update(
                    [
                        'response_description' => $response_description,
                        'query_received_date' => $query_received_date,
                    ]
                );

                //update activity details
                $main_task = $this->get_main_task_id($dossier_assignment_id);
                $issued_datetime = $query_received_date;
                $task_category = 'Query';
                $task_activity_title = 'Query Response Details Updated';
                $content_details = $response_description;
                $route_link = '';
                $activity_status = 'Inprogress';

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $endtime = null, $task_category,
                    $task_activity_title, $content_details, $route_link, $activity_status, $query->received_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated.');
                }

                $dossier_assignment = dossier_assignment::where('id', $dossier_assignment_id)->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $response_description;
                $new_notification['subject'] = 'Query Response';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $query->received_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_assignment_id;
                $new_notification['remark'] = '';

                $assessor = User::find($dossier_assignment->assessor_id);
                Notification::send($assessor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor->id, 'Updated Query Response has been uploaded.'));

            }); // end transaction

        } catch (\Exception $e) {

            return Redirect()->back()->with(['danger' => 'Problem Updating Query Response' . $e->getMessage()]);
        }
        return Redirect()->back()->with('success', 'Response Updated Successfully.');


    }

    public function edit_qc_response(Request $request)
    {

        // try uploading files (upon success update db details)
        try {

            $dir = 'documents/uploads';

            $report_file = $request->file('qc_report_file1');
            $description = $request->qc_description1;
            $report_filename = time() . '_' . $report_file->getClientOriginalName();

            $path = $dir . '/' . $report_filename;

            // Upload files (copy files to destination)
            $report_file->move($dir, $report_filename);

            $attached_file = $request->file('qc_report_attachments1');
            $attachment_available = true;
            if ($attached_file == null) {
                $attached_filename = null;
                $attachment_available = false;
                $attach_path = null;
            } else {
                $attached_filename = time() . '_' . $attached_file->getClientOriginalName();
                $attach_path = $dir . '/' . $attached_filename;
                $attached_file->move($dir, $attached_filename);
            }


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        // update db details
        try {

            DB::transaction(function () use ($attachment_available, $attach_path, $description, $path, $request) {
                // update uploaded documents details
                $qc_id = $request->input('hidden_qc_id1');
                $qc = QualityControl::find($qc_id);
                uploaded_documents::where('id', $qc->received_document_id)->update(
                    [

                        'description' => $description,
                        'path' => $path,
                    ]
                );
                $dossier_assignment_id = $qc->qc_related_id;

                // update attachment details

                if ($attachment_available) {

                    // if there is already an attachment update new path
                    // else if qc report has been uploaded already but without attachment,..
                    // then insert new record for attachment
                    if (Attachment::where('uploaded_documents_id', $qc->received_document_id)->first()){
                        // attachment already exists so update it
                        Attachment::where('uploaded_documents_id', $qc->received_document_id)->update(
                            [

                                'path' => $attach_path,
                            ]
                        );

                        QualityControl::where('id', $qc_id)
                            ->update([
                                'attachments_available' => $attachment_available,
                            ]);

                    }else{ // new record for attachment

                        Attachment::insert([
                            'uploaded_documents_id' => $qc->received_document_id,
                            'path' => $attach_path,
                        ]);

                        QualityControl::where('id', $qc_id)
                            ->update([
                                'attachments_available' => $attachment_available,
                            ]);

                    }


                } else {
                    // no attachment was provided

                    // case 1: if there was attachment previously, remove it (user has chosen to remove it)
                    if (Attachment::where('uploaded_documents_id', $qc->received_document_id)->first()) {
                        Attachment::where('uploaded_documents_id', $qc->received_document_id)->delete();

                        QualityControl::where('id', $qc_id)
                            ->update([
                                'attachments_available' => $attachment_available,
                            ]);

                    } else {
                        // case 2: if previous attachment does not exist
                        // do nothing, cuz this time also no attachment was provided
                    }

                }

                //update qc description, dates ...
                $received_date = date('Y-m-d H:i:s');
                QualityControl::where('id', $qc_id)->update(
                    [
                        'response_description' => $description,
                        'qc_received_date' => $received_date,
                    ]
                );

                //update activity details
                $main_task = $this->get_main_task_id($dossier_assignment_id);
                $issued_datetime = $received_date;
                $task_category = 'Sample Testing';
                $task_activity_title = 'QC Report Details Updated';
                $content_details = $description;
                $route_link = '';
                $activity_status = 'Inprogress';

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $endtime = null, $task_category,
                    $task_activity_title, $content_details, $route_link, $activity_status, $qc->received_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated to database.');
                }

            }); // end transaction
            return Redirect()->back()->with('success', 'QC Report Updated Successfully.');
        } catch (\Exception $e) {

            return Redirect()->back()->with(['danger' => 'Problem Updating QC Report' . $e->getMessage()]);
        }

    }

    public function edit_assessment_report(Request $request)
    {

        //dd($request->input('hidden_document_id'));
        //upload new file, delete old file, update new file details
        try {
            $document_id = $request->input('hidden_document_id');

            //$dossier_assign_id = $request->input('hidden_dossier_assign_id');
            $new_file = $request->file('assess_report_file');
            $filename = time() . '_' . $new_file->getClientOriginalName();
            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;
            $new_file->move($dir, $filename);

            // delete old file
            $old_filepath = public_path($request->hidden_path);
            unlink($old_filepath);

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with file update. ' . $e->getMessage());
        }

        // update db details
        try {
            DB::transaction(function () use ($path, $document_id, $request) {
                uploaded_documents::where('id', $document_id)
                    ->update([
                        'path' => $path,
                    ]);
            }); // end transaction
            return Redirect()->back()->with('success', 'Assessment report updated successfully.');
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem updating database.' . $e->getMessage());
        }


    }

    public function submit_to_supervisor(Request $request)
    {

        try {
            // update progress by 20% (both FT and ST)
            DB::transaction(function () use ($request) {

                $dossier_assignment_id = $request->input('dossier_assignment_id');
                $dossier_assignment = dossier_assignment::find($dossier_assignment_id);
                $application = applications::find($dossier_assignment->application_id);

                $decision = Decision::where('dossier_assignment_id', $dossier_assignment_id)
                    ->where('decision_status', 'Deferred')
                    ->first();

                if ($decision == null) { /* dont update progress if coming via deferral*/
                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $application->progress_percentage + 20,
                        ]);
                    $progress_field = "assessment_submitted_to_supervisor";
                } else {
                    $progress_field = "deferred_assessment_submitted_to_supervisor";
                }

                dossier_evaluation_progress::where('dossier_assignment_id', $dossier_assignment_id)
                    ->update(
                        [
                            $progress_field => 1,
                        ]
                    );
                dossier_assignment::where('id', $dossier_assignment_id)
                    ->update(
                        [
                            'locked' => 1,

                        ]
                    );
                dossier::where('id', $dossier_assignment->dossier_id)->update([
                    'assignment_status' => 4,
                ]);
                $doss = dossier::where('id', $dossier_assignment->dossier_id)->first();
                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Dossier Evaluation of ' . $doss->dossier_ref_num . ' Completed.';
                $new_notification['subject'] = 'Dossier Evaluation Completed';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = '';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $dossier_assignment_id;
                $new_notification['remark'] = '';
                // ::send($users, new ($invoice));
                $user = User::find($dossier_assignment->supervisor_id);
                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($user->id, 'Dossier Evaluation Completed ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name));

                $main_task = $this->get_main_task_id($dossier_assignment_id);
                //update main task
                MainTask::where('id', $main_task->id)->update([
                    'is_complete' => 1,
                    'task_status' => 'completed',
                    'task_duration_days_actual' => Carbon::now()
                ]);
                //get the end time from the assessor
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Assessment Report';
                $task_activity_title = 'Dossier Evaluation Completed';
                $content_details = 'Dossier Evaluation of ' . $doss->dossier_ref_num . ' Completed.';
                $route_link = '';
                $activity_status = 'completed';
                $uploaded_document_id = null;
                //insert this into task tracker

                MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

            });
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Submit-to-Supervisor failed. ' . $e);
        }
        return Redirect()->back()->with('success', 'Submitted to Supervisor successfully.');

    }

    public function retrieve_details(Request $request)
    {
        try {

            $id = $request->id;
            $typ = $request->typ;

            if ($typ == 'qc') {
                $qc = QualityControl::join('users as assessor', 'assessor.id', 'quality_controls.from_user_id')
                    ->leftjoin('users as qc', 'qc.id', 'quality_controls.to_qc_staff_id')
                    ->leftjoin('users as inspection', 'inspection.id', 'quality_controls.inspection_to_user_id')
                    ->where('quality_controls.id', $id)
                    ->select('quality_controls.*', 'assessor.first_name as assessor_first_name', 'assessor.middle_name as assessor_middle_name',
                        'qc.first_name as qc_first_name', 'qc.middle_name as qc_middle_name',
                        'inspection.first_name as inspection_first_name', 'inspection.middle_name as inspection_middle_name')
                    ->first();


                $assessor_document = uploaded_documents::find($qc->sent_document_id);
                $inspection_document = uploaded_documents::find($qc->to_qc_document_id);
                $qc_document = uploaded_documents::find($qc->received_document_id);
                $attachments = [];


                if ($qc->attachments_available) {

                    $attachments = Attachment::where('uploaded_documents_id', $qc->received_document_id)->first();

                }

                // return response()->json(['data' => $qc,'id'=>$id]);
                return response()->json(['data' => $qc, 'assessor_document' => $assessor_document,
                    'inspection_document' => $inspection_document,
                    'qc_document' => $qc_document, 'attachments' => $attachments
                ]);
            } elseif ($typ == 'query') {

                $query = queries::join('users as assessor', 'assessor.id', 'queries.query_from_user_id')
                    ->leftjoin('users', 'users.id', 'queries.query_to_user_id')
                    ->join('contacts as applicant', 'users.id', 'applicant.user_id')
                    ->where('queries.id', $id)
                    ->where('applicant.contact_type', 'Supplier')
                    ->select('queries.*', 'assessor.first_name as assessor_first_name', 'assessor.last_name as assessor_last_name',
                        'applicant.first_name as applicant_first_name', 'applicant.last_name as applicant_last_name')
                    ->first();
                $assessor_document = uploaded_documents::where('id', $query->sent_document_id)->first();
                $applicant_document = uploaded_documents::where('id', $query->received_document_id)->first();


                if ($query->attachments_available != 0) {
                    $attachments = Attachment::where('uploaded_documents_id', $query->received_document_id)->first();

                } else {
                    $attachments = null;
                }

                return response()->json(['data' => $query, 'received_document' => $applicant_document,
                    'sent_document' => $assessor_document, 'attachments' => $attachments
                ]);
            } elseif ($typ == 'section') {
                $section = dossier_section_assignment::join('users as from_user', 'from_user.id', 'dossier_section_assignments.section_from_user_id')
                    ->leftjoin('users as to_user', 'to_user.id', 'dossier_section_assignments.section_to_user_id')
                    ->where('dossier_section_assignments.id', $id)
                    ->select('dossier_section_assignments.*', 'to_user.first_name as section_to_user_first_name', 'to_user.middle_name as section_to_user_middle_name', 'from_user.first_name as section_from_user_first_name', 'from_user.middle_name as section_from_user_middle_name')
                    ->first();
                $received_document = uploaded_documents::find($section->received_document_id);
                $sent_document = uploaded_documents::find($section->sent_document_id);
                return response()->json(['data' => $section, 'received_document' => $received_document,
                    'sent_document' => $sent_document
                ]);
            } elseif ($typ == 'variation') {
                $variation = VariationQuery::join('users as assessor', 'assessor.id', 'variation_queries.query_from_user_id')
                    ->leftjoin('users', 'users.id', 'variation_queries.query_to_user_id')
                    ->join('contacts as applicant', 'users.id', 'applicant.user_id')
                    ->where('variation_queries.id', $id)
                    ->select('variation_queries.*', 'assessor.first_name as assessor_first_name', 'assessor.middle_name as assessor_middle_name',
                        'applicant.first_name as applicant_first_name', 'applicant.last_name as applicant_last_name')
                    ->first();
                $assessor_document = uploaded_documents::where('id', $variation->sent_document_id)->first();
                $applicant_document = uploaded_documents::where('id', $variation->received_document_id)->first();

                if ($variation->attachments_available != 0) {
                    $attachments = Attachment::where('uploaded_documents_id', $variation->received_document_id)->first();

                } else {
                    $attachments = null;
                }

                return response()->json(['data' => $variation, 'received_document' => $applicant_document,
                    'sent_document' => $assessor_document, 'attachments' => $attachments
                ]);
            } else {
                return response()->json(['data' => '']);
            }

        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);
    }

    public function retrieve_unit_staff(Request $request)
    {
        try {

            $id = $request->id;
            $users = DB::table('users')
                ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', $id)
                ->select('users.*')
                ->distinct('users.id')
                ->get();
            $response = '';
            $response .= '<option></option>';
            foreach ($users as $user) {
                $response .= '<option value="' . $user->id . '">' . $user->first_name . ' ' . $user->last_name . '</option>';
            }
            return response()->json(['response' => $response]);


        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);
    }

    public function update_qos_status(Request $request)
    {

        try {
            DB::transaction(function () use ($request) {
                $dossier_progress_id = dossier_evaluation_progress::find($request->id);
                $dossier_assignment = dossier_assignment::find($dossier_progress_id->dossier_assignment_id);
                $application = applications::find($dossier_assignment->application_id);

                $eval_progress = dossier_evaluation_progress::where('id', $request->id)->first();

                // update progress by 20%
                if($eval_progress->QOS_is_done == 1){
                    // do nothing
                    //This forbids adding another 20% if QOS/QIS has been updated already
                    //TODO find out the bug that is adding another 20% somehow..?
                } else{
                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $application->progress_percentage + 20,
                        ]);

                    // update qos/qis status to done
                    dossier_evaluation_progress::where('id', $request->id)
                        ->update(
                            [
                                'QOS_is_done' => 1,
                            ]
                        );
                }

                $main_task = $this->get_main_task_id($dossier_assignment->id);

                $end_time = null;
                $qos_done_datetime = date('Y-m-d H:i:s');
                $task_category = 'Assessment Report';
                $task_activity_title = 'QOS/QIS assessment completed.';
                $content_details = 'QOS/QIS assessment completed.';
                $route_link = '';
                $activity_status = 'Inprogress';
                $uploaded_document_id = null;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id,
                    $qos_done_datetime, $end_time, $task_category, $task_activity_title,
                    $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated to database.');
                } else {
                    // all went well - so notify the assessor him/her self about the successful update.
                    $user = User::find($dossier_assignment->assessor_id);
                    event(new DossierAssignmentEvent($user->id, 'QOS/QOI Assessment Completed.'));

                }

            }); // end transaction

            $dossier_progress_id = dossier_evaluation_progress::find($request->id);
            $dossier_assignment = dossier_assignment::find($dossier_progress_id->dossier_assignment_id);
            $application = applications::find($dossier_assignment->application_id);
            return response()->json(['data' => $application->progress_percentage]);


        } catch (MainTaskNotInsertedException $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
    }

    public function to_qc_from_inspection_view()
    {
        return view('html_templates.to_qc_from_inspection_unit');
    }

    public function supervisor_assessor_initial_submition()
    {
        $submition = dossier_evaluation_progress::where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->join('dossier_assignments', 'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->get();
        return view('dossier_evaluation.supervisor_assessor_submition', ['submition' => $submition]);
    }

    public function download_query_issue(Request $request)
    {

        $upload_date = date('Y-m-s-H-m-s ');
        $dir = 'documents/uploads/';
        $file_name = 'Query_letter.doc';
        $uploaded_file_name = $upload_date . $file_name;

        $data = '<img src="images/nmfa_header.png" width="100%"/>';
        $data .= $request->input('data');
        $data .= '<img src="images/nmfa_footer.png" width="100%"/>';

        require_once "vendor\autoload.php";
        $phpWord = new PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        PhpOffice\PhpWord\Shared\Html::addHtml($section, $data, false, false);
        $phpWord->save("HTML.docx", "Word2007");
        dd('this is download query issue function in DossierEvaluation');
//        $pdf = PDF::loadHTML($data);
        //        $pdf->setPaper ('A4', 'portrait');
        //        $pdf->save ($dir.$uploaded_file_name);
        //        $path = $dir . $uploaded_file_name;
        //        return response()->download($path);

    }

    public function save_to_draft(Request $request)
    {
        $upload_date = date('Y_m_s_H_m_s ');
        $dir = 'documents/uploads/';
        $file_name = 'Query_letter.pdf';
        $uploaded_file_name = $upload_date . $file_name;
        $type = $request->input('type');

        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

        try {
            // update progress by 20% (both FT and ST)
            DB::transaction(function () use ($request, $upload_date, $dir, $file_name, $uploaded_file_name, $header, $type) {
                $html_data = $request->input('data');
                $dossier_ass_id = $request->input('hidden_dossier_asg_id');
                $saved_draft = query_drafts::where('dossier_assignment_id', $dossier_ass_id)->first();

                if ($type == "query_details") {
                    $no_header_footer = true;
                } else {
                    $no_header_footer = false;
                }


                $document = new PDFF([
                        'format' => "A4",
                        'margin_header' => "1",
                        'margin_top' => "30",
                        'margin_bottom' => "20",
                        'margin_footer' => "2",
                    ]
                );


                if ($no_header_footer) {
                } else {
                    $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
                    $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');
                }
                $document->WriteHTML($html_data);


                Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));


//dd($html_data);
                return Storage::disk('documents')->download($uploaded_file_name, 'Request', $header);

            });
        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }


        return Storage::disk('documents')->download($uploaded_file_name, 'Request', $header);


    }

    private function rollback_db($alert_level, $error_message)
    {

        DB::rollBack();
        return Redirect()->back()->with($alert_level, $error_message);
    }

    public function update_dossier_tab(Request $request)
    {
        try {

            $id = $request->dossier_assign_id;
            $tab_id = $request->tab_id;
            db::table('dossier_assignments')->where('id', $id)->update([
                'current_tab_id' => $tab_id
            ]);


            return response()->json(['response' => 'true']);


        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);
    }


    /**
     * @param Request $request
     * @param $progress
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public static function copy_reports_to_server(Request $request, $progress)
    {

        $report_type = $request->report_type;
        $description = $request->report_desc;
        $dossier_assignment_id = $request->dossier_assignment_id;

        /*-----------------START Uploading documents----------------*/

        $assessment_progress_status = $progress->assessment_submitted;
        $deferred_assessment_progress_status = $progress->deferred_assessment_submitted;
        //$report_limit = Config::get('site_vars.assessment_report_submission_limit');


        if ($assessment_progress_status >= 3 and $deferred_assessment_progress_status >= 3)
            dd('Report already sent for 3 times (Initial, Final and Final_revised).');


        if ($report_type == 'initial_report' || $report_type == 'commented_initial_report') {

            if ($report_type == 'initial_report') {
                $document_type = 7;

                if ($assessment_progress_status == 0)
                    $report_sequence = 'First';
                elseif ($assessment_progress_status == 1)
                    $report_sequence = 'Final';
                elseif ($assessment_progress_status == 2)
                    $report_sequence = 'Final_revised';

            } elseif ($report_type == 'commented_initial_report') {
                $document_type = 16;

                if ($assessment_progress_status == 1)
                    $report_sequence = 'First';
                elseif ($assessment_progress_status == 2)
                    $report_sequence = 'Final';
            }
        } elseif ($report_type == 'deferment_report' || $report_type == 'commented_deferment_report') {

            if ($report_type == 'deferment_report') {
                $document_type = 27;
                if ($deferred_assessment_progress_status == 0)
                    $report_sequence = 'Deferment_First';
                elseif ($deferred_assessment_progress_status == 1)
                    $report_sequence = 'Deferment_Final';
                elseif ($deferred_assessment_progress_status == 2)
                    $report_sequence = 'Deferment_Final_revised';
            } elseif ($report_type == 'commented_deferment_report') {
                $document_type = 28;

                if ($deferred_assessment_progress_status == 1)
                    $report_sequence = 'Deferment_First';
                elseif ($deferred_assessment_progress_status == 2)
                    $report_sequence = 'Deferment_Final';
            }
        } else {
            throw new Exception("Invalid report type: " . $report_type);
        }

        $name = array();
        $uploaded_document_ids = array();
        $i = 0;

        // insert n files (reports)
        foreach ($request->file() as $file) {


            if ($request->file('assessment_report_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();

                if (Str::startswith($report_type, 'commented'))
                    $name[0] = "Commented Full Assessment Report (" . $report_sequence . ")";
                else
                    $name[0] = "Full Assessment Report (" . $report_sequence . ")";
            }
            if ($request->file('assessment_report_smpc_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();

                if (Str::startswith($report_type, 'commented'))
                    $name[1] = "Commented Assessment Report SmPC (" . $report_sequence . ")";
                else
                    $name[1] = "Assessment Report SmPC (" . $report_sequence . ")";

            }
            if ($request->file('assessment_report_pils_file')) {
                $filename = time() . '_' . $file->getClientOriginalName();

                if (Str::startswith($report_type, 'commented'))
                    $name[2] = "Commented Assessment Report PILs (" . $report_sequence . ")";
                else
                    $name[2] = "Assessment Report PILs (" . $report_sequence . ")";

            }


            //todo change to storage disk
            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;


            $file->move($dir, $filename);
            $uploaded_document = new uploaded_documents;

            $uploaded_document->related_id = $dossier_assignment_id;
            $uploaded_document->name = $name[$i];
            $uploaded_document->path = $path;
            $uploaded_document->document_type = $document_type;
            $uploaded_document->description = $description;
            // insert records
            $saved = $uploaded_document->save();
            if (!$saved) {
                //$this->rollback_db('danger', 'ERROR 1: Problem with Insert into table: uploaded_documents. ');
                DB::rollBack();
                return Redirect()->back()->with('danger', 'ERROR 1: Problem with Insert into table: uploaded_documents. ');
            }
            $pdf_generated_uploaded_id = $uploaded_document->id;
            array_push($uploaded_document_ids, $pdf_generated_uploaded_id);

            $i++;
        }

        return array($uploaded_document_ids, $pdf_generated_uploaded_id); // end foreach
    }

    public static function get_evaluation_days_count($day_count_type='elapsed_days', $dossier_assignment_id){

        $dossier_evaluation = dossier_assignment::where('dossier_assignments.id', $dossier_assignment_id)
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            //->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            //->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->select(
                'dossier_assignments.id as doss_assign_id',
                'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_duration_days_actual as actual_completed_time'

            )
            ->first();

        $start_time = Carbon::create($dossier_evaluation->start_time);
        $end_time = Carbon::create($dossier_evaluation->end_time);
        $actual_completed_time = Carbon::create($dossier_evaluation->actual_completed_time);

        if($day_count_type == 'elapsed_days') {
            // elapsed days = now  - start time
            return $start_time->diffInDays(Carbon::now(), False);
        }elseif($day_count_type == 'remaining_days'){
            // remaining days = end time - now
        return Carbon::now()->diffInDays($end_time,False);
        }elseif($day_count_type == 'completed_in_days') {
            // completed_days = report submitted time  - start time
            return $start_time->diffInDays($actual_completed_time, False);
        }else{
            //do nothing

        }

    }


    // Yemane Extension  Variation


    public function query_deadline_extension(Request $request)
    {
        $query_id=$request->input('extension_query_id');
        $description=$request->input('extension_reason');
        $deadline=$request->input('extended_deadline');
        $query=queries::find($query_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($query->query_related_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Query Deadline Extension Request';
        $task_activity_title = 'Deadline Extension Request for Query Response';
        $assessor = User::find($query->query_from_user_id);
        $content_details = 'Dossier Query Response Extension was Requested by '.auth()->user()->first_name .' '.auth()->user()->middle_name .
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
        $new_notification['alert_level']='high';
        $new_notification['related_document']=  '';
        $new_notification['related_id'] = $query->query_related_id;
        $new_notification['remark']='';

        Notification::send($assessor, new InformationNotification($new_notification));
        event (new DossierAssignmentEvent($query->query_from_user_id, 'Query Response Extension was Requested by '.auth()->user()->first_name));

        return Redirect()->back()->with('success', 'Request for Query Response Extension Sent Successfully.');


    }




} // end class
