<?php

namespace App\Http\Controllers;


use App\Events\DossierAssignmentEvent;
use App\Exceptions\MainTaskNotInsertedException;
use App\Models\agents;
use App\Models\applications;
use App\Models\Attachment;
use App\Models\contacts;
use App\Models\DecisionParticipant;
use App\Models\MainTask;
use App\Models\Meeting;
use App\Models\TaskTracker;
use App\Models\template;
use App\Models\uploaded_documents;
use App\Models\uploaded_documnts;
use App\Models\User;
use App\Models\Variation;
use App\Models\VariationDecision;
use App\Models\VariationQuery;
use App\Notifications\InformationNotification;
use Carbon\Carbon;
use App\Models\dossier_assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf as PDFF;

class VariationController extends Controller
{
    private function get_main_task_id($variation, $related_type = 'Variation')
    {

        $main_task = MainTask::where('related_id', $variation)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {

            return 0; //means false
        }
    }

    private function date_formatter($date)
    {

        $formatted_date = date($date);
        $date = new \DateTime($formatted_date);
        $formatted_date = $date->format('Y-m-d');
        return ($formatted_date);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $acknowledgments = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->where('acknowledgment_document_id', null)
            ->where('variations.supervisor_id', auth()->user()->id)
            ->select('variations.*', 'certifications.registration_number', 'medicines.product_name', 'medicinal_products.product_trade_name',
                'applicant.first_name as applicant_first_name',
                'applicant.middle_name as applicant_middle_name', 'company_suppliers.trade_name as company_name')
            ->get();

        $unassigned_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->where('acknowledgment_document_id', '<>', null)
            ->where('variations.assessor_id', null)
            ->where('variations.supervisor_id', auth()->user()->id)
            ->select('variations.*', 'applications.application_id', 'applications.medical_product_id', 'certifications.registration_number',
                'medicines.product_name', 'medicinal_products.product_trade_name','applicant.first_name as applicant_first_name',
                'applicant.middle_name as applicant_middle_name', 'company_suppliers.trade_name as company_name')
            ->get();


        $assigned_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->where('main_tasks.related_task', 'Variation')
            ->where('variations.status', '<>', 'Decided')
            ->where('variations.status', '<>', 'Decision')
            ->where('acknowledgment_document_id', '<>', null)
            ->where('variations.assessor_id', '<>', null)
            ->where('variations.supervisor_id', auth()->user()->id)
            ->select('variations.*', 'users.first_name', 'users.last_name', 'certifications.certificate_number',
                'applications.application_id', 'applications.medical_product_id', 'certifications.registration_number',
                'medicines.product_name', 'medicinal_products.product_trade_name','applicant.first_name as applicant_first_name',
                'applicant.middle_name as applicant_middle_name', 'company_suppliers.trade_name as company_name',
                'main_tasks.task_status')
            ->get();


        $completed = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->where('variations.status', 'Decision')
            ->where('variations.supervisor_id', auth()->user()->id)
            ->select('variations.*', 'users.first_name', 'users.last_name',
                'certifications.certificate_number', 'applications.application_id', 'applications.medical_product_id',
                'certifications.registration_number', 'medicines.product_name', 'medicinal_products.product_trade_name',
                'applicant.first_name as applicant_first_name',
                'applicant.middle_name as applicant_middle_name', 'company_suppliers.trade_name as company_name')
            ->get();
        $decided = VariationDecision::join('variations', 'variations.id', 'variation_decisions.variation_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->where('variation_decisions.decision_status', '<>', 'null')
            ->select('variation_decisions.*', 'variations.variation_reference_number',
                'variations.assigned_datetime', 'users.first_name', 'users.middle_name', 'certifications.certificate_number',
                'medicinal_products.product_trade_name', 'company_suppliers.trade_name', 'certifications.registration_number',
                'medicines.product_name', 'applicant.first_name as applicant_first_name',
                'applicant.middle_name as applicant_middle_name', 'company_suppliers.trade_name as company_name')
            ->distinct('variation_decisions.id')
            ->get();
        // dd($decided);

        return view('variations.variation_tab', [
            'acknowledgments' => $acknowledgments,
            'unassigned_variations' => $unassigned_variations,
            'assigned_variations' => $assigned_variations,
            'completed' => $completed,
            'decided' => $decided
        ]);


    }

    public function ongoing_index()
    {

        $assigned_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            //->join('variation_decisions', 'variations.id', 'variation_decisions.variation_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->where('variations.assessor_id', auth()->user()->id)
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn("main_tasks.task_status", ["Inprogress", "pause"])
            ->where('variations.status', '!=', 'Decided')
                ->select('variations.*', 'users.first_name', 'users.middle_name',
                'certifications.registration_number', 'applications.application_id', 'applications.medical_product_id',
                    'medicines.product_name', 'medicinal_products.product_trade_name','company_suppliers.trade_name as company_name')
            ->get();


        $completed_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->leftjoin('variation_decisions', 'variations.id', 'variation_decisions.variation_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->where('variations.assessor_id', auth()->user()->id)
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn("main_tasks.task_status", ["Decision", "Completed"])
            ->select('variations.*',  'variation_decisions.decision_status', 'users.first_name', 'users.middle_name',
                'certifications.registration_number', 'applications.application_id', 'applications.medical_product_id',
                'medicines.product_name', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name as company_name')
            ->get();


        $breadcrumb_title = 'Variation Evaluations';
        return view(
            'variations.assessor_variation',
            [
                'evaluations' => $assigned_variations,
                'breadcrumb_title' => $breadcrumb_title,
                "completed_variations" => $completed_variations

            ]
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $issue_query_documents = VariationQuery::where('query_related_id', $id)->get();
        $variation = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->leftjoin('users as assessor', 'assessor.id', 'variations.assessor_id')
            ->join('users as supervisor', 'supervisor.id', 'variations.supervisor_id')
            ->where('variations.id', $id)
            ->select('variations.*',
                'assessor.first_name', 'assessor.last_name', 'supervisor.first_name as name',
                'dosage_forms.name as dosage_name', 'supervisor.last_name as m_name', 'supervisor.email as supervisor_email',
                'assessor.email as assessor_email', 'applications.company_supplier_id as company_id', 'applications.agent_id',
                'applications.id as app_id', 'applications.user_id as applicant_id',
                'applications.application_number', 'applications.application_type', 'medicines.product_name', 'certifications.registration_number')
            ->first();
        $main_task = $this->get_main_task_id($id);
        $application = applications::find($variation->app_id);
        $variation_document = uploaded_documents::find($variation->variation_document_id);
        $attachment = Attachment::where('uploaded_documents_id', $variation->variation_document_id)->first();;
        $company = db::table('company_suppliers')->where('id', $variation->company_id)->first();
        $agent = agents::find($variation->agent_id);

        $agent_contact_person = contacts::where('application_id', $application->application_id)
            ->where('contact_type', 'Agent')
            ->where('user_id', $agent->user_id)
            ->first();

        $current_user_id = auth()->user()->id;
        $assessment_report = uploaded_documents::find($variation->assessment_report_document_id);
        $issue_query_report_template = template::where('template_type', 35)
            ->leftjoin('document_types', 'document_types.id', 'templates.template_type')
            ->select('templates.*', 'document_types.document_type')
            ->first();
        $query_details = template::where('template_type', 16)
            ->select('templates.*')
            ->first();

        $main_task = $this->get_main_task_id($id);
        $tasks = TaskTracker::where('task_id', $main_task->id)
            ->OrderBy('task_trackers.id', 'desc')
            ->get();
        return view('variations.create', ['variation' => $variation,
                'issue_query_documents' => $issue_query_documents,
                'application' => $application,
                'variation_document' => $variation_document,
                'company' => $company,
                'agent' => $agent,
                'attachment' => $attachment,
                'main_task' => $main_task,
                'query_details' => $query_details,
                'current_user_id' => $current_user_id,
                'issue_query_report_template' => $issue_query_report_template,
                'tasks' => $tasks,
                'assessment_report' => $assessment_report,
                'agent_contact_person' => $agent_contact_person
            ]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function variation_applicant_index($id)
    {

        $certification = DB::table('certifications')->where('id', $id)->first();
        $application = DB::table('certifications')->where('certifications.id', $id)
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->select('applications.*')
            ->first();
        $variations = Variation::where('variations.certificate_id', $id)
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->leftjoin('variation_decisions', 'variation_decisions.variation_id', 'variations.id')
            ->select('variations.*', 'certifications.certificate_number', 'variation_decisions.decision_status',
                'variation_decisions.sealed_document_id')
            ->get();

        return view('variations.index', [
            'variations' => $variations,
            'application' => $application,
            'certification' => $certification

        ]);
    }

    public function new_variation(Request $request)
    {
        $certificate_id = $request->input('certificate_id');

        try {

            // query response attached in zip file
            $variation_document = $request->file('variation_document');
            $dir = 'documents/uploads';
            $variation_document_available = false;
            $variation_document_path = null;
            if ($variation_document != null) {
                $variation_document_filename = time() . '_' . $variation_document->getClientOriginalName();
                $variation_document_path = $dir . '/' . $variation_document_filename;

                $variation_document->move($dir, $variation_document_filename);
                $variation_document_available = true;
            }


            // Sealed Rejection Letter from the company
            $variation_cover_letter = $request->file('variation_cover_letter');
            $variation_cover_letter_filename = time() . '_' . $variation_cover_letter->getClientOriginalName();
            //todo: change dir to storage disk


            $variation_cover_letter_path = $dir . '/' . $variation_cover_letter_filename;

            // Upload files (copy files to destination)
            $variation_cover_letter->move($dir, $variation_cover_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($variation_document_path, $variation_cover_letter_path, $request) {
                $certificate_id = $request->input('certificate_id');
                $subject = $request->input('variation_subject');
                $uploaded_document = new uploaded_documents;
                $description = 'Variation Cover letter';

                $uploaded_document->related_id = $certificate_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Variatio Cover letter Sent from Applicant to Supervisor. ';
                $uploaded_document->path = $variation_cover_letter_path;
                $uploaded_document->document_type = 32; //TODO seed to document type 22 as variation Cover letter document
                $uploaded_document->description = $description;


                $uploaded_document->save();

                //Insert attachment to attachments table

                Attachment::insert([
                    'uploaded_documents_id' => $uploaded_document->id,
                    'path' => $variation_document_path,
                ]);


                $certification = DB::table('certifications')->where('certifications.id', $certificate_id)
                    ->join('decisions', 'decisions.id', 'certifications.decision_id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                    ->select('dossier_assignments.supervisor_id', 'dossier_assignments.application_id'
                        , 'certifications.registration_number', 'dossiers.dossier_ref_num')
                    ->first();
                $counter = Variation::where('certificate_id', $certificate_id)->count();
                $counter++;
                $variation_ref_num = $certification->dossier_ref_num . '/Var' . $counter;


                /// Generate Variation Reference number


                $variation = new Variation();
                $variation->certificate_id = $certificate_id;
                $variation->supervisor_id = $certification->supervisor_id;
                $variation->application_id = $certification->application_id;
                $variation->variation_reference_number = $variation_ref_num;
                $variation->attachments = 1;
                $variation->applicant_subject = $subject;
                $variation->variation_document_id = $uploaded_document->id;
                $variation->status = "Acknowledgment Not Sent";

                $variation->save();


                $variation_request_time = date('Y-m-d H:i:s');


                $task_name = 'Variation';
                $related_task = 'Variation';
                $related_id = $variation->id;
                $start_time = $variation_request_time;
                $end_time = $variation_request_time;
                $stopping_reason = '';
                $task_duration_days_actual = null;
                $is_active = 1;
                $is_complete = 0;
                $is_archived = 0;
                $task_status = 'Pending'; //TODO pending means waiting to be assigned to assessor
                $deadline = $variation_request_time;
                $deadline_extended_to = '';
                //notify before days
                $alert_before_days = 10;
                $duration_days = 15;

                $task_inserted = MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days, $start_time,
                    $end_time, $deadline, $task_status, $alert_before_days);

                if (!$task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting task details.
                    Your changes have not been updated to database.');
                }


                //update activity for timeline
                $main_task = $this->get_main_task_id($related_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Variation';
                $task_activity_title = 'New Variation';
                $user = User::find($certification->supervisor_id);
                $content_details = 'New Variation for the Product with Registration Number' . $certification->registration_number . ' has been sent for evaluation.';
                $route_link = '';
                $activity_status = 'Acknowledgment Not Sent';
                $uploaded_document_id = null;


                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'New Variation request for the Product with  ' . $certification->registration_number . ' Registration Number.';
                $new_notification['subject'] = 'New Variation';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['alert_level'] = 'high';
                $new_notification['related_id'] = $related_id;
                $new_notification['related_document'] = '';
                $new_notification['remark'] = '';


                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($user->id, 'New Variation request for the Product with  ' . $certification->registration_number . ' Registration Number.'));

            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', 'MainTaskNotInsertedException: ' . $e->getMessage());
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        return redirect('/Variation/index/' . $certificate_id)->with('success', 'Variation Document Uploaded Successfully.');
    }

    public function acknowledgment_details($id)
    {
        $variation = Variation::where('variations.id', $id)
            ->leftjoin('uploaded_documents as applicant_cover_letter', 'applicant_cover_letter.id', 'variations.variation_document_id')
            ->leftjoin('uploaded_documents as acknowledgment_letter', 'acknowledgment_letter.id', 'variations.acknowledgment_document_id')
            ->leftjoin('uploaded_documents as sealed_acknowledgment_letter', 'sealed_acknowledgment_letter.id', 'variations.sealed_acknowledgment_document_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->select('variations.*', 'decisions.id as decision_id', 'applicant_cover_letter.path as cover_letter_path'
                , 'acknowledgment_letter.path as acknowledgment_document', 'sealed_acknowledgment_letter.path as sealed_acknowledgment_document_path')
            ->first();

        $document = Attachment::where('uploaded_documents_id', $variation->variation_document_id)->first();
        return view('variations.acknowledgment_details', ['variation' => $variation, 'attachment' => $document]);
    }


    public function download_acknowledgment_letter(Request $request)
    {
        try {
            $variation_id = $request->input('variation_id');
            $variation = Variation::where('id', $variation_id)->first();
            $deadline = null;
            $upload_date = date('Y_m_s_H_m_s');
            $dir = 'documents/uploads/';
            $file_name = 'acknowledgment.pdf';
            $uploaded_file_name = $upload_date . $file_name;
            $data = $request->input('data');


            $document = new PDFF([
                    'mode' => "utf-8",
                    'format' => "A4",
                    'margin_header' => "1",
                    'margin_top' => "30",
                    'margin_bottom' => "15",
                    'margin_footer' => "2",
                ]
            );

            $header = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

            $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
            $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');


            $document->WriteHTML($data);


            Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));
            // return Storage::disk('documents')->download($uploaded_file_name,'Request',$header);


            $path = $dir . $uploaded_file_name;


            $uploaded_document = new uploaded_documents();
            $uploaded_document->related_id = $variation_id;
            $uploaded_document->name = $file_name;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 33; //Downloaded decision document id
            $uploaded_document->description = 'Decision Document';
            // insert records
            $saved = $uploaded_document->save();


            //update variations and add to task trackers
            $variation = Variation::where('id', $variation_id)->update(
                [
                    'acknowledgment_document_id' => $uploaded_document->id,
                ]
            );


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Download. ' . $e->getMessage());

        }

        // $request->session()->flash('download.request');
        // return response()->download($path);

        return redirect('Variation/Acknowledgment/' . $variation_id)->with('success', 'Acknowledgment Letter Saved Successfully.');

    }


    public function send_variation_acknowledgment(Request $request)
    {
        $variation_id = $request->input('variation_id');

        try {

            // query response attached in zip file

            $dir = 'documents/uploads';


            // Sealed Rejection Letter from the company
            $sealed_acknowledgment_letter = $request->file('sealed_acknowledgment_letter');
            $sealed_acknowledgment_letter_filename = time() . '_' . $sealed_acknowledgment_letter->getClientOriginalName();
            //todo: change dir to storage disk


            $sealed_acknowledgment_letter_path = $dir . '/' . $sealed_acknowledgment_letter_filename;

            // Upload files (copy files to destination)
            $sealed_acknowledgment_letter->move($dir, $sealed_acknowledgment_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ERROR 1' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($sealed_acknowledgment_letter_path, $request) {

                $variation_id = $request->input('variation_id');
                $supervisor_subject = $request->input('supervisor_subject');

                $variation = Variation::find($variation_id);
                $application = applications::find($variation->application_id);


                $description = 'Variation Acknowledgment letter to applicant ';
                $uploaded_document = new uploaded_documents();

                $uploaded_document->related_id = $variation_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Variation Acknowledgment Letter sent to Applicant. ';
                $uploaded_document->path = $sealed_acknowledgment_letter_path;
                $uploaded_document->document_type = 33;
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $sealed_acknowledgment_letter_id = $uploaded_document->id;


                Variation::where('id', $variation_id)->update(
                    [

                        'sealed_acknowledgment_document_id' => $sealed_acknowledgment_letter_id,
                        'supervisor_subject' => $supervisor_subject,
                        'status' => 'Unassigned'
                    ]
                );

                MainTask::where('related_id', $variation_id)
                    ->where('related_task', 'Variation')
                    ->update([
                        'task_status' => 'Unassigned'
                    ]);

                //time line and notification

                //update activity for timeline
                $main_task = $this->get_main_task_id($variation_id);
                $end_time = null;
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Variation Evaluation';
                $task_activity_title = 'Variation Acknowledgement Letter';
                $user = User::find($application->user_id);
                $content_details = 'Acknowledgment Letter of New Variation has been Sent to You.';
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

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $content_details;
                $new_notification['subject'] = $request->supervisor_subject;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['alert_level'] = null;
                $new_notification['related_id'] = $variation_id;
                $new_notification['related_document'] = '';
                $new_notification['remark'] = '';

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($user->id, $task_activity_title));


            }); // end transaction

            return Redirect()->back()->with('success', 'Variation Acknowledgment Letter Successfully Sent to Applicant.');

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ERROR 2: ' . $e->getMessage());

        }

    }


    public function retrieve_assessor_assignments(Request $request)
    {

        try {

            $id = $request->id;


            $return_data = "";

            $assessor_check = dossier_assignment::where('assessor_id', $id)
                ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->whereIn("main_tasks.task_status",["Inprogress","pause"])
                ->get();
            if (count($assessor_check) > 0) {
                $assessor_assignment_details = dossier_assignment::where('assessor_id', $id)
                    ->join('users', 'users.id', 'dossier_assignments.assessor_id')
                    ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                    ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                    ->join('applications','applications.id','dossier_assignments.application_id')
                    ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                    ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                    ->where('main_tasks.related_task', 'Dossier Evaluation')
                    ->whereIn("main_tasks.task_status",["Inprogress","pause"])
                    ->whereIn('dossiers.assignment_status', [2, 3])  // inprogres, pause
                    ->select('dossier_assignments.*', 'dossiers.dossier_ref_num', 'medicines.product_name')
                    ->get();
                $i = 1;

//               dd($assessor_assignment_details);

                foreach ($assessor_assignment_details as $assignment) {
                    $main_task = MainTask::where('related_id', $assignment->id)
                        ->where('related_task', 'Dossier Evaluation')
                        ->first();
                    $application = applications::find($assignment->application_id);

                    //$return_data .= "<tr><td>" . $i++ . "</td>";
                    $return_data .= "<td>" . $assignment->dossier_ref_num . "</td>";
                    $return_data .= "<td> Dossier Evaluation</td>";
                    $return_data .= "<td>" . $assignment->product_name . "</td>";
                    $return_data .= "<td>" . $main_task->end_time . "</td>";
                    $return_data .= "<td> <div class='progress'>
                        <div class='progress-bar bg-gradient-green progress-bar-striped'
                            role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100'
                            style='width:" . $application->progress_percentage . "%'>
                            <span >" . $application->progress_percentage . "% Complete </span>
                        </div>

                    </div></td>";
                    if($main_task->task_status == 'Inprogress'){
                        $return_data .= "<td><span class='badge badge-primary'>In-progress</span></td>";
                    }elseif($main_task->task_status == 'pause'){
                        $return_data .= "<td><span class='badge badge-warning'>Paused</span></td>";
                    }else {
                        $return_data .= "<td><span class='badge badge-secondary'>" . $main_task->task_status . "</span></td>";
                    }

                    $return_data .= "<td><a href='/dossier_evaluation/edit/".$assignment->id."'  class='btn btn-info btn-sm'  ><i class='fas fa-list'></i></a></td></tr> ";
                }


            }


            $assessor_check = Variation::where('assessor_id', $id)
                ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
                ->where('main_tasks.related_task', 'Variation')
                ->whereIn("main_tasks.task_status",["Inprogress","Pending","inprogress", 'pause'])
                ->get();

            if (count($assessor_check) > 0) {
                $assessor_assignment_details = Variation::where('variations.assessor_id', $id)
                    ->join('users', 'users.id', 'variations.assessor_id')
                    ->join('certifications','certifications.id','variations.certificate_id')
                    ->join('decisions','decisions.id','certifications.decision_id')
                    ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
                    ->join('applications','applications.id','dossier_assignments.application_id')
                    ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                    ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                    ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                    ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
                    ->where('main_tasks.related_task', 'Variation')
                    ->whereIn("main_tasks.task_status",["Inprogress","Pending","inprogress", 'pause'])
                    ->select('variations.*','medicines.product_name')
                    ->get();
                $i = 1;

                // dd($assessor_assignment_details);

                foreach ($assessor_assignment_details as $assignment) {
                    $main_task = MainTask::where('related_id', $assignment->id)
                        ->where('related_task', 'Variation')
                        ->first();
                    //$return_data .= "<tr><td>" . $i++ . "</td>";
                    $return_data .= "<td>" . $assignment->variation_reference_number . "</td>";
                    $return_data .= "<td> Variation Assessment</td>";
                    $return_data .= "<td>" . $assignment->product_name . "</td>";
                    $return_data .= "<td>" . $main_task->end_time . "</td>";
                    $return_data .= "<td> <span class='badge bg-success badge-btn font-weight-bold'>In Variation Evaluation</span></td>";

                    if($assignment->status  == 'Inprogress'){
                        $return_data .= "<td><span class='badge badge-primary'>In-progress</span></td>";
                    }elseif($assignment->status  == 'pause'){
                        $return_data .= "<td><span class='badge badge-warning'>Paused</span></td>";
                    }else {
                        $return_data .= "<td><span class='badge badge-secondary'>" . $assignment->status  . "</span></td>";
                    }

                    $return_data .= "<td><a href='/variation_evaluation/edit/" . $assignment->id . "' class='btn btn-info btn-sm'  ><i class='fas fa-list'></i></a></td></tr> ";
                }

                //TODO - retrieve PSUR TASKS Here


                return response()->json(['assignment' => $return_data, 'assessor_name' => $assessor_assignment_details[0]->first_name . ' ' . $assessor_assignment_details[0]->middle_name]);
            } else {
                $return_data .= "";
                return response()->json(['assignment' => $return_data, 'assessor_name' => '']);
            }
        } catch (\Exception $e) {
            return response()->json(['assignment' => $e, 'data' => "", 'item' => 'error' . $e]);
        }
        return response()->json(['assignment' => 'generic', 'item' => 'item_success']);
    }

    public function assign_variation_index($id)
    {


        $variation = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->where('variations.id', $id)
            ->select('variations.*', 'applications.application_number', 'applications.application_type', 'medicines.product_name')
            ->first();


//        dd($dossier);
        //
        $assessors = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Assessor')
            ->get();
        $breadcrumb_title = 'Assign Variation to Assessor';

        return view('variations.assign', ['variation' => $variation, 'assessors' => $assessors, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function assign_variation(Request $request)
    {


        // todo $assessor must be required
        try {
            DB::transaction(function () use ($request) {

                $assessor_id = $request->input('assessor');
                $variation_id = $request->input('variation_id');
                $deadline = $request->input('deadline');

                // session user id(super visor id)


                $assigned_datetime = date('Y-m-d H:i:s');
                $end_datetime = $assigned_datetime;

                $variation = Variation::where('id', $variation_id)->first();

                //60 is going to be changed to 60


                Variation::where('id', $variation_id)->update([
                    'assessor_id' => $assessor_id,
                    'assigned_datetime' => $assigned_datetime,
                    'deadline' => $deadline,
                    'status' => 'Inprogress',

                ]);

                MainTask::where('related_id', $variation_id)
                    ->where('related_task', 'Variation')
                    ->update([
                        'task_status' => 'Inprogress'
                    ]);


                //update activity for timeline
                $main_task = $this->get_main_task_id($variation_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Variation Evaluation';
                $task_activity_title = 'New Variation Assignment';
                $user = User::find($assessor_id);
                $content_details = 'New Variation Document Assigned to ' . $user->first_name . ' ' . $user->middle_name . ' for evaluation.';
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

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'New Variation Assigment. Variation Ref. Num: ' . $variation->variation_reference_number;
                $new_notification['subject'] = 'New Variation Assignment';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['alert_level'] = 'high';
                $new_notification['related_id'] = $variation_id;
                $new_notification['related_document'] = '';
                $new_notification['remark'] = '';

                $user = User::find($assessor_id);

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor_id, 'New Variation Assignment. Ref. Num: ' . $variation->variation_reference_number));

            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', 'MainTaskNotInsertedException: ' . $e->getMessage());
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        return redirect('/Variation/index/')->with('success', 'Variation Document Assigned Successfully.');
        //return Redirect()->back()->with('success', 'Dossier Assigned Successfully.');
    }


    public function variation_template($id, $variation_id)
    {

        $template = DB::table('templates')->where('id', $id)->first();

        $breadcrumb_title = $template->name;

        $variation = Variation::where('variations.id', $variation_id)
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->leftjoin('checklists', 'checklists.application_id', 'applications.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->leftjoin('contacts', 'applications.application_id', 'contacts.application_id')
            ->where('contacts.contact_type', 'Supplier')
            ->select(
                'variations.*',
                'variations.id as variation_id',
                'dossier_assignments.id as dossier_ass_id',
                'dossier_assignments.assigned_datetime',
                'dossiers.dossier_ref_num',
                'dossier_assignments.dossier_id',
                'dossiers.assignment_status',
                'assessors.first_name',
                'assessors.middle_name',
                'supervisors.first_name as name',
                'supervisors.middle_name as m_name',
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
                'contacts.first_name as applicant_first_name',
                'contacts.last_name  as applicant_last_name',
                'route_administrations.name as route_administration_name',
                'dosage_forms.name as dosage_form_name',
                'checklists.sample_received_date',
                'applications.created_at',
                'medicinal_products.product_trade_name as brand_name',
                'medicines.product_name as product_trade_name'
            )
            ->first();
        //this is for QC
        $users = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Inspection')
            ->get();


        $letterReferenceNumber = new letterReferenceNumberGenerator();
        $reference_letter = $letterReferenceNumber->generate_letter_reference_number();


        $date = date('d/m/Y');
        return view($template->path, ['breadcrumb_title' => $breadcrumb_title, 'reference_letter' => $reference_letter,
            'users' => $users, 'variation' => $variation, 'date' => $date, 'template' => $template]);


    }


    public function send_variation_query_issue(Request $request)
    {


        try {

            $upload_date = date('Y-m-s-H-m-s ');
            $dir = 'documents/uploads/';
            $file_name = 'Variation_Query_letter.pdf';
            $uploaded_file_name = $upload_date . $file_name;


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

        $variation_id = $request->input('variation_id');

        try {
            // handle transactions automatically
            DB::transaction(function () use ($query_path, $request) {

                $variation_id = $request->input('variation_id');
                $variation = Variation::find($variation_id);
                $application = applications::find($variation->application_id);

                //this code below is for checklist in the progress tab


                //upload the document
                // //first make the html and save it as pdf
                // //

                $uploaded_document = new uploaded_documents;
                $description = $request->input('query_subject');

                $uploaded_document->related_id = $variation_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Query Issued ';
                $uploaded_document->path = $query_path;
                $uploaded_document->document_type = 35; //TODO fetch from document_type
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
                $query_related_id = $variation_id;
                //insert into query
                $query = new VariationQuery();


                $query->query_from_user_id = $sender; //Generated name
                $query->query_to_user_id = $receiver; //to applicant
                $query->query_sent_date = $query_sent_time; //current date
                $query->status = $query_type;
                $query->query_deadline = $query_deadline; //pdf
                $query->query_related_id = $query_related_id; //duration or expire date
                $query->sent_document_id = $query_document_id; //uploaded document id
                $query->request_subject = $query_subject; //document id
                $query->save();
                //get main task id
                $main_task = $this->get_main_task_id($variation_id, 'Variation');
                // Main task is paused
                //

                MainTask::where('id', $main_task->id)
                    ->update([
                        'task_status' => 'pause',
                        'stopping_reason' => 'Query Issued',

                    ]);

                //get the end time from the assessor
                $end_time = null;
                $task_category = 'Query';
                $task_activity_title = 'Query Issued';
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
                $new_notification['data'] = 'You have query regarding  Evaluation of Variation.';
                $new_notification['subject'] = $query_subject;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $pdf_generated_uploaded_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $variation_id;
                $new_notification['remark'] = '';
                $user = User::find($receiver);
                $supervisor = User::find($variation->supervisor_id);

                Notification::send($user, new InformationNotification($new_notification));
                Notification::send($supervisor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($receiver, 'You have query regarding Evaluation of Variation.  '));
                event(new DossierAssignmentEvent($variation->supervisor_id, 'Query regarding Evaluation of Variation with Ref.  Num' . $variation->variation_reference_number . ' has been sent to Applicant by ' . auth()->user()->first_name));
            }); // end transaction
        } catch (QueryNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }


        return Redirect('/variation_evaluation/edit/' . $variation_id)->with('success', 'Query Issued To Applicant Successfully.');


    }

    public function send_variation_assessment(Request $request)
    {
        try {


            //insert details to uploaded_document

            $file = $request->file('assessment_report');
            $filename = time() . '_' . $file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;
            $path = $file->move($dir, $filename);

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }
        $variation_id = $request->input('variation_id');

        try {
            // update progress by 20% (both FT and ST)
            DB::transaction(function () use ($request, $path) {
                $variation_id = $request->input('variation_id');
                $variation = Variation::find($variation_id);
                $application = applications::find($variation->application_id);

                $description = $request->input('assessment_subject');
                $uploaded_document = new uploaded_documents;
                $uploaded_document->related_id = $variation_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Assessment Report ';
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 35; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                Variation::where('id', $variation_id)->update([
                    'assessment_report_document_id' => $uploaded_document->id,
                    'status' => 'Decision'
                ]);

                $end_time = date('Y-m-d H:i:s');
                $main_task = $this->get_main_task_id($variation_id);
                //update main task
                MainTask::where('id', $main_task->id)->update([
                    'is_complete' => 1,
                    'task_status' => 'completed',
                    'end_time' => $end_time,
                    'task_duration_days_actual' => $end_time
                ]);
                //get the end time from the assessor

                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Assessment Report';
                $task_activity_title = 'Variation Evaluation Completed';
                $content_details = 'Variation Evaluation of ' . $variation->variation_reference_number . ' Completed.';
                $route_link = '';
                $activity_status = 'completed';
                $uploaded_document_id = null;
                //insert this into task tracker

                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Cannot insert activity details. Your Changes have not been updated. ');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Variation Assessment of ' . $variation->variation_reference_number . ' Completed.';
                $new_notification['subject'] = 'Variation Assessment Completed';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document->id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $variation_id;
                $new_notification['remark'] = '';
                // ::send($users, new ($invoice));
                $user = User::find($variation->supervisor_id);
                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($user->id, 'Assessment report has been submitted successfully'));
            });
        } catch (QueryNotInsertedException $e) {
            return Redirect()->back()->with('danger', $e->getMessage());

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }


        return Redirect('/variation_evaluation/edit/' . $variation_id)->with('success', 'Assessment report has been submitted successfully.');


    }

    public function upload_variation_query_response(Request $request)
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
                $variation_id = $request->input('hidden_variation_id');
                $uploaded_document = new uploaded_documents;

                $uploaded_document->related_id = $variation_id;
                //$uploaded_document->ref_num = $request->ref_num;
                $uploaded_document->name = 'Variation Query Response Cover Letter';
                $uploaded_document->path = $cover_path;
                $uploaded_document->document_type = 35; //TODO fetch from document_type
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
                // dd($request->input('hidden_query_id'));

                VariationQuery::where('id', $request->input('hidden_query_id'))
                    ->update([
                        'status' => $status,
                        'query_received_date' => $query_received_date,
                        'received_document_id' => $uploaded_document_id,
                        'attachments_available' => 1,
                        'response_description' => $response_description,

                    ]);


                //update activity for timeline

                $main_task = $this->get_main_task_id($variation_id);

                MainTask::where('id', $main_task->id)
                    ->update([
                        'task_status' => 'Inprogress',
                    ]);

                $issued_datetime = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $task_category = 'Query';
                $task_activity_title = 'Query Response';
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

                $variation = Variation::where('id', $variation_id)->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $response_description;
                $new_notification['subject'] = 'Query Response';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $uploaded_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $variation->id;
                $new_notification['remark'] = '';

                $assessor = User::find($variation->assessor_id);
                Notification::send($assessor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor->id, 'Variation Query Response has been uploaded.'));


            }); // end transaction


        } catch (\Exception $e) {

            return Redirect()->back()->with(['danger' => 'Problem with File Upload. ' . $e->getMessage()]);
        }
        return Redirect()->back()->with('success', 'Files Uploaded Successfully.');

    }

    public function edit_variation_query_response(Request $request)
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
                $variation_id = $request->input('variation_id');

                // update uploaded documents details
                $query_id = $request->input('hidden_query_id1');
                // dd($variation_id,$query_id);
                $query = VariationQuery::find($query_id);
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
                VariationQuery::where('id', $query_id)->update(
                    [
                        'response_description' => $response_description,
                        'query_received_date' => $query_received_date,
                    ]
                );

                //update activity details
                $main_task = $this->get_main_task_id($variation_id);
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

                $variation = Variation::where('id', $variation_id)->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $response_description;
                $new_notification['subject'] = 'Query Response';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $query->received_document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $variation_id;
                $new_notification['remark'] = '';

                $assessor = User::find($variation->assessor_id);
                Notification::send($assessor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor->id, 'Updated Query Response has been uploaded.'));

            }); // end transaction

        } catch (\Exception $e) {

            return Redirect()->back()->with(['danger' => 'Problem Updating Query Response' . $e->getMessage()]);
        }
        return Redirect()->back()->with('success', 'Response Updated Successfully.');


    }

