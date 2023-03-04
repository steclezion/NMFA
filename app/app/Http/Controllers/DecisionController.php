<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\certification;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Events\DossierAssignmentEvent;

use App\Models\Attachment;
use App\Models\applications;
use App\Models\DefermentQuery;
use App\Models\AppSetting;
use App\Models\AssessmentReport;
use App\Models\Variation;
use App\Models\User;
use \Mpdf\Mpdf as PDFF;
use Illuminate\Support\Facades\Storage;

use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\uploaded_documents;
use App\Models\MainTask;
use App\Models\Meeting;
use App\Models\Decision;
use App\Models\DecisionParticipant;
use App\Models\uploaded_documnts;
use App\Notifications\InformationNotification;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use MongoDB\Driver\Exception\ExecutionTimeoutException;
use PDF;

class DecisionController extends Controller
{


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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function applicant_decision_index()
    {


        $rejected_decisions = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('applications.user_id', auth()->user()->id)
            ->where('decisions.sealed_document_id', '!=', null)
            ->where('decision_status', 'Rejected')
            ->orWhere('decision_status', 'Reassign')
            ->orWhere('decision_status', 'Reassigned')
            ->select('decisions.*', 'meetings.meeting_date', 'medicines.product_name as pname',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name')
            ->get();

        $deferred_decisions = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('deferment_queries', 'deferment_queries.decision_id', 'decisions.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('decision_status', 'Deferred')
            ->where('applications.user_id', auth()->user()->id)
            ->where('decisions.sealed_document_id', '!=', null)
            ->select('decisions.*', 'meetings.meeting_date', 'medicines.product_name as pname',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name', 'deferment_queries.deadline as query_deadline')
            ->get();

        $accepted_decisions = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->leftjoin('certifications', 'certifications.decision_id', 'decisions.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('decision_status', 'Accepted')
            ->where('applications.user_id', auth()->user()->id)
            ->where('decisions.sealed_document_id', '!=', null)
            ->select(

                'meetings.meeting_date',
                'medicines.product_name',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name',
                'applications.application_id',
                'certifications.registration_number',
                'certifications.certificate_number',
                'decisions.*'
            )
            ->get();

        // dd($accepted_decisions[0]->pname);

        return view('CompletedApplications.decision_tab',
            [
                'rejected_decisions' => $rejected_decisions,
                'deferred_decisions' => $deferred_decisions,
                'accepted_decisions' => $accepted_decisions
            ]);

    }

    public function decision_index()
    {


        $rejected_decisions = $meetings = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('meetings.supervisor_id', auth()->user()->id)
            ->where('decision_status', 'Rejected')
            ->orWhere('decision_status', 'Reassign')
            ->orWhere('decision_status', 'Reassigned')
            ->select('decisions.*', 'meetings.meeting_date',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name', 'dossier_assignments.dossier_id',
                'applications.application_number', 'decisions.dossier_assignment_id')
            ->get();
        $deferred_decisions = $meetings = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('decision_status', 'Deferred')
            ->where('meetings.supervisor_id', auth()->user()->id)
            ->select('decisions.*', 'meetings.meeting_date',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name',
                'applications.application_number', 'decisions.dossier_assignment_id')
            ->get();
        $accepted_decisions = $meetings = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->leftjoin('certifications', 'certifications.decision_id', 'decisions.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('decision_status', 'Accepted')
            ->where('meetings.supervisor_id', auth()->user()->id)
            ->select('decisions.*', 'meetings.meeting_date',
                'company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name',
                'certifications.registration_number', 'certifications.certificate_number',
                'applications.application_number', 'decisions.dossier_assignment_id')
            ->get();


        return view('decisions.decision_tab', [
            'rejected_decisions' => $rejected_decisions,
            'deferred_decisions' => $deferred_decisions,
            'accepted_decisions' => $accepted_decisions]);
    }


    public function send_application_rejection(Request $request)
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
            $rejection_letter_file = $request->file('rejection_letter');
            $rejection_letter_filename = time() . '_' . $rejection_letter_file->getClientOriginalName();
            //todo: change dir to storage disk


            $rejection_letter_path = $dir . '/' . $rejection_letter_filename;

            // Upload files (copy files to destination)
            $rejection_letter_file->move($dir, $rejection_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $rejection_letter_path, $request, $attachment_available) {
                $decision_id = $request->input('decision_id');
                $uploaded_document = new uploaded_documents;
                $description = 'Rejection letter to applicant and agent';

                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Application Rejection Letter sent to Applicant and Agent ';
                $uploaded_document->path = $rejection_letter_path;
                $uploaded_document->document_type = 22; //TODO seed to document type 22 as rejection document
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

                $decision_id = $request->input('decision_id');


                Decision::where('id', $decision_id)->update(
                    [
                        'sealed_document_id' => $uploaded_document->id,
                        'appeal' => true,
                        'attachments' => $attachment_available
                    ]
                );
                //todo Lock the application after two months of rejection (if appeal is not received)

                // update activity timeline

                $decision_status = "Rejected";
                $sent_request = null;
                // add registration decision details to activity timeline
                $this->add_timeline($decision_id, $uploaded_document_id, $decision_status, $sent_request);

                // send notification to applicant
                $this->send_notification($decision_id, $uploaded_document_id, $decision_status);


            }); // end transaction

            return Redirect()->back()->with('success', 'Rejection Letter Successfully Sent to Applicant.');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

    }

    public function send_application_deferral(Request $request)
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


            // Sealed Deferment Letter from the company
            $deferment_letter_file = $request->file('deferment_letter');
            $deferment_letter_filename = time() . '_' . $deferment_letter_file->getClientOriginalName();
            //todo: change dir to storage disk


            $deferment_letter_path = $dir . '/' . $deferment_letter_filename;

            // Upload files (copy files to destination)
            $deferment_letter_file->move($dir, $deferment_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $deferment_letter_path, $request, $attachment_available) {
                $decision_id = $request->input('decision_id');
                $deadline = $request->input('deadline');

                $uploaded_document = new uploaded_documents;
                $description = 'Deferral letter to applicant and agent';

                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Application Deferral Letter sent to Applicant and Agent ';
                $uploaded_document->path = $deferment_letter_path;
                $uploaded_document->document_type = 24; //TODO seed to document type 24 as deferral document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $uploaded_document_id = $uploaded_document->id;

                if ($attachment_available) {
                    Attachment::insert([
                        'uploaded_documents_id' => $uploaded_document_id,
                        'path' => $attach_path,
                    ]);

                }

                Decision::where('id', $decision_id)->update(
                    [

                        'sealed_document_id' => $uploaded_document_id,
                        'attachments' => $attachment_available
                    ]
                );


                $decision = Decision::where('id', $decision_id)->first();
                DefermentQuery::insert(
                    [
                        'decision_id' => $decision_id,
                        'sent_date' => now(),
                        'status' => 'Sent',
                        'sent_document_id' => $uploaded_document_id,
                        'sent_subject' => 'Deferral Decision',
                        'sent_query' => 'Deferral Decision Letter Sent to the Applicant, Deadline is ' . $decision->deferred_date,
                        'deadline' => $decision->deferred_date

                    ]
                );

                $decision_status = "Deferred";
                $sent_request = null;
                // add registration decision details to activity timeline
                $this->add_timeline($decision_id, $uploaded_document_id, $decision_status, $sent_request);

                // send notification to applicant
                $this->send_notification($decision_id, $uploaded_document_id, $decision_status);


            }); // end transaction

            return Redirect()->back()->with('success', 'Deferral Letter Successfully Sent to Applicant.');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

    }


    public function send_application_accept(Request $request)
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
            $acceptance_letter_file = $request->file('acceptance_letter');
            $mah_letter_file = $request->file('mah_letter');
            $acceptance_letter_filename = time() . '_' . $acceptance_letter_file->getClientOriginalName();
            $mah_letter_filename = time() . '_' . $mah_letter_file->getClientOriginalName();
            //todo: change dir to storage disk


            $acceptance_letter_path = $dir . '/' . $acceptance_letter_filename;
            $mah_letter_path = $dir . '/' . $mah_letter_filename;

            // Upload files (copy files to destination)
            $acceptance_letter_file->move($dir, $acceptance_letter_filename);
            $mah_letter_file->move($dir, $mah_letter_filename);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ERROR 1' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($attach_path, $acceptance_letter_path, $mah_letter_path, $request, $attachment_available) {

                $decision_id = $request->input('decision_id');

                $decision = Decision::find($decision_id);


                $dossier_assignment = dossier_assignment::find($decision->dossier_assignment_id);

                $application = applications::find($dossier_assignment->application_id);
                $uploaded_document = new uploaded_documents;
                $description = 'Acceptance letter to applicant and agent';

                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Application Acceptance Letter sent to Applicant and Agent ';
                $uploaded_document->path = $acceptance_letter_path;
                $uploaded_document->document_type = 25; //TODO seed to document type 25 as Accept document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $acceptance_letter_id = $uploaded_document->id;


                $uploaded_document = new uploaded_documents;
                $description = 'MAH Certificate to applicant and agent';

                $uploaded_document->related_id = $decision_id;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'MAH Certificate sent to Applicant and Agent ';
                $uploaded_document->path = $acceptance_letter_path;
                $uploaded_document->document_type = 26; //TODO seed to document type 26 as MAH Certificate  document
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();
                $mah_letter_id = $uploaded_document->id;


                if ($attachment_available) {
                    Attachment::insert([
                        'uploaded_documents_id' => $acceptance_letter_id,
                        'path' => $attach_path,
                    ]);

                }


                Decision::where('id', $decision_id)->update(
                    [
                        'decision_status' => 'Accepted',
                        'sealed_document_id' => $acceptance_letter_id,
                        'attachments' => $attachment_available
                    ]
                );

                $certified_date = Carbon::now()->timezone('Africa/Asmara');
                $expiry_date = Carbon::now()->timezone('Africa/Asmara')->addYears(5);
                // OR use $now = date('Y-m-d H:i:s');
                //dd($certified_date, $expiry_date);


                DB::table('certifications')->where('decision_id', $decision_id)->update(
                    [

                        'sealed_MA_document' => $mah_letter_id,
                        'certified_date' => $certified_date->toDateTime(),
                        'expiry_date' => $expiry_date->toDateTime()
                    ]
                );


                if ($application->application_type == 1) {

                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $application->progress_percentage + 10,
                        ]);


                } else { // fast track
                    applications::where('id', $application->id)
                        ->update([
                            'progress_percentage' => $application->progress_percentage + 20,
                        ]);

                }


                //update activity for timeline
                $decision_status = "Accepted";
                $sent_request = null; //do not send $request from view, add_timeline() will construct its own messages.

                // add registration decision details to activity timeline
                $this->add_timeline($decision_id, $mah_letter_id, $decision_status, $sent_request);

                // send notification to applicant
                $this->send_notification($decision_id, $mah_letter_id, $decision_status);


            }); // end transaction

            return Redirect()->back()->with('success', 'Acceptance Letter Successfully Sent to Applicant.');

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ERROR 2: ' . $e->getMessage());

        }

    }

    public function decision_details($id)
    {
        $assigned_variations = [];
        $decision = Decision::where('decisions.id', $id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('meetings', 'meetings.id', 'decisions.meeting_id')
            ->leftjoin('uploaded_documents as downloaded_document', 'downloaded_document.id', 'decisions.downloaded_document_id')
            ->leftjoin('uploaded_documents as sealed_document', 'sealed_document.id', 'decisions.sealed_document_id')
            ->leftjoin('uploaded_documents as minutes_document', 'minutes_document.id', 'meetings.minutes_id')
            ->leftjoin('uploaded_documents as appeal_letter', 'appeal_letter.id', 'decisions.appeal_letter_id')
            ->select('decisions.*', 'applications.application_number', 'meetings.description', 'meetings.meeting_date', 'meetings.time', 'meetings.venue',
                'downloaded_document.path as downloaded_document_path', 'sealed_document.path as sealed_document_path', 'minutes_document.path as minute_path', 'appeal_letter.path as appeal_letter_path')
            ->first();
        // dd($decision);
        $attachment = Attachment::where('uploaded_documents_id', $decision->sealed_document_id)->first();
        $participants = DB::table('decision_participants')->join('users', 'users.id', 'decision_participants.committee_id')
            ->where('meeting_id', $decision->meeting_id)
            ->get();
        $deferment_queries = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->leftjoin('uploaded_documents as sent_document', 'sent_document.id', 'deferment_queries.sent_document_id')
            ->leftjoin('uploaded_documents as received_document', 'received_document.id', 'deferment_queries.received_document_id')
            ->select('deferment_queries.*', 'sent_document.path as sent_document_path', 'received_document.path as received_document_path')
            ->get();
        $check_query_with_out_deferment = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->where('received_date', null)
            ->get();
        $all_queries_reponsed = false;
        if (count($check_query_with_out_deferment) == 0) {
            $all_queries_reponsed = true;

        }


        // dd($participants);
        $assessor = User::join('dossier_assignments', 'dossier_assignments.assessor_id', 'users.id')
            ->where('dossier_assignments.id', $decision->dossier_assignment_id)
            ->select('users.*')
            ->first();


        $certification = null;

        if ($decision->decision_status == 'Accepted') {
            $certification = DB::table('certifications')
                ->join('decisions', 'decisions.id', 'certifications.decision_id')
                ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->leftjoin('uploaded_documents as downloaded_MAH_document', 'downloaded_MAH_document.id', 'certifications.MA_document_downloaded')
                ->leftjoin('uploaded_documents as sealed_MAH_document', 'sealed_MAH_document.id', 'certifications.sealed_MA_document')
                ->where('decision_id', $decision->id)
                ->select('certifications.*', 'downloaded_MAH_document.path as downloaded_MAH_document_path',
                    'sealed_MAH_document.path as sealed_MAH_document_path', 'applications.application_type', 'applications.application_number')
                ->first();

            if (@$certification->id == '') {

                // dd('i');
                $assigned_variations = [];
            } else {
                $assigned_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
                    ->leftjoin('variation_decisions', 'variations.id', 'variation_decisions.variation_id')
                    ->join('decisions', 'decisions.id', 'certifications.decision_id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->leftjoin('users', 'users.id', 'variations.assessor_id')
                    ->where('variations.certificate_id', $certification->id)
                    ->select('variations.*', 'variation_decisions.decision_status', 'users.first_name', 'users.middle_name', 'certifications.registration_number',
                        'applications.application_id as app_id', 'applications.medical_product_id')
                    ->get();


            }

            $view = "decisions.accepted_edit";
        } elseif ($decision->decision_status == 'Deferred') {
            $view = "decisions.deferred_edit";
        } else {
            $view = "decisions.rejected_edit";
        }
        $date = date('Y-m-d');
        return view($view, ['decision' => $decision, 'participants' => $participants, 'date' => $date, 'all_queries_reponsed' => $all_queries_reponsed
            , 'certificate' => $certification, 'attachment' => $attachment, 'deferment_queries' => $deferment_queries
            , 'assessor' => $assessor, 'variations' => $assigned_variations]);
    }

    public function update_reject_decision(Request $request)
    {


        try {

            $appeal_accepted = $request->input('appeal');//0 for reject 1 for accepted

            if ($appeal_accepted == 1) {
                $file = $request->file('accepted_document');
            } elseif ($appeal_accepted == 0) {
                $file = $request->file('rejected_document');
            }
            $decision_id = $request->input('decision_id');


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
                $decision = Decision::find($decision_id);
                $dossier_ass = dossier_assignment::where('id', $decision->dossier_assignment_id)->first();
                if ($appeal_accepted == 1) {
                    Decision::where('id', $decision_id)->update([
                        'appeal' => 1,
                        'appeal_letter_id' => $pdf_generated_uploaded_id,
                        'appeal_status' => 'Accepted',
                        'decision_status' => 'Reassign',
                        'appeal_decision_date' => now()
                    ]);
                    DB::table('dossiers')->where('id', $dossier_ass->dossier_id)->update(
                        [
                            'assignment_status' => 8
                        ]
                    );
                    //lock previous dossier assignment


                } else {
                    Decision::where('id', $decision_id)->update([
                        'appeal' => 1,
                        'appeal_letter_id' => $pdf_generated_uploaded_id,
                        'appeal_status' => 'Rejected',
                        'appeal_decision_date' => now()
                    ]);
                    //lock the product
                }


                $this->add_timeline($decision_id, $pdf_generated_uploaded_id, 'Appeal_status', $request);
                $this->send_notification($decision_id, $pdf_generated_uploaded_id, 'Appeal_status');


            });
            return Redirect()->back()->with('success', 'Appeal Document Successfully Uploaded.');

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect()->back()->with('success', 'Meeting Data Inserted Successfully.');


    }


    public function retrive_all_information(Request $request)
    {


        try {

            $id = $request->id;
            $decision = Decision::find($id);


            $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $decision->dossier_assignment_id)
                ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
                ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('users as applicant', 'applicant.id', 'applications.user_id')
                ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
                ->join('manufacturers', 'medicinal_products.application_id', 'manufacturers.application_id')
                ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
                ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                ->join('countries', 'countries.id', 'company_suppliers.country_id')
                ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
                ->select(
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
                    'applications.created_at',
                    'applications.progress_percentage',
                    'applications.application_type',
                    'applications.application_number',
                    'company_suppliers.trade_name as company_name',
                    'company_suppliers.city',
                    'company_suppliers.state',
                    'company_suppliers.address_line_one',
                    'countries.country_name',
                    /*'applicant.first_name as applicant_first_name',
                    'applicant.middle_name  as applicant_middle_name',*/
                    'contacts.first_name as applicant_first_name',
                    'contacts.last_name  as applicant_middle_name', // as --- not changed to avoid breaking code elsewhere
                    'route_administrations.name as route_administration_name',
                    'dosage_forms.name as dosage_form_name',
                    'medicines.product_name',
                    'manufacturers.addressline_one as manufacturer_address'
                )
                ->first();


            $dosser_assign = dossier_assignment::find($decision->dossier_assignment_id);
            $application = applications::find($dosser_assign->application_id);


            $product_composition_info = DB::table('product_compositions')->where('product_compositions.application_id', $application->application_id)
                ->where('product_compositions.type', 'API')->get();


            $agent_contact_info = DB::table('agents')->where('agents.application_id', $application->application_id)
                ->first();


            $approved_self_life = $dossier_evaluation_details->proposed_shelf_life_amount . ' ' . $dossier_evaluation_details->shelf_life_unit;
            $presentation_packaging = $dossier_evaluation_details->visual_description . ', ' . $dossier_evaluation_details->packaging;;
            $agent_company = $agent_contact_info->trade_name;


            $api = '';
            $strength = '';
            $i = 1;
            foreach ($product_composition_info as $product) {
                $api .= $product->composition_name . ', ';
                $strength .= $i . '. ' . $product->composition_name . ' ' . $product->quantity . '<br>';

                $i++;
            }


            $certificate = DB::table('certifications')->where('decision_id', $decision->id)->first();
            $decision = Decision::find($decision->id);
            $applied_date = $decision->decision_date;

            $converted_date = Carbon::create($applied_date)->format('d/m/Y');
            $expiry = Carbon::create($applied_date)->addYear(5)->subDay(1)->format('d/m/Y');
            $date_now = Carbon::now()->format('d/m/Y');

            return response()->json(['data' => $dossier_evaluation_details, 'approved_self_life' => $approved_self_life, 'presentation_packaging' => $presentation_packaging,
                'certified_date' => $converted_date, 'certificate' => $certificate, 'agent_company' => $agent_company, 'api' => $api, 'strength' => $strength, 'expiry_date' => $expiry, 'date_now' => $date_now]);


        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => false, 'item' => 'item_success']);


    }

    public function download_decision_letter(Request $request)
    {

        try {
            $decision_id = $request->input('decision_id');
            $decision = Decision::where('id', $decision_id)->first();
            $decision_assignment = dossier_assignment::where('id', $decision->dossier_assignment_id)->first();
            $application = applications::where('id', $decision_assignment->application_id)->first();
            $deadline = null;
            $upload_date = date('Y-m-s-H-m-s');
            $dir = 'documents/uploads/';
            $file_name = 'decision_letter.pdf';
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
            $uploaded_document->related_id = $decision_id;
            $uploaded_document->name = $file_name;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 25; //Downloaded decision document id
            $uploaded_document->description = 'Decision Document';
            // insert records
            $saved = $uploaded_document->save();

            if ($decision->downloaded_document_id == null) {
                if ($decision->decision_status == 'Deferred') {

                    $deadline = $request->input('deadline');

                }
                if ($decision->decision_status == 'Accepted') {

                    $year_setting = AppSetting::where('name', 'current_year')->first();
                    $year_in_db = $year_setting->value;
                    $year_from_system = date('Y');

                    if ($year_in_db == $year_from_system) {
                        $product_setting = AppSetting::where('name', 'product_registration_counter')->first();
                        $count = $product_setting->value;
                        $zero_filled_counter = sprintf('%04d', $count);

                        //always check R1
                        $certificate_type = $request->input('certificate_type');
                        // dd($certificate_type);
                        $registration_num = 'NMFA/' . $certificate_type . '/' . $application->re_registration_number . '/' . $year_from_system . '/' . $zero_filled_counter;
                        $certificate_number = $zero_filled_counter . '/' . $year_from_system;
                        //increment the counter
                        AppSetting::where('name', 'product_registration_counter')->update(
                            [
                                'value' => $count + 1,
                            ]
                        );

                    } else {
                        //update the current year in db to the system year
                        AppSetting::where('name', 'current_year')->update(
                            [
                                'value' => $year_from_system,
                            ]
                        );
                        //reset the counter to 1
                        $count = 1;
                        AppSetting::where('name', 'dossier_ref_num_counter')->update(
                            [
                                'value' => $count,
                            ]
                        );
                        AppSetting::where('name', 'product_registration_counter')->update(
                            [
                                'value' => $count + 1,
                            ]
                        );
                        AppSetting::where('name', 'letter_reference_number_counters')->update(
                            [
                                'value' => $count,
                            ]
                        );


                        $zero_filled_counter = sprintf('%04d', $count);

                        $registration_num = 'NMFA/GM/R1/' . $year_from_system . '/' . $zero_filled_counter;
                        $certificate_number = $zero_filled_counter . '/' . $year_from_system;

                    }


                    DB::table('certifications')->insert(
                        [
                            'decision_id' => $decision_id,
                            'registration_number' => $registration_num,
                            'certificate_number' => $certificate_number

                        ]
                    );
                }


                Decision::where('id', $decision_id)->update(
                    [
                        'downloaded_document_id' => $uploaded_document->id,
                        'decision_letter_downloaded' => 1,
                        'downoloded_date' => now(),
                        'deferred_date' => $deadline
                    ]);
            } else {
                Decision::where('id', $decision_id)->update(
                    [
                        'downloaded_document_id' => $uploaded_document->id,
                        'deferred_date' => $deadline
                    ]);

                if ($decision->decision_status == 'Accepted') {
                    $certificate = DB::table('certifications')->where('decision_id', $decision_id)->first();
                    $certificate_type = $request->input('certificate_type');
                    $old_certificate = $certificate->registration_number;
                    $values = explode("/", $old_certificate);
                    $values[1] = $certificate_type;
                    $new_certificate = implode("/", $values);
                    $certificate = DB::table('certifications')->where('decision_id', $decision_id)->update(
                        ["registration_number" => $new_certificate]
                    );


                }


            }
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Download. ' . $e->getMessage());

        }

        // $request->session()->flash('download.request');
        // return response()->download($path);

        return redirect('/Decision/edit/' . $decision_id)->with('success', 'Decision Letter Generated Successfully.');

    }

    public function download_market_authorization_letter(Request $request)
    {


        $data = '
        <div style="border-style:solid;border-width:2px; padding-left:0px; width:100%;height: 100%;">
            <div style="border-width:2px border-style:solid ; width:100%;height: 100%;">
            <div style="border-style:solid;border-color: blue;border-width:5px; width:100%;height: 100%;">
            <table width="100%" height="100px" cellpadding="0">
            <tr>
            <td width="20%">
            <img src="./images/camel.png" style="height:100px;width:100px;"/>
            </td>
            <td width="60%">
            <center>
            <h1 align="center" style="margin-top:0in;text-align:center;line-height:100%"> 
            <span style="font-size:16.0pt;line-height:100%">The State of Eritrea</span><br>
            <span style="font-size:16.0pt;line-height:100%">Ministry of Health</span><br>
            <span style="font-size:16.0pt;line-height:100%">National Medicines and Food Administration</span>
            </h1>
            </center>
            </td>

            <td width="20%">
            <img src="./images/MOH.png" style="height:100px;width:100px;"/>
            </td>
            </tr>
            
            </table>';
        $data .= $request->input('data');
        $data .= '
        <table  width="100%"  height="25px">
            <tr>
            <td width="33%">
            <img src="./images/nmfa.png" style="height:80px;width:100px;"/>
            </td>
            <td width="37%">
             <hr style="width:80%;border-width:2px;color:black">
             <h6 style="text-align:center;font-size:10px"> Director</h6>
             <h6 style="text-align:center;font-size:10px"> National Medicines and Food Administration</h6>
            </td>
                 
            <td width="30%">
            <img src="./images/MOH.png" style="height:80px;width:100px;"/>
            
            </td>
            </tr>
           <tr>
           <td colspan=3>
           <hr style="width:100%;border-width:2px;color:black">
           </td>
           </tr>
            <tr>
            <td width="30%"> 
            <h6 style="text-align:left;font-size:10px"> National Medicines and Food Administration<br>P.O. Box: 212 Asmara, Eritrea</h6>
            </td>
            <td width="40%">
            <h6 style="text-align:center;font-size:10px"> Tel:  +291-1-125393<br>
                   +291-1-125525</h6>
            </td>
            <td width="30%">
            <h6 style="text-align:right;font-size:10px"> Fax:  +291-1-122899<br>Email: er.peru.nmfa@gmail.com</h6>
            </td>
            </tr>

            </table>

            </div>
            </div>
            </div>';


        try {
            DB::transaction(function () use ($request, $data) {


                $decision_id = $request->input('decision_id');

                $certified_date = $request->input('certified_date');
                $certified_date = Carbon::createFromFormat('d/m/Y', $certified_date);
                $certified_date = $certified_date->format('Y-m-d H:I:s');

                $expiry_date = $request->input('expiry_date');
                $expiry_date = Carbon::createFromFormat('d/m/Y', $expiry_date);
                $expiry_date = $expiry_date->format('Y-m-d H:I:s');

                $upload_date = date('Y-m-d H:I:s');
                $dir = 'documents/uploads/';
                $file_name = 'MAH_Letter.pdf';
                $uploaded_file_name = $upload_date .'_'. $file_name;
                $dat = $request->input('data');

                $document = new PDFF([
                        'mode' => "utf-8",
                        'format' => "A4",
                        'margin_header' => "1",
                        'margin_top' => "10",
                        'margin_bottom' => "10",
                        'margin_left' => "5",
                        'margin_right' => "5",
                    ]
                );

                $header = [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];


                $document->WriteHTML($data);

                Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));


                $path = $dir . $uploaded_file_name;


                $uploaded_document = new uploaded_documents;
                $uploaded_document->related_id = $decision_id;
                $uploaded_document->name = $file_name;
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 26; //Downloaded MAH
                $uploaded_document->description = 'MAH Document';
                // insert records
                $uploaded_document->save();


                DB::table('certifications')->where('decision_id', $decision_id)->update(
                    [
                        'MA_document_downloaded' => $uploaded_document->id,
                        'certified_date' => $certified_date,
                        'expiry_date' => $expiry_date,
                        'status' => 'reregistration_closed'

                    ]
                );

                // insert this new authorized certificate into main task
                $certification = certification::where('decision_id', $decision_id)->first();

                MainTask::insert(
                    [
                        'task_name' => 'Certification',
                        'related_task' => 'Certification',
                        'related_id' => $certification->id,
                        'task_duration_days_plan' => 0, //zero id dummy value since this field is irrelevant (but mandatory)
                        'start_time' => $certified_date,
                        'end_time' => $expiry_date,
                        'is_complete' => 0, //dummy value
                        'is_archived' => 0, //dummy value
                        'task_status' => 'registration_active',
                        'alert_before_days' => 0,  ///zero id dummy value since this field is irrelevant (but mandatory)

                    ]
                );

            }); // end transaction

            $decision_id = $request->input('decision_id');;
            return redirect('/Decision/edit/' . $decision_id)->with('success', 'MAH Letter Generated Successfully.');
            //return response()->download($path, $uploaded_file_name);
            //return Storage::disk('documents')->download($uploaded_file_name,'Request',$header);
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with MAH Certificate Generation. ' . $e->getMessage());
        }
    }


    public function send_deferral_query(Request $request)
    {

        try {

            // query response attached in zip file
            $document = $request->file('document');
            $dir = 'documents/uploads';
            $document_available = false;
            $document_path = null;
            if ($document != null) {
                $document_filename = time() . '_' . $document->getClientOriginalName();
                $document_path = $dir . '/' . $document_filename;

                $document->move($dir, $document_filename);
                $document_available = true;
            }
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($document_path, $request, $document_available) {


                $subject = $request->input('subject');
                $body = 'Deferral Query has been sent on ' . Carbon::now();
                $decision_id = $request->input('decision_id');
                $deadline = $request->input('deadline');


                $document_id = null;
                if ($document_available) {

                    $uploaded_document = new uploaded_documents;

                    $uploaded_document->related_id = $decision_id;
                    $uploaded_document->ref_num = '';
                    $uploaded_document->name = 'Query Document';
                    $uploaded_document->path = $document_path;
                    $uploaded_document->document_type = 24;
                    $uploaded_document->description = $subject;

                    $uploaded_document->save();
                    $document_id = $uploaded_document->id;
                }


                DefermentQuery::insert(
                    [
                        'decision_id' => $decision_id,
                        'sent_date' => now(),
                        'status' => 'Sent',
                        'sent_document_id' => $document_id,
                        'sent_subject' => $subject,
                        'sent_query' => $body,
                        'deadline' => $deadline


                    ]
                );


                //todo timeline and notification

                $decision_status = 'Deferral_query';

                // add registration decision details to activity timeline
                $this->add_timeline($decision_id, $document_id, $decision_status, $request);

                // $subject_, $description_ are just placeholders in this notification
                // subject, and description/body are taken from the form $request
                list($subject_, $description_, $application_details) = $this->get_decision_message($decision_status, $decision_id);

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $body;
                $new_notification['subject'] = $subject;
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = $document_id;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $application_details->dossier_assignment_id; //todo ask mera why u using decision_id as related Id?
                $new_notification['remark'] = '';
                $applicant = User::find($application_details->applicant_id);

                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $subject));


            }); // end transaction

            return Redirect()->back()->with('success', 'Query Sent to Applicant');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }


    }

    public function decision_applicant_details($id)
    {

        $decision = Decision::where('decisions.id', $id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('meetings', 'meetings.id', 'decisions.meeting_id')
            ->leftjoin('uploaded_documents as sealed_document', 'sealed_document.id', 'decisions.sealed_document_id')
            ->leftjoin('uploaded_documents as appeal_letter', 'appeal_letter.id', 'decisions.appeal_letter_id')
            ->select('decisions.*', 'meetings.description', 'meetings.meeting_date', 'meetings.time', 'meetings.venue',
                'sealed_document.path as sealed_document_path',
                'appeal_letter.path as appeal_letter_path', 'applications.application_number', 'applications.application_type', 'medicines.product_name')
            ->first();
        $attachment = Attachment::where('uploaded_documents_id', $decision->sealed_document_id)->first();

        $deferment_queries = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->leftjoin('uploaded_documents as sent_document', 'sent_document.id', 'deferment_queries.sent_document_id')
            ->leftjoin('uploaded_documents as received_document', 'received_document.id', 'deferment_queries.received_document_id')
            ->select('deferment_queries.*', 'sent_document.path as sent_document_path', 'received_document.path as received_document_path')
            ->get();
        $check_query_with_out_deferment = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->where('received_date', null)
            ->get();
        $all_queries_reponsed = false;
        if (count($check_query_with_out_deferment) == 0) {
            $all_queries_reponsed = true;

        }

        $certification = DB::table('certifications')
            ->leftjoin('uploaded_documents as downloaded_MAH_document', 'downloaded_MAH_document.id', 'certifications.MA_document_downloaded')
            ->leftjoin('uploaded_documents as sealed_MAH_document', 'sealed_MAH_document.id', 'certifications.sealed_MA_document')
            ->where('decision_id', $decision->id)
            ->select('certifications.*', 'downloaded_MAH_document.path as downloaded_MAH_document_path', 'sealed_MAH_document.path as sealed_MAH_document_path')
            ->first();

        return view('decisions.applicant_decision_details', ['decision' => $decision, 'all_queries_reponsed' => $all_queries_reponsed
            , 'certificate' => $certification, 'attachment' => $attachment, 'deferment_queries' => $deferment_queries]);
    }


    public function query_response(Request $request)
    {

        try {

            // query response attached in zip file
            $document = $request->file('document');
            $dir = 'documents/uploads';
            $document_available = false;
            $document_path = null;
            if ($document != null) {
                $document_filename = time() . '_' . $document->getClientOriginalName();
                $document_path = $dir . '/' . $document_filename;

                $document->move($dir, $document_filename);
                $document_available = true;
            }
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());

        }

        try {
            DB::transaction(function () use ($document_path, $request, $document_available) {


                $subject = $request->input('subject');
                $deferment_id = $request->input('deferment_id');


                $deferment = DefermentQuery::where('id', $deferment_id)->first();


                $document_id = null;
                if ($document_available) {

                    $uploaded_document = new uploaded_documents;

                    $uploaded_document->related_id = $deferment->decision_id;
                    $uploaded_document->ref_num = '';
                    $uploaded_document->name = 'Response Document';
                    $uploaded_document->path = $document_path;
                    $uploaded_document->document_type = 24; //TODO seed to document type 24 as deferment document
                    $uploaded_document->description = $subject;

                    $uploaded_document->save();
                    $document_id = $uploaded_document->id;
                }


                DefermentQuery::where('id', $deferment_id)->update(
                    [

                        'received_date' => now(),
                        'status' => 'Received',
                        'received_document_id' => $document_id,
                        'received_subject' => $subject

                    ]
                );


                //Todo notification, main_task
                $this->add_timeline($deferment->decision_id, $document_id, 'Deferral_query_response', null);
                $this->send_notification($deferment->decision_id, $document_id, 'Deferral_query_response');


            }); // end transaction

            return Redirect()->back()->with('success', 'Query Response Sent');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
        }
    }

    public function return_deferment_to_assessor(Request $request)
    {

        $assigned_assessor = $request->input('assigned_assessor');

        try {
            DB::transaction(function () use ($request) {
                $deadline = $request->input('evaluationDeadline');
                $decision_id = $request->input('decision_id');
                $data = User::join('dossier_assignments', 'dossier_assignments.assessor_id', 'users.id')
                    ->join('decisions', 'decisions.dossier_assignment_id', 'dossier_assignments.id')
                    ->where('decisions.id', $decision_id)
                    ->select('users.id as assessor_id', 'decisions.dossier_assignment_id')
                    ->first();


                //update main tasks taskstatus to inprogress
                MainTask::where('related_id', $data->dossier_assignment_id)
                    ->where('related_task', 'Dossier Evaluation')
                    ->update([
                        'task_status' => 'Inprogress',
                        'deadline' => $deadline,
                    ]);


                $dossier_assignment = dossier_assignment::where('id', $data->dossier_assignment_id)->first();

                dossier_evaluation_progress::where('dossier_assignment_id', $data->dossier_assignment_id)
                    ->update(
                        [
                            'deferred_assessment_submitted_to_supervisor' => 0,
                        ]
                    );
                // update decision to decision_assignment_status
                dossier::where('id', $dossier_assignment->dossier_id)->update([
                    'dossiers.assignment_status' => 3
                ]);


                Decision::where('id', $decision_id)->update(['locked' => 1]);

                $this->add_timeline($decision_id, null, 'Deferral_returned_to_assessor', $sent_request = null);


                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Deferred Product ';
                $new_notification['subject'] = 'Deferral';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = null;
                $new_notification['related_id'] = $decision_id;
                $new_notification['remark'] = '';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $user = User::find($data->assessor_id);
                Notification::send($user, new InformationNotification($new_notification));

                event(new DossierAssignmentEvent($user->id, 'Deferred Dossier Returned to Assessor. '));


            }); // end transaction


            return Redirect()->back()->with('success', 'The product dossier has been re-assigned to ' . $assigned_assessor . '.');


        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Problem with File Upload. ' . $e->getMessage());
        }


    }


    private function add_timeline($decision_id, $uploaded_document_id, $decision_status, $sent_request)
    {

        $decision = Decision::find($decision_id);
        $dossier_assignment = dossier_assignment::find($decision->dossier_assignment_id);
        $main_task = $this->get_main_task_id($dossier_assignment->id, 'Dossier Evaluation');
        $meeting = Meeting::where('id', $decision->meeting_id)->first();

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

            list($subject, $description, $application_details_) = $this->get_decision_message($decision_status, $decision_id);
        }
        // get notification details to show in timeline

        $task_category = 'Decision';
        $task_activity_title = $subject;
        $content_details = $description;
        $route_link = '';
        $activity_status = 'Decision';

        //insert this into task tracker
        $main_task_inserted = MainTaskController::insertActivity($main_task->id, $decision_date, null,
            $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

        if (!$main_task_inserted) {
            throw new MainTaskNotInsertedException('Can not insert activity details.
                    Your changes have not been updated to database.');
        }

    }


    private function send_notification($decision_id, $uploaded_document_id, $decision_status)
    {
        // get decision message to be sent along with notification
        list($subject, $description, $application_details) = $this->get_decision_message($decision_status, $decision_id);

        $new_notification = [];
        $new_notification['type'] = 'Notification';
        $new_notification['data'] = $description;
        $new_notification['subject'] = $subject;
        $new_notification['alert_level'] = '';
        $new_notification['related_document'] = $uploaded_document_id;
        $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
        $new_notification['related_id'] = $application_details->dossier_assignment_id;
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
    private function get_decision_message($decision_status, $decision_id)
    {


        // set notification description based on the decision status
        $decision = Decision::find($decision_id);
        // get applicant id, doss. assign. id, product name
        $application_details = Decision::join('dossier_assignments',
            'decisions.dossier_assignment_id', 'dossier_assignments.id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('users', 'users.id', 'applications.user_id')
            ->where('decisions.id', $decision_id)
            ->select('users.id as applicant_id', 'dossier_assignments.id as dossier_assignment_id', 'medicinal_products.product_trade_name')
            ->first();

        $subject = 'Decision for Product Registration of ' . $application_details->product_trade_name;
        $description = "";
        if ($decision_status == 'Accepted') {
            $description = 'Congratulations! The Application has been ' . $decision_status . '.';

        } elseif ($decision_status == 'Rejected') {
            $description = 'We regret to inform you that, the application has NOT been accepted for registration. 
                            Please refer to the following instructions if you want to issue appeal';

        } elseif ($decision_status == 'Deferred') {

            $description = 'The application decision has been deferred to ' . $decision->deferred_date . '
            . We will contact you for further details and queries.';

        } elseif ($decision_status == 'Deferral_query') {

            $description = 'New Deferral Query has been sent for product ' . $application_details->product_trade_name . '.';
        } elseif ($decision_status == 'Deferral_query_response') {

            $application_details = Decision::join('dossier_assignments',
                'decisions.dossier_assignment_id', 'dossier_assignments.id')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                ->join('users', 'users.id', 'dossier_assignments.supervisor_id')
                ->where('decisions.id', $decision_id)
                ->select('users.id as applicant_id', 'dossier_assignments.id as dossier_assignment_id', 'medicinal_products.product_trade_name')
                ->first();
            //applicant_id: above is really supervisor_id but left as is for compatibility with the view part

            $subject = 'Deferral Query Response for Product Registration of ' . $application_details->product_trade_name . '.';

            $description = 'Deferral Query Response has been Sent for product ' . $application_details->product_trade_name . '.';
        } elseif ($decision_status == 'Deferral_returned_to_assessor') {

            $subject = 'Evaluation Returned to Assessor for deferral Evaluation.';

            $description = 'Evaluation Returned to Assessor for deferral Evaluation. Product Trade Name: ' . $application_details->product_trade_name . '.';

        } elseif ($decision_status == 'Appeal_status') {

            $subject = 'Appeal Decision.';
            $description = 'Appeal Decision has been sent for product: ' . $application_details->product_trade_name . '.';
        } else {
            //do nothing
        }
        return array($subject, $description, $application_details);
    }

    public function assesment_details(Request $request)
    {
        try {

            $id = $request->id;
            $type = $request->type;
            if ($type == 'variation') {
                $variation = Variation::find($id);
                $uploaded_documents = DB::table('uploaded_documents')
                    ->where('id', $variation->assessment_report_document_id)
                    ->get();

            } else {

                $assessment_report_detail = db::table('assessment_reports')->where('assessment_related_id', $id)
                    ->Where('name', 'Assessment Report Submission (Final_revised)')
                    ->first();


                $uploaded_document_ids = explode(',', $assessment_report_detail->sent_document_id);

                $uploaded_documents = DB::table('uploaded_documents')
                    ->whereIn('id', $uploaded_document_ids)
                    ->get();
            }


            return response()->json(['data' => $uploaded_documents]);


        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);
    }


    public function query_details(Request $request)
    {
        try {

            $id = $request->id;

            $query = DefermentQuery::join('decisions', 'decisions.id', 'deferment_queries.decision_id')
                ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('company_suppliers', 'applications.application_id', 'company_suppliers.application_id')
                ->join('users as supervisor', 'supervisor.id', 'dossier_assignments.supervisor_id')
                ->leftjoin('users as applicant', 'applicant.id', 'applications.user_id')
                ->where('deferment_queries.id', $id)
                ->select('deferment_queries.*', 'company_suppliers.trade_name as company_name'
                    /*'applicant.first_name as applicant_first_name', 'applicant.middle_name as applicant_middle_name'*/
                    , 'supervisor.first_name as supervisor_first_name'
                    , 'supervisor.middle_name as supervisor_middle_name')
                ->first();


            $supervisor_document = uploaded_documents::where('id', $query->sent_document_id)->first();
            $applicant_document = uploaded_documents::where('id', $query->received_document_id)->first();


            return response()->json(['data' => $query, 'received_document' => $applicant_document,
                'sent_document' => $supervisor_document
            ]);


        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);
    }

    public function applicant_decision_details($id)
    {

        $assigned_variations = [];
        $decision = Decision::where('decisions.id', $id)
            ->join('meetings', 'meetings.id', 'decisions.meeting_id')
            ->leftjoin('uploaded_documents as downloaded_document', 'downloaded_document.id', 'decisions.downloaded_document_id')
            ->leftjoin('uploaded_documents as sealed_document', 'sealed_document.id', 'decisions.sealed_document_id')
            ->leftjoin('uploaded_documents as minutes_document', 'minutes_document.id', 'meetings.minutes_id')
            ->leftjoin('uploaded_documents as appeal_letter', 'appeal_letter.id', 'decisions.appeal_letter_id')
            ->select('decisions.*', 'meetings.description', 'meetings.meeting_date', 'meetings.time', 'meetings.venue',
                'downloaded_document.path as downloaded_document_path', 'sealed_document.path as sealed_document_path', 'minutes_document.path as minute_path', 'appeal_letter.path as appeal_letter_path')
            ->first();
        $attachment = Attachment::where('uploaded_documents_id', $decision->sealed_document_id)->first();
        $participants = DB::table('decision_participants')->join('users', 'users.id', 'decision_participants.committee_id')
            ->where('meeting_id', $decision->meeting_id)
            ->get();
        $deferment_queries = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->leftjoin('uploaded_documents as sent_document', 'sent_document.id', 'deferment_queries.sent_document_id')
            ->leftjoin('uploaded_documents as received_document', 'received_document.id', 'deferment_queries.received_document_id')
            ->select('deferment_queries.*', 'sent_document.path as sent_document_path', 'received_document.path as received_document_path')
            ->get();
        $check_query_with_out_deferment = DefermentQuery::where('deferment_queries.decision_id', $decision->id)
            ->where('received_date', null)
            ->get();
        $all_queries_reponsed = false;
        if (count($check_query_with_out_deferment) == 0) {
            $all_queries_reponsed = true;

        }


        // dd($participants);
        $assessor = User::join('dossier_assignments', 'dossier_assignments.assessor_id', 'users.id')
            ->where('dossier_assignments.id', $decision->dossier_assignment_id)
            ->select('users.*')
            ->first();


        $certification = null;

        if ($decision->decision_status == 'Accepted') {
            $certification = DB::table('certifications')
                ->join('decisions', 'decisions.id', 'certifications.decision_id')
                ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->leftjoin('uploaded_documents as downloaded_MAH_document', 'downloaded_MAH_document.id', 'certifications.MA_document_downloaded')
                ->leftjoin('uploaded_documents as sealed_MAH_document', 'sealed_MAH_document.id', 'certifications.sealed_MA_document')
                ->where('decision_id', $decision->id)
                ->select('certifications.*', 'downloaded_MAH_document.path as downloaded_MAH_document_path',
                    'sealed_MAH_document.path as sealed_MAH_document_path', 'applications.application_type', 'applications.application_number')
                ->first();

            if (@$certification->id == '') {

                // dd('i');
                $assigned_variations = [];
            } else {

                $assigned_variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
                    ->leftjoin('variation_decisions', 'variations.id', 'variation_decisions.variation_id')
                    ->join('decisions', 'decisions.id', 'certifications.decision_id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->leftjoin('users', 'users.id', 'variations.assessor_id')
                    ->where('variations.certificate_id', $certification->id)
                    ->select('variations.*', 'variation_decisions.decision_status', 'users.first_name', 'users.middle_name', 'certifications.registration_number',
                        'applications.application_id as app_id', 'applications.medical_product_id', 'variation_decisions.sealed_document_id')
                    ->get();


            }

            $view = "CompletedApplications.accepted_edit";
        } elseif ($decision->decision_status == 'Deferred') {
            $view = "CompletedApplications.deferred_edit";
        } else {
            $view = "CompletedApplications.rejected_edit";
        }

        $application_details = dossier_assignment::where('dossier_assignments.id', $decision->dossier_assignment_id)
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
                'applications.created_at as app_created_at',
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
                'applicant.first_name as applicant_first_name',
                'applicant.middle_name  as applicant_middle_name',
                'route_administrations.name as route_administration_name',
                'dosage_forms.name as dosage_form_name',
                'checklists.sample_received_date',
                'applications.created_at as app_created_at'
            )
            ->first();

        $date = date('Y-m-d');
        return view($view, ['decision' => $decision, 'participants' => $participants, 'date' => $date, 'all_queries_reponsed' => $all_queries_reponsed
            , 'certificate' => $certification, 'attachment' => $attachment, 'deferment_queries' => $deferment_queries
            , 'assessor' => $assessor, 'variations' => $assigned_variations, 'application_details' => $application_details]);
    }

    public function reregistraton_open_index()
    {


        $applications = certification::join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->where('applications.user_id', auth()->user()->id)
            ->Where('certifications.status', 'reregistration_open')
            ->select('applications.*', 'medicines.product_name', 'decisions.decision_status', 'medicinal_products.product_trade_name',
                'decisions.id as decision_id', 'decisions.locked', 'certifications.registration_number', 'certifications.id as certification_id')
            ->get();

        return view('applicant.reregistration_open_index', compact('applications'));
    }

    public function applicant_query_deferment_deadline_extension_request(Request $request)
    {

        $query_deferment_id = $request->input('query_deferment_id');

        $deferment_query = DefermentQuery::where('deferment_queries.id', $query_deferment_id)
            ->join('decisions', 'decisions.id', 'deferment_queries.decision_id')
            ->select('deferment_queries.*', 'decisions.dossier_assignment_id')
            ->first();
        $dossier_assing_id = $deferment_query->dossier_assignment_id;
        $description = $request->input('extension_reason');
        $deadline = $request->input('extended_deadline');
        $dossier_assign_details = dossier_assignment::find($dossier_assing_id);


        //update activity for timeline
        $main_task = $this->get_main_task_id($dossier_assing_id);
        $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Deferral Query Deadline Extension';
        $task_activity_title = 'Deadline Extension Request Deferral Query';
        $supervisor = User::find($dossier_assign_details->supervisor_id); //assessor who assigned the section
        $content_details = 'Deferral Query Response Date Extension was Requested by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name .
            '. Date Requested: ' . $deadline . '. Reason: ' . $description;
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
        $new_notification['subject'] = $task_category;
        $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
        $new_notification['alert_level'] = 'high';
        $new_notification['related_document'] = '';
        $new_notification['related_id'] = $dossier_assing_id;
        $new_notification['remark'] = '';

        DefermentQuery::where('deferment_queries.id', $query_deferment_id)
            ->update(['deadline_requested' => 1]);


        Notification::send($supervisor, new InformationNotification($new_notification));
        event(new DossierAssignmentEvent($supervisor->id, 'Deferral Query Response Date Extension was Requested by ' . auth()->user()->first_name));

        return Redirect()->back()->with('success', ' Deferral Query Response Date Extension Request Sent Successfully.');


    }


}
