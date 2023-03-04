<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\letterReferenceNumberGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Events\DossierAssignmentEvent;
use App\Models\Variation;
use App\Models\AssessmentReport;
use App\Models\AppSetting;
use App\Models\User;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\uploaded_documents;
use App\Models\MainTask;
use App\Models\Meeting;
use \Mpdf\Mpdf as PDFF;
use Illuminate\Support\Facades\Storage;
use App\Models\Decision;
use App\Models\uploaded_documnts;
use App\Notifications\InformationNotification;
use App\Exceptions\MainTaskNotInsertedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use PDF;
use App\Models\VariationDecision;

class MeetingController extends Controller
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
        $meetings = Meeting::where('supervisor_id', auth()->user()->id)
            ->select('meetings.*')
            ->orderByDesc('meetings.meeting_date')
            ->get();

        return view('meeting.index', ['meetings' => $meetings]);

    }

    public function perc_meeting_index()
    {

        $meetings =  DB::table('meetings')
            ->orderByDesc('meetings.meeting_date')
            ->get();


        return view('PERC.index', ['meetings' => $meetings]);
    }

    public function invitation_details($id)
    {


        $meeting = Meeting::where('meetings.id', $id)
            ->leftjoin('uploaded_documents as minutes', 'minutes.id', 'meetings.minutes_id')
            ->leftjoin('uploaded_documents as invitation_documents', 'invitation_documents.id', 'meetings.invitation_document_id')
            ->select('meetings.*', 'minutes.path', 'invitation_documents.path as invitation_document_path')
            ->first();


        $decisions = Decision::where('meeting_id', $id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->select('decisions.*', 'dossier_assignments.id as dossier_assign_id', 'users.first_name', 'users.middle_name', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name'
                , 'applications.application_type', 'applications.application_number')
            ->distinct('decisions.id')
            ->get();

        $variation_decisions = VariationDecision::where('meeting_id', $id)
            ->join('variations', 'variations.id', 'variation_decisions.variation_id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->select('variations.*', 'certifications.registration_number', 'certifications.certificate_number', 'variations.id as var_id', 'users.first_name', 'users.middle_name')
            ->distinct('variations.id')
            ->get();


        $percs = DB::table('decision_participants')->join('users', 'users.id', 'decision_participants.committee_id')
            ->where('meeting_id', $id)
            ->get();


        return view('PERC.details', ['meeting' => $meeting, 'percs' => $percs, 'decisions' => $decisions, 'variation_decisions' => $variation_decisions]);

    }

    public function decision_meeting()
    {

        $drugs_for_registration = dossier_assignment::join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->where('main_tasks.task_status', 'queued')
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
                'medicinal_products.product_trade_name as brand_name',
                'medicines.product_name as product_trade_name'
            )
            ->get();
        $percs = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'PERC')
            ->select('users.*')
            ->get();
        $template = DB::table('templates')->where('id', 12)->first();
        $dossier_evaluation_details = [];
        return view($template->path, ['template' => $template, 'percs' => $percs, 'drugs_for_registration' => $drugs_for_registration]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $completed_assessment_assignments = dossier_assignment:://where('dossier_assignments.locked',1)->
        join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('main_tasks.task_status', 'completed')
            ->orwhere('main_tasks.task_status', 'queued')
            ->select('dossier_assignments.*', 'users.first_name', 'users.middle_name',
                'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status',
                'medicinal_products.product_trade_name', 'company_suppliers.trade_name',
                'applications.application_number', 'dossiers.dossier_ref_num')
            ->distinct('dossier_assignments.id')
            ->get();


        return view('/meeting/create', ['completed_assessment_assignments' => $completed_assessment_assignments]);
    }

    public function create_other_meeting()
    {
        //
        $variations = Variation::join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->join('users', 'users.id', 'variations.assessor_id')
            ->where('main_tasks.related_task', 'Variation')
            ->where('variations.status', 'Decision')
            ->where('variations.supervisor_id', auth()->user()->id)
            ->select('variations.*', 'users.first_name', 'main_tasks.task_status', 'company_suppliers.trade_name', 'medicinal_products.product_trade_name', 'users.last_name', 'certifications.registration_number', 'applications.application_id', 'applications.medical_product_id')
            ->get();

        return view('meeting.create_other_meeting', ['variations' => $variations]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $drugs_for_registration = dossier_assignment::join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->where('main_tasks.task_status', 'queued')
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
                'medicinal_products.product_trade_name as brand_name',
                'medicines.product_name as product_trade_name'
            )
            ->get();
        $percs = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'PERC')
            ->select('users.*')
            ->get();
        $template = DB::table('templates')->where('id', 12)->first();
        $dossier_evaluation_details = [];


//
        $data = [];
        $data['venue'] = $request->input('venue');
        $data['time'] = $request->input('time');

        $meeting_date = Carbon::createFromFormat('m/d/Y', $request->input('meeting_date'));

        $data['meeting_date'] = $meeting_date->format('d/m/Y');

        $data['description'] = $request->input('description');
        $date = date('d/m/Y');

        $letterReferenceNumber = new letterReferenceNumberGenerator();
        $reference_letter = $letterReferenceNumber->generate_letter_reference_number();

        return view($template->path, ['template' => $template, 'percs' => $percs, 'reference_letter' => $reference_letter,
            'drugs_for_registration' => $drugs_for_registration, 'data' => $data, 'date' => $date]);

    }

    public function other_meeting_store(Request $request)
    {
        //

        $percs = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'PERC')
            ->select('users.*')
            ->get();
        $template = DB::table('templates')->where('id', 13)->first();
        $dossier_evaluation_details = [];