    public function variation_decision_details($id)
    {

        $decided = VariationDecision::join('variations', 'variations.id', 'variation_decisions.variation_id')
            ->join('meetings', 'meetings.id', 'variation_decisions.meeting_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->leftjoin('uploaded_documents as minutes_document', 'minutes_document.id', 'meetings.minutes_id')
            ->leftjoin('uploaded_documents as downloaded_document', 'downloaded_document.id', 'variation_decisions.downloaded_document_id')
            ->leftjoin('uploaded_documents as sealed_document', 'sealed_document.id', 'variation_decisions.sealed_document_id')
            ->leftjoin('uploaded_documents as appeal_letter', 'appeal_letter.id', 'variation_decisions.appeal_letter_id')
            ->where('variation_decisions.id', $id)
            ->select('variation_decisions.*', 'variations.variation_reference_number', 'variations.assigned_datetime',
                'meetings.meeting_date', 'meetings.venue', 'meetings.time', 'meetings.description', 'minutes_document.path as minute_path',
                'users.first_name', 'users.middle_name', 'certifications.certificate_number', 'downloaded_document.path as downloaded_document_path',
                'sealed_document.path as sealed_document_path', 'appeal_letter.path as appeal_letter_path', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name',)
            ->first();

        $attachment = Attachment::where('uploaded_documents_id', $decided->sealed_document_id)->first();
        $participants = DB::table('decision_participants')->join('users', 'users.id', 'decision_participants.committee_id')
            ->where('meeting_id', $decided->meeting_id)
            ->get();

        $letterReferenceNumber = new letterReferenceNumberGenerator();
        $reference_letter = $letterReferenceNumber->generate_letter_reference_number();

        return view('variations.decision_details', ['decision' => $decided, 'reference_letter' => $reference_letter, 'participants' => $participants, 'attachment' => $attachment]);
    }


    public function download_decision_letter(Request $request)
    {

        $variation_id = $request->input('variation_decision_id');

        try {
            $variation_id = $request->input('variation_decision_id');
            $decision = VariationDecision::where('variation_id', $variation_id)->first();
            $deadline = null;
            $upload_date = date('Y-m-s-H-m-s');
            $dir = 'documents/uploads/';
            $file_name = 'variation_decision_letter.pdf';
            $uploaded_file_name = $upload_date . $file_name;
            $data = $request->input('data');


            $document = new PDFF([
                    'mode' => "utf-8",
                    'format' => "A4",
                    'margin_header' => "1",
                    'margin_top' => "30",
                    'margin_bottom' => "15",
                    'margin_footer' => "2",
                ]
            );

            $header = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

            $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
            $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');


            $document->WriteHTML($data);


            Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));


            $path = $dir . $uploaded_file_name;


            $uploaded_document = new uploaded_documents();
            $uploaded_document->related_id = $variation_id;
            $uploaded_document->name = $file_name;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 25; //Downloaded decision document id
            $uploaded_document->description = 'Decision Document';
            // insert records
            $saved = $uploaded_document->save();


            VariationDecision::where('variation_id', $variation_id)->update(
                [
                    'downloaded_document_id' => $uploaded_document->id,
                    'decision_letter_downloaded' => 1,
                    'downoloded_date' => now()
                ]);


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Download. ' . $e->getMessage());

        }

        // $request->session()->flash('download.request');
        // return response()->download($path);

        return redirect('/variation_evaluation/variation_decision_details/' . $decision->id)->with('success', 'Decision Letter Saved Successfully.');

    }

