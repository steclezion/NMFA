<?php

namespace App\Http\Controllers;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\certification;
use App\Models\MainTask;
use App\Models\Meeting;
use App\Models\User;
use App\Notifications\InformationNotification;
use App\Notifications\RemindersNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReregistrationController extends Controller
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

    public function reregistration_deadline_extension_request(Request $request)
    {

        try {
            // handle transactions automatically
            DB::transaction(function () use ($request) {

                $certification_id = $request->input('certification_id');
                $deadline = $request->input('requested_deadline');
                $reason = $request->input('extension_request_reason');

                //update certification table with extension deadline request
                certification::where('id', $certification_id)->update(
                    [
                        'reregister_requested_deadline' => $deadline,
                        'reregister_request_reason' => $reason,
                        'status' => 'renewal_requested'
                    ]
                );

                //update activity for timeline
                $main_task = $this->get_main_task_id($certification_id, 'Certification');
                $issued_datetime = date('Y-m-d H:i:s');  // enter this as both starttime and endtime in task tracker
                $task_category = 'Deadline Extension Request';
                $task_activity_title = 'Reregistration Deadline Extension Request';

//            $requesting_applicant = User::find(auth()->user()->id);
                $content_details = 'Registration Renewal Request was sent by ' . auth()->user()->first_name . ' ' . auth()->user()->middle_name .
                    '. Extension Requested till: ' . $deadline . '. Reason: ' . $reason;
                $route_link = '';
                $activity_status = 'renewal_requested';
                $uploaded_document_id = null;

                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $issued_datetime,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
                }

                // send notification to supervisor
                $certification_details = certification::join('decisions', 'certifications.decision_id', 'decisions.id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->where('certifications.id', $certification_id)
                    ->select('dossier_assignments.supervisor_id')
                    ->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = $content_details;
                $new_notification['subject'] = 'Registration Renewal Request';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = null;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $certification_id;
                $new_notification['remark'] = '';
                $supervisor = User::find($certification_details->supervisor_id);

                Notification::send($supervisor, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($supervisor->id, $new_notification['subject']));

            });

            return Redirect()->back()->with('success', 'Extension Request Sent Successfully .');
        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Sending Extension Request Failed. '. $e->getMessage());
        }
    } // end method

    public function reregister_request_index(){

        $reregister_requested_certifications = Meeting::join('decisions', 'decisions.meeting_id', 'meetings.id')
            ->leftjoin('certifications', 'certifications.decision_id', 'decisions.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->where('certifications.reregister_requested_deadline', '!=', null)
            ->where('certifications.status', 'renewal_requested')
            ->where('meetings.supervisor_id', auth()->user()->id)
            ->select('company_suppliers.trade_name as company_name',
                'medicinal_products.product_trade_name',
                'certifications.id as certification_id',
                'certifications.*')
            ->get();

      return view('supervisor.reregister_request_index',
          ['reregister_requested_certifications' => $reregister_requested_certifications]);

    }

    public function update_renewal_deadline(Request $request){
        try {
            // handle transactions automatically
            DB::transaction(function () use ($request) {

                $certification_id = $request->input('hidden_certification_id');
                $new_deadline = date($request->input('new_deadline'). ' 23:59:59'); //time added to indicate end-of-day as deadline.
                $extend_reason = $request->input('extend_reason');

               certification::where('id', $certification_id)->update(
                   [
                       'reregister_extended_deadline' => $new_deadline,
                       'reregister_extended_desc' => $extend_reason,
                       'status' => 'renewal_request_accepted'
                   ]
               );


                // send notification to applicant
                $certification_details = certification::join('decisions', 'certifications.decision_id', 'decisions.id')
                    ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                    ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->where('certifications.id', $certification_id)
                    ->select('applications.user_id as applicant_id')
                    ->first();

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Your Registration Renewal Request has been Accepted. New Deadline is: '.$new_deadline;
                $new_notification['subject'] = 'Renewal Request Accepted';
                $new_notification['alert_level'] = '';
                $new_notification['related_document'] = null;
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['related_id'] = $certification_id;
                $new_notification['remark'] = '';
                $applicant = User::find($certification_details->applicant_id);

                Notification::send($applicant, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($applicant->id, $new_notification['subject']));



            }); //end transaction
            return Redirect()->back()->with('success', 'Deadline Extension Completed Successfully.');
        }catch (\Exception $e){

            return Redirect()->back()->with('danger', 'Deadline Extension Failed. '. $e->getMessage());
        }
    }




        // todo 2: write reminder to track renewal deadline using new_deadline then change status to 'reregistation_expired' if status is still
        // renewal_requested (and supervisor didnot reply for 3 months)  OR renewal_request_accepted, else (customer has re-registered do nothing)
        //todo 3: renewal allowed (max. 3 months)
        // decision of supervisor - allow/reject renewal request
        // disable other buttons when reregistration is in-progress (psur, ...etc)

} //end class