//
        $data = [];
        $data['venue'] = $request->input('venue');
        $data['time'] = $request->input('time');
        $meeting_date = Carbon::createFromFormat('m/d/Y', $request->input('meeting_date'));
        $data['meeting_date'] = $meeting_date->format('d/m/Y');
        $data['description'] = $request->input('description');
        $date = date('d/m/Y');

        $letterReferenceNumber = new letterReferenceNumberGenerator();
        $reference_letter = $letterReferenceNumber->generate_letter_reference_number();

        return view($template->path, ['template' => $template, 'percs' => $percs, 'data' => $data, 'date' => $date, 'reference_letter' => $reference_letter]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_meeting(Request $request)
    {


        try {
            $meeting_id = $request->input('meeting_id');


            $file = $request->file('minutes');
            $filename = time() . '_' . $file->getClientOriginalName();

            $dir = 'documents/uploads';
            $path = $dir . '/' . $filename;

            $file->move($dir, $filename);
            $uploaded_document = new uploaded_documents;
            $uploaded_document->related_id = $meeting_id;
            $uploaded_document->name = $filename;
            $uploaded_document->path = $path;
            $uploaded_document->document_type = 23; //meeting document id
            $uploaded_document->description = 'Meeting minute';
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
            DB::transaction(function () use ($pdf_generated_uploaded_id, $path, $request, $meeting_id) {


                //
//check if the all option is checked or not
                $participants = $request->input('participants');
                $presented_committee = [];
                // dd($participants);
                $all = false;
                foreach ($participants as $participant) {
                    // dd($participant);
                    if ($participant == 'All') {
                        $all = true;
                        break;
                    }
                    $presented_committee[] = $participant;

                }
                //if all the participants
                if ($all) {
                    $percs = DB::table('roles')
                        ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                        ->join('users', 'users.id', 'model_has_roles.model_id')
                        ->where('roles.name', 'PERC')
                        ->select('users.*')
                        ->get();
                    foreach ($percs as $perc) {
                        DB::table('decision_participants')->insert(
                            [
                                'committee_id' => $perc->id,
                                'meeting_id' => $meeting_id
                            ]);
                    }

                } else {
                    foreach ($presented_committee as $participant) {
                        DB::table('decision_participants')->insert(
                            [
                                'committee_id' => $participant,
                                'meeting_id' => $meeting_id
                            ]);
                    }
                }
                Meeting::where('id', $meeting_id)
                    ->update([
                        'minutes_id' => $pdf_generated_uploaded_id,
                        'done' => true
                    ]);


            }); // end transaction

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect()->back()->with('success', 'Meeting Detailes Uploaded Successfully.');

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

    public function perc_decision_invitation(Request $request)
    {

        try {
            // handle transactions automatically
            DB::transaction(function () use ($request) {
                $venue = $request->input('venue');
                $time = $request->input('time');

                $decision_date = Carbon::createFromFormat('d/m/Y', $request->input('decision_date'));

                $meeting_description = $request->input('description');
                $data_from_textarea = $request->input('data');

                $uploaded_date = date('d-M-Y');

                //  This is to show the document in the activities
                $data = '';
                $data .= '

                <div class="form-group" >
      <h3 align="center"><b>PERC Meeting Invitation Letter</b></h3>
       <label>Date: </label>' . $uploaded_date .
                    '<br/>
       <label>To: </label>
                All PERC committee
                </div>
                ';


                $data .= $data_from_textarea;


                //this for the file name
                $upload_date = date('Y-m-s-H-m-s');
                $dir = 'documents/uploads/';
                $file_name = 'PERC_Invitation_letter_all.pdf';
                $uploaded_file_name = $upload_date . $file_name;

                $document = new PDFF([
                        'mode' => "utf-8",
                        'format' => "A4",
                        'margin_header' => "10",
                        'margin_top' => "30",
                        'margin_bottom' => "20",
                        'margin_footer' => "2",
                    ]
                );

                $header = [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

                //$document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
                //$document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');


                $document->WriteHTML($data);


                Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));

                $path = $dir . $uploaded_file_name;
                $description = 'The product decision date has been set to ' . $decision_date . '.';

                $uploaded_document = new uploaded_documents;
                $uploaded_document->related_id = 0;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Invitation to Register Drug';
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 12; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                $end_time = null;
                $task_category = 'Message';
                $task_activity_title = 'Invitation for Registration Decision';
                $content_details = $description;
                $route_link = '';
                $activity_status = 'queued';
                $issued_datetime = date('Y-m-d H:I:s');

//this is to update the task activity for all the queued dosseirs supvised by the current user
                $evaluated_dossiers = dossier_assignment::join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
                    ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                    ->where('dossier_assignments.supervisor_id', auth()->user()->id)
                    ->where('main_tasks.task_status', 'queued')
                    ->where('main_tasks.related_task', 'Dossier Evaluation')
                    ->select('dossier_assignments.id')
                    ->get();


                $pdf_generated_uploaded_id = $uploaded_document->id;
                $new_meeting = new Meeting();
                $new_meeting->type = 'Decision_Meeting';
                //$new_meeting->meeting_date = Carbon::create($decision_date)->format('Y-m-d H:i:s');
                $new_meeting->meeting_date = $decision_date;

                $new_meeting->description = $meeting_description;
                $new_meeting->invitation_document_id = $pdf_generated_uploaded_id;
                $new_meeting->venue = $venue;
                $new_meeting->time = $time;
                $new_meeting->supervisor_id = auth()->user()->id;
                $new_meeting->save();
                foreach ($evaluated_dossiers as $dossier_assign) {
                    $main_task = $this->get_main_task_id($dossier_assign->id, 'Dossier Evaluation');
                    MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                    MainTask::where('id', $main_task->id)->update(
                        [
                            'task_status' => 'Decision'
                        ]
                    );
                    Decision::insert(
                        [
                            'meeting_id' => $new_meeting->id,
                            'dossier_assignment_id' => $dossier_assign->id,


                        ]
                    );

                }


//this code is send invitiation to all perc committees individually
                $percs = DB::table('roles')
                    ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                    ->join('users', 'users.id', 'model_has_roles.model_id')
                    ->where('roles.name', 'PERC')
                    ->select('users.*')
                    ->get();

                foreach ($percs as $perc) {
                    $data = '';
                    $data .= '<div class="form-group" >
    <label>Date:</label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                           ' . $uploaded_date . '
                            </span>
                            
    </div>
    <br>
    <div class="form-group" >
    <label>Ref:</label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                 NMFA/
                                </span>
    </div>
    <br>
    <div class="form-group" >
    <label>To: ' . $perc->first_name . ' ' . $perc->middle_name . '</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Profession]<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $perc->addressline_one . '<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $perc->email . '
    </div>
    ';

                    $data .= $data_from_textarea;


                    //this for the file name
                    $upload_date = date('Y-m-s-H-m-s ');
                    $dir = 'documents/uploads/';
                    $file_name = 'PERC_Invitarion_letter_' . $perc->id . '.pdf';
                    $uploaded_file_name = $upload_date . $file_name;

                    $document = new PDFF([
                            'mode' => "utf-8",
                            'format' => "A4",
                            'margin_header' => "10",
                            'margin_top' => "50",
                            'margin_bottom' => "20",
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


                    //todo what shall we make this related_id
                    $uploaded_document = new uploaded_documents;
                    $uploaded_document->related_id = 0;
                    $uploaded_document->ref_num = '';
                    $uploaded_document->name = 'Invitation pdf to ' . $perc->first_name;
                    $uploaded_document->path = $path;
                    $uploaded_document->document_type = 12; //TODO fetch from document_type
                    $uploaded_document->description = $description;
                    // insert records
                    $uploaded_document->save();

                    //instert the variables above to the queries table
                    //insert this into task tracker
                    $new_notification = [];
                    $new_notification['type'] = 'Notification';
                    $new_notification['data'] = $description;
                    $new_notification['subject'] = 'Decision Meeting Invitation';
                    $new_notification['alert_level'] = '';
                    $new_notification['related_document'] = $uploaded_document->id;
                    $new_notification['related_id'] = $new_meeting->id;
                    $new_notification['remark'] = '';
                    $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;


                    $user = User::find($perc->id);
                    Notification::send($user, new InformationNotification($new_notification));

                    event(new DossierAssignmentEvent($perc->id, 'Meeting Invitation for Product Registration'));


                }

            }); // end transaction

        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect('/Meetings')->with('success', 'Invitation Sent to PERC.');


    }


    public function other_invitation(Request $request)
    {


        try {
            // handle transactions automatically
            DB::transaction(function () use ($request) {


                $venue = $request->input('venue');
                $time = $request->input('time');
                $decision_date = $request->input('decision_date');
                $decision_date = Carbon::createFromFormat('d/m/Y', $decision_date);

                $meeting_description = $request->input('description');
                $data_from_textarea = $request->input('data');

                $uploaded_date = date('d-M-Y');

                //  This is to show the document in the activities
                $data = '';
                $data .= '
     
      <div class="form-group" >
      <h3 align="center"><b>PERC Meeting Invitation Letter</b></h3>
       <label>Date: </label>' . $uploaded_date .
                    '<br/>
       <label>To: </label>
      All PERC committee
      </div>
      ';

                $data .= $data_from_textarea;


                //this for the file name
                $upload_date = date('Y-m-s-H-m-s');
                $dir = 'documents/uploads/';
                $file_name = 'PERC_Invitation_other_issues_letter_all.pdf';
                $uploaded_file_name = $upload_date . $file_name;

                $document = new PDFF([
                        'mode' => "utf-8",
                        'format' => "A4",
                        'margin_header' => "10",
                        'margin_top' => "30",
                        'margin_bottom' => "20",
                        'margin_footer' => "2",
                    ]
                );

                $header = [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline: filename="' . $uploaded_file_name . '"'];

                //$document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
                //$document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');


                $document->WriteHTML($data);


                Storage::disk('documents')->put($uploaded_file_name, $document->Output($uploaded_file_name, "S"));

                $path = $dir . $uploaded_file_name;
                $description = 'Meeting date has been set.';

                $uploaded_document = new uploaded_documents;
                $uploaded_document->related_id = 0;
                $uploaded_document->ref_num = '';
                $uploaded_document->name = 'Meeting Invitation';
                $uploaded_document->path = $path;
                $uploaded_document->document_type = 12; //TODO fetch from document_type
                $uploaded_document->description = $description;
                // insert records
                $uploaded_document->save();

                $end_time = null;
                $task_category = 'Message';
                $task_activity_title = 'Variations Meeting';
                $content_details = $description;
                $route_link = '';
                $activity_status = 'queued';
                $issued_datetime = date('Y-m-d H:i:s');


                $pdf_generated_uploaded_id = $uploaded_document->id;
                $new_meeting = new Meeting();
                $new_meeting->type = 'Other_Meeting';
                $new_meeting->meeting_date = $decision_date;
                $new_meeting->description = $meeting_description;
                $new_meeting->invitation_document_id = $pdf_generated_uploaded_id;
                $new_meeting->venue = $venue;
                $new_meeting->time = $time;
                $new_meeting->supervisor_id = auth()->user()->id;
                $new_meeting->save();


                $variations = Variation::join('users as supervisors', 'supervisors.id', 'variations.supervisor_id')
                    ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
                    ->where('variations.supervisor_id', auth()->user()->id)
                    ->where('main_tasks.task_status', 'queued')
                    ->where('main_tasks.related_task', 'Variation')
                    ->select('variations.id')
                    ->get();


                foreach ($variations as $variation) {
                    $main_task = $this->get_main_task_id($variation->id, 'Variation');
                    MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $pdf_generated_uploaded_id);
                    MainTask::where('id', $main_task->id)->update(
                        [
                            'task_status' => 'Decision'
                        ]
                    );
                    VariationDecision::insert(
                        [
                            'meeting_id' => $new_meeting->id,
                            'variation_id' => $variation->id,


                        ]
                    );

                }


//this code is send invitiation to all perc committees individually
                $percs = DB::table('roles')
                    ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                    ->join('users', 'users.id', 'model_has_roles.model_id')
                    ->where('roles.name', 'PERC')
                    ->select('users.*')
                    ->get();

                foreach ($percs as $perc) {
                    $data = '';

                    $data .= '<div class="form-group" >
    <label>Date:</label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                           ' . $uploaded_date . '
                            </span>
                            
    </div>
    <br>
    <div class="form-group" >
    <label>Ref:</label>
                            <span style="color:#2E74B5;mso-themecolor:accent1;mso-themeshade:191">
                                 NMFA/
                                </span>
    </div>
    <br>
    <div class="form-group" >
    <label>To: ' . $perc->first_name . ' ' . $perc->middle_name . '</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Profession]<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $perc->addressline_one . '<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $perc->email . '
    </div>
    ';

                    $data .= $data_from_textarea;


                    //this for the file name
                    $upload_date = date('Y-m-s-H-m-s ');
                    $dir = 'documents/uploads/';
                    $file_name = 'PERC_Invitation_other_letter_' . $perc->id . '.pdf';
                    $uploaded_file_name = $upload_date . $file_name;

                    $document = new PDFF([
                            'mode' => "utf-8",
                            'format' => "A4",
                            'margin_header' => "10",
                            'margin_top' => "50",
                            'margin_bottom' => "20",
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


                    //what shall we make this related_id
                    $uploaded_document = new uploaded_documents;
                    $uploaded_document->related_id = 0;
                    $uploaded_document->ref_num = '';
                    $uploaded_document->name = 'Invitation pdf to ' . $perc->first_name;
                    $uploaded_document->path = $path;
                    $uploaded_document->document_type = 12; //TODO fetch from document_type
                    $uploaded_document->description = $description;
                    // insert records
                    $uploaded_document->save();

                    //instert the variables above to the queries table
                    //insert this into task tracker
                    $new_notification = [];
                    $new_notification['type'] = 'Notification';
                    $new_notification['data'] = $description;
                    $new_notification['subject'] = 'General Meeting Invitation';
                    $new_notification['alert_level'] = '';
                    $new_notification['related_document'] = $uploaded_document->id;
                    $new_notification['related_id'] = $new_meeting->id;
                    $new_notification['remark'] = '';
                    $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;


                    $user = User::find($perc->id);
                    Notification::send($user, new InformationNotification($new_notification));

                    event(new DossierAssignmentEvent($perc->id, 'General Meeting Invitation '));


                }

            });


        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }
        return Redirect('/Meetings')->with('success', 'Invitation Sent to PERC.');

    }

    public function upload_meeting_details($id)
    {

        $meeting = Meeting::where('meetings.id', $id)
            ->leftjoin('uploaded_documents as minutes', 'minutes.id', 'meetings.minutes_id')
            ->leftjoin('uploaded_documents as invitation_documents', 'invitation_documents.id', 'meetings.invitation_document_id')
            ->select('meetings.*', 'minutes.path', 'invitation_documents.path as invitation_document_path')
            ->first();

        $decisions = Decision::where('meeting_id', $id)
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->select('decisions.*', 'users.first_name', 'users.middle_name', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name')
            ->distinct('decisions.id')
            ->get();

        $variation_decisions = VariationDecision::where('variation_decisions.meeting_id', $id)
            ->join('variations', 'variations.id', 'variation_decisions.variation_id')
            ->join('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->select('variation_decisions.*', 'users.first_name', 'users.middle_name', 'medicinal_products.product_trade_name', 'company_suppliers.trade_name')
            ->distinct('variation_decisions.id')
            ->get();
        if ($meeting->minutes_id == null) {
            $percs = DB::table('roles')
                ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                ->join('users', 'users.id', 'model_has_roles.model_id')
                ->where('roles.name', 'PERC')
                ->select('users.*')
                ->distinct('users.id')
                ->get();
        } else {
            $percs = DB::table('decision_participants')->join('users', 'users.id', 'decision_participants.committee_id')
                ->where('meeting_id', $id)
                ->get();
        }

        $date = date('d-M-Y');

        return view('meeting.edit', ['meeting' => $meeting, 'decisions' => $decisions, 'variation_decisions' => $variation_decisions, 'percs' => $percs, 'date' => $date]);
    }

    public function retrive_application_information(Request $request)
    {
        try {

            $id = $request->id;

            $type = $request->decision_type;

            if ($type == 'variation') {

                $dossier_evaluation_details = Variation::where('variations.id', $id)
                    ->leftjoin('variation_decisions', 'variation_decisions.variation_id', 'variations.id')
                    ->leftjoin('meetings', 'meetings.id', 'variation_decisions.meeting_id')
                    ->join('certifications', 'certifications.id', 'variations.certificate_id')
                    ->join('decisions', 'decisions.id', 'certifications.decision_id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                    ->leftjoin('users as assessors', 'assessors.id', 'variations.assessor_id')
                    ->join('users as supervisors', 'supervisors.id', 'variations.supervisor_id')
                    ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->join('users as applicant', 'applicant.id', 'applications.user_id')
                    ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                    ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                    ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
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
                        'medicines.product_name',
                        'dosage_forms.name as dosage_name',
                        'variations.created_at',
                        'applications.progress_percentage',
                        'applications.application_type',
                        'applications.application_number',
                        'company_suppliers.trade_name as company_name',
                        'company_suppliers.city',
                        'company_suppliers.state',
                        'company_suppliers.address_line_one',
                        'countries.country_name',
                        /* 'applicant.first_name as applicant_first_name',
                         'applicant.middle_name  as applicant_middle_name',*/
                        'contacts.first_name as contact_first_name',
                        'contacts.last_name  as contact_last_name',
                        'route_administrations.name as route_administration_name',
                        'dosage_forms.name as dosage_form_name',
                        'variations.id as variation_id',
                        'variations.variation_reference_number'
                    )
                    ->where('contact_type', 'Supplier')
                    ->first();


            } else {

                $decision = Decision::find($id);
                $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $decision->dossier_assignment_id)
                    ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                    ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
                    ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
                    ->join('users as applicant', 'applicant.id', 'applications.user_id')
                    ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                    ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
                    ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
                    ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
                    ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                    ->join('countries', 'countries.id', 'company_suppliers.country_id')
                    ->join('contacts', 'applications.application_id', 'contacts.application_id')
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
                        'medicines.product_name as generic_name',
                        'dosage_forms.name as dosage_name',
                        'applications.created_at',
                        'applications.progress_percentage',
                        'applications.application_type',
                        'applications.fast_track_details',
                        'applications.application_number',
                        'company_suppliers.trade_name as company_name',
                        'company_suppliers.city',
                        'company_suppliers.state',
                        'company_suppliers.address_line_one',
                        'countries.country_name',
                        'contacts.first_name as contact_first_name',
                        'contacts.last_name  as contact_last_name',
                        'route_administrations.name as route_administration_name',
                        'dosage_forms.name as dosage_form_name'
                    )
                    ->where('contact_type', 'Supplier')
                    ->first();
            }


            //dd($dossier_evaluation_details);

            $applied_date = $dossier_evaluation_details->created_at;

            $converted_date = $applied_date->format('d/m/Y');

            $date = Carbon::now()->format('d/m/Y');


            $letterReferenceNumber = new letterReferenceNumberGenerator();
            $reference_letter = $letterReferenceNumber->generate_letter_reference_number();
            return response()->json(['data' => $dossier_evaluation_details, 'created_at' => $converted_date, 'date' => $date, 'reference_letter' => $reference_letter]);


        } catch (\Exception $e) {
            return response()->json(['queued' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['queued' => false, 'item' => 'item_success']);


    }


    public function product_decision(Request $request)
    {

        try {


            $id = $request->id;
            $type = $request->type;
            $decision = $request->decision;
            $decision_status = "";
            $decision_date = Carbon::now();


            if ($decision == 'accept') {
                $decision_status = 'Accepted';
            } elseif ($decision == 'defer') {
                $decision_status = 'Deferred';

            } elseif ($decision == 'reject') {
                $decision_status = 'Rejected';

            } else {

            }

            if ($type == 'variation') {


                $decision = VariationDecision::find($id);
                if ($decision->decision_status == null) {


                    VariationDecision::where('id', $id)->update(
                        [
                            'decision_status' => $decision_status,
                            'decision_date' => $decision_date
                        ]
                    );

                } else {

                    VariationDecision::where('id', $id)->update(
                        [
                            'decision_status' => $decision_status,
                        ]
                    );

                }

                //updated decision
                $decision = VariationDecision::find($id);
                Variation::where('id', $decision->variation_id)->update(
                    [
                        'status' => 'Decided',
                    ]
                );

            } else {

                $decision = Decision::find($id);
                if ($decision->decision_status == null) {


                    Decision::where('id', $id)->update(
                        [
                            'decision_status' => $decision_status,
                            'decision_date' => $decision_date
                        ]
                    );

                } else {


                    //Merhawi Hungary

// kulu kem zelewo koiynu ezia tray paste bela ika also  AppSetting import gbero

//Hungary.COm this is the code i added this is the rollback


                    $decision = Decision::find($id);

                    uploaded_documents::where('related_id', $id)->where('document_type', 25)->delete();


                    $deadline = null;


                    if ($decision->decision_status == 'Accepted') {

                        $generated_certificate = DB::table('certifications')->where('decision_id', $id)->first();


                        if ($generated_certificate != null) {
                            $year_setting = AppSetting::where('name', 'current_year')->first();
                            $year_in_db = $year_setting->value;
                            $year_from_system = date('Y');

                            if ($year_in_db == $year_from_system) {
                                $product_setting = AppSetting::where('name', 'product_registration_counter')->first();
                                $count = $product_setting->value;
                                //decrement the counter
                                AppSetting::where('name', 'product_registration_counter')->update(
                                    [
                                        'value' => $count - 1,
                                    ]
                                );

                            } else {


                            }


                            $deleted = DB::table('certifications')->where('decision_id', $id)->delete();
                        }
                    }


                    Decision::where('id', $id)->update(
                        [
                            'downloaded_document_id' => null,
                            'decision_letter_downloaded' => 0,
                            'downoloded_date' => null,
                            'deferred_date' => $deadline
                        ]);


// END OF Hungary.COm this is the code i added


                    Decision::where('id', $id)->update(
                        [
                            'decision_status' => $decision_status,
                        ]
                    );

                }

                //updated decision
                $decision = Decision::find($id);

            }
            $now = \Carbon\Carbon::now();
            $decision_date = \Carbon\Carbon::create($decision->decision_date);
            $edit_decision_hrs_counter = $decision_date->diffInHours($now, false);
            $diff_displayed = $decision_date->diff($now)->format('%dd:%Hhr:%I:%S');


            return response()->json(['decision' => $decision,
                'edit_decision_hrs_counter' => $edit_decision_hrs_counter, 'diff_displayed' => $diff_displayed]);


        } catch (\Exception $e) {
            return response()->json(['queued' => $e, 'item' => 'error' . $e]);
        }
        return response()->json(['queued' => false, 'item' => 'item_success']);


    }

    public function postpone_meeting(Request $request)
    {
        $meeting_id = $request->input('meeting_id');
        $postpone_reason = $request->input('postpone_reason');
        $postpone_date = $request->input('postpone_date');
        $postpone_time = $request->input('postpone_time');
        Meeting::where('id', $meeting_id)
            ->update
            ([
                'postponed' => 1,
                'postponed_reason' => $postpone_reason,
                'postponed_date' => $postpone_date,
                'postponed_time' => $postpone_time
            ]);
        $percs = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'PERC')
            ->select('users.*')
            ->get();
        $new_notification = [];
        $new_notification['type'] = 'Notification';
        $new_notification['data'] = 'PERC Meeting Postponed to ' . $postpone_date . ' at ' . $postpone_time . ' Reason: ' . $postpone_reason;
        $new_notification['subject'] = 'Meeting Postponed';
        $new_notification['alert_level'] = '';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $meeting_id;
        $new_notification['remark'] = '';
        $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
        foreach ($percs as $perc) {


            $user = User::find($perc->id);
            Notification::send($user, new InformationNotification($new_notification));

            event(new DossierAssignmentEvent($perc->id, 'Meeting Postponed '));
        }

        return redirect('/Meetings')->with('success', 'Meeting Date Postponed Successfully.');

    }

}