    public function send_variation_decision(Request $request)
    {

        try {
            // query response attached in zip file
            $attachment = $request->file('attachment');
            $dir = 'documents/uploads';
            $attachment_available = false;
            $attach_path = null;
            if ($attachment != null) {
                $attachment_filename = time() . '_' . $attachment->getClientOriginalName();
                $attach_path = $dir . '/' . $attachment_filename;

                $attachment->move($dir, $attachment_filename);
                $attachment_available = true;
            }


            // Sealed Rejection Letter from the company
            $decision_letter_file = $request->file('decision_letter');
            $decision_letter_filename = time() . '_' . $decision_letter_file->getClientOriginalName();
            //todo: change dir to storage disk


            $decision_letter_path = $dir . '/' . $decision_letter_filename;

            // Upload files (copy files to destination)
            $decision_letter_file->move($dir, $decision_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $decision_letter_path, $request, $attachment_available) {
                $variation_decision_id = $request->input('variation_decision_id');
                $uploaded_document = new uploaded_documents;
                $description = 'Variation Decision letter to applicant and agent';

                $uploaded_document->related_id = $variation_decision_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Application Decision Letter sent to Applicant and Agent ';
                $uploaded_document->path = $decision_letter_path;
                $uploaded_document->document_type = 36; //TODO seed to document type 36 as variation decision letter
                $uploaded_document->description = $description;


                $uploaded_document->save();
                $uploaded_document_id = $uploaded_document->id;

                //Insert attachment to attachments table
                if ($attachment_available) {
                    Attachment::insert([
                        'uploaded_documents_id' => $uploaded_document->id,
                        'path' => $attach_path,
                    ]);

                }

                $variation_decision_id = $request->input('variation_decision_id');


                VariationDecision::where('id', $variation_decision_id)->update(
                    [
                        'sealed_document_id' => $uploaded_document->id,
                        'appeal' => true,
                        'attachments' => $attachment_available
                    ]
                );
                //todo Lock the application after two months of rejection (if appeal is not received)

                // update activity timeline

                $variation_decision = VariationDecision::where('id', $variation_decision_id)->first();

                $decision_status = $variation_decision->decision_status;
                $sent_request = null;
                // add registration decision details to activity timeline
                $this->add_timeline($variation_decision->variation_id, $uploaded_document_id, $decision_status, $sent_request);

                // send notification to applicant
                $this->send_notification($variation_decision->variation_id, $uploaded_document_id, $decision_status);


            }); // end transaction

            return Redirect()->back()->with('success', 'Variation Decision Letter Successfully Sent to Applicant.');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

    }

    private function add_timeline($variation_id, $uploaded_document_id, $decision_status, $sent_request)
    {

        $variation_decision = VariationDecision::where('variation_id', $variation_id)->first();

        $variation = Variation::find($variation_id);
        $main_task = $this->get_main_task_id($variation->id, 'Variation');
        $meeting = Meeting::where('id', $variation_decision->meeting_id)->first();

        try {
            if ($meeting->postponed_date != null)
                $decision_date = $meeting->postponed_date;
            else
                $decision_date = $meeting->meeting_date;
            $decision_date = Carbon::create($decision_date);

        } catch (\Execution $e) {

            return Redirect()->back()->with('danger', 'Problem with decision date. ERROR 3' . $e->getMessage());
        }

        // if there is $request from view, get subject and body from it
        // else construct the subject and body in get_decision_message()
        if ($sent_request != null) {
            $subject = $sent_request->subject;
            $description = $sent_request->body;
        } else {
            list($subject, $description, $application_details_) = $this->get_decision_message($decision_status, $variation_id);
        }
        // get notification details to show in timeline

        $task_category = 'Decision';
        $task_activity_title = $subject;
        $content_details = $description;
        $route_link = '';
        $activity_status = 'Variation Decision';

        //insert this into task tracker
        $main_task_inserted = MainTaskController::insertActivity($main_task->id, $decision_date, null,
            $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated to database.');
        }

    }

    private function send_notification($variation_id, $uploaded_document_id, $decision_status)
    {
        // get decision message to be sent along with notification
        list($subject, $description, $application_details) = $this->get_decision_message($decision_status, $variation_id);

        $new_notification = [];
        $new_notification['type'] = 'Notification';
        $new_notification['data'] = $description;
        $new_notification['subject'] = $subject;
        $new_notification['alert_level'] = '';
        $new_notification['related_document'] = $uploaded_document_id;
        $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
        $new_notification['related_id'] = $application_details->variation_id;
        $new_notification['remark'] = '';
        $applicant = User::find($application_details->applicant_id);

        Notification::send($applicant, new InformationNotification($new_notification));
        event(new DossierAssignmentEvent($applicant->id, $subject));

    }

    /**
     * @param $decision_status
     * @param $decision_id
     * @return array
     */
    private function get_decision_message($decision_status, $variation_id)
    {

        // set notification description based on the decision status
        $variation_decision = VariationDecision::where('variation_id', $variation_id)->first();

        // get applicant id, doss. assign. id, product name
        $application_details = VariationDecision::join('variations', 'variation_decisions.variation_id', 'variations.id')
            ->join('meetings', 'meetings.id', 'variation_decisions.meeting_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'applications.user_id')
            ->where('variations.id', $variation_id)
            ->select('users.id as applicant_id', 'variations.id as variation_id', 'medicinal_products.product_trade_name')
            ->first();

        $subject = 'Decision for Variation of ' . $application_details->product_trade_name;
        $description = "";
        if ($decision_status == 'Accepted') {
            $description = 'Congratulations! The Application has been ' . $decision_status . '.';

        } elseif ($decision_status == 'Rejected') {
            $description = 'We regret to inform you that, the Variation has NOT been accepted. 
                            Please refer to the following instructions if you want to appeal';

        } elseif ($decision_status == 'Appeal_status') {

            $subject = 'Variation Appeal Decision.';
            $description = 'Variation Appeal Decision has been sent for product: ' . $application_details->product_trade_name . '.';
        } else {
            //do nothing
        }
        return array($subject, $description, $application_details);
    }

    // this works for both appeal reject and accept despite its name
    public function appeal_reject(Request $request)
    {


        try {

            $appeal_accepted = $request->input('appeal');//0 for reject 1 for accepted

            if ($appeal_accepted == 1) {
                $file = $request->file('accepted_document');
            } elseif ($appeal_accepted == 0) {
                $file = $request->file('rejected_document');
            }
            $decision_id = $request->input('variation_decision_id');


            $filename = time() . '_' . $file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;

            $file->move($dir, $filename);
            $uploaded_document = new uploaded_documents;
            $uploaded_document->related_id = $decision_id;
            $uploaded_document->name = $filename;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 24; //appeal document id
            $uploaded_document->description = 'Appeal Document';
            // insert records
            $saved = $uploaded_document->save();
            if (!$saved) {
                $this->rollback_db('danger', 'ERROR 1: Problem with Insert into table: uploaded_documents. ');
            }
            $pdf_generated_uploaded_id = $uploaded_document->id;

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }
        try {
            // handle transactions automatically
            DB::transaction(function () use ($pdf_generated_uploaded_id, $path, $request, $decision_id, $appeal_accepted) {
                $decision = VariationDecision::find($decision_id);
                $variation = Variation::where('id', $decision->variation_id)->first();
                if ($appeal_accepted == 1) {
                    VariationDecision::where('id', $decision_id)->update([
                        'appeal' => 1,
                        'appeal_letter_id' => $pdf_generated_uploaded_id,
                        'appeal_status' => 'Accepted',
                        'decision_status' => 'Accepted',
                        'appeal_decision_date' => now()
                    ]);
                    // DB::table('dossiers')->where('id', $dossier_ass->dossier_id)->update(
                    //     [
                    //         'assignment_status' => 8
                    //     ]
                    // );
                    // //lock previous dossier assignment


                } else {
                    VariationDecision::where('id', $decision_id)->update([
                        'appeal' => 1,
                        'appeal_letter_id' => $pdf_generated_uploaded_id,
                        'appeal_status' => 'Rejected',
                        'appeal_decision_date' => now()
                    ]);
                    //lock the product
                }

                $this->add_timeline($decision->variation_id, $pdf_generated_uploaded_id, 'Appeal_status', $request);
                $this->send_notification($decision->variation_id, $pdf_generated_uploaded_id, 'Appeal_status');


            });
            return Redirect()->back()->with('success', 'Variation Appeal Document Successfully Uploaded.');

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect()->back()->with('success', 'Meeting Data Inserted Successfully.');


    }

    public function variation_applicant_details($id)
    {


        $variation = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->leftjoin('users as assessor', 'assessor.id', 'variations.assessor_id')
            ->leftjoin('variation_decisions', 'variation_decisions.variation_id', 'variations.id')
            ->join('users as supervisor', 'supervisor.id', 'variations.supervisor_id')
            ->leftjoin('uploaded_documents as acknowledgment_letter', 'acknowledgment_letter.id', 'variations.sealed_acknowledgment_document_id')
            ->leftjoin('uploaded_documents as decision_letter', 'decision_letter.id', 'variation_decisions.sealed_document_id')
            ->leftjoin('uploaded_documents as appeal_letter', 'appeal_letter.id', 'variation_decisions.appeal_letter_id')
            ->where('variations.id', $id)
            ->select('variations.*', 'decisions.id as decision_id',
                'assessor.first_name', 'assessor.last_name', 'supervisor.first_name as name',
                'dosage_forms.name as dosage_name', 'supervisor.last_name as m_name', 'supervisor.email as supervisor_email',
                'assessor.email as assessor_email', 'company_suppliers.trade_name as company_name', 'applications.agent_id',
                'applications.id as app_id', 'applications.user_id as applicant_id',
                'applications.application_number', 'applications.application_type', 'medicinal_products.product_trade_name',
                'certifications.certified_date', 'certifications.expiry_date', 'certifications.registration_number', 'acknowledgment_letter.path as sealed_acknowledgment_document_path',
                'variation_decisions.decision_status', 'decision_letter.path as decision_letter_path', 'decision_letter.id as decision_letter_id', 'variation_decisions.appeal_status',
                'variation_decisions.attachments as variation_decision_attachment_available', 'variation_decisions.appeal_letter_id', 'appeal_letter.path as appeal_letter_path', 'variation_decisions.appeal_letter_id'
            )
            ->first();
        $attachment = null;
        if ($variation->variation_decision_attachment_available == 1) {
            $attachment = Attachment::where('uploaded_documents_id', $variation->decision_letter_id)->first();

        }
        return view('variations.applicant_details', ['variation' => $variation, 'attachment' => $attachment]);


    }




    // Yemane Extension


    public function variation_query_deadline_extension(Request $request)
    {
        $query_id=$request->input('extension_variation_query_id');
        $description=$request->input('extension_reason');
        $deadline=$request->input('extended_deadline');
        $query=VariationQuery::find($query_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($query->query_related_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Deadline Extension Request';
        $task_activity_title = 'Deadline Extension Request for Variation Query Response';
        $assessor = User::find($query->query_from_user_id); //assessor who assigned the section
        $content_details = 'Variation Query Response Extension was Requested by '.auth()->user()->first_name .' '.auth()->user()->middle_name .
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
        event (new DossierAssignmentEvent($query->query_from_user_id, 'Vaiation Query Response Extension was Requested by '.auth()->user()->first_name));

        return Redirect()->back()->with('success', 'Request for Vaiation Query Response Extension Sent Successfully.');


    }

    // End Yemane Extension
}
