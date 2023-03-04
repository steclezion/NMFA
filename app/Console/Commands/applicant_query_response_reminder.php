<?php

namespace App\Console\Commands;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Models\dossier;
use App\Models\queries;
use App\Models\User;
use App\Notifications\RemindersNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class applicant_query_response_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:applicant_query_response_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind applicant to submit query response 5 days prior to deadline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {

        $assignments = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('queries', 'dossier_assignments.id', 'queries.query_related_id')
            ->where('dossier_status_lookups.status', '!=', 'Completed')  //and
            ->where('dossier_status_lookups.status', '!=', 'Queued')
            ->select([
                'dossiers.dossier_ref_num',
                'dossier_assignments.id as dossier_assign_id',
                'queries.status',
                'queries.query_related_id',
                'queries.query_sent_date as sent_date',
                'queries.query_received_date as received_date',
                'queries.query_to_user_id as to_user', // applicant
                'queries.query_from_user_id as from_user',  //assessor
                'queries.query_deadline as deadline'
            ])
            ->get();

        $reminder_limit = Config::get('site_vars.applicant_query_remind_before_days');  //5 days

        foreach ($assignments as $assignment) {


            $deadline = Carbon::Create($assignment->deadline);
            $diff_in_days = $deadline->diffInDays(Carbon::now());

            // send reminder if applicant did not respond to the query yet and
            // remaining days are <=  $reminder_limit but > 0
            if ($assignment->received_date == null) {


                // remind daily from 6 to 1 days and lock at day 0
                if ($diff_in_days <= $reminder_limit + 1 and $diff_in_days > 0 and $assignment->status=='Query Issued') {

                    $message = "Query Response Reminder: " . $diff_in_days . " days remaining to respond to Query Sent on ". $assignment->sent_date;
                    $subject = 'Deadline Reminder';
                    $user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                } elseif ($diff_in_days == 0) { //expired but response is still not sent

                    // on expire, disable response uploading (applicant has to request extension)
                    queries::where('query_related_id', $assignment->dossier_assign_id)->update([
                        'status' => 'Locked'
                    ]);

                    // notify applicant to request extension after expiry
                    $message = "The deadline for query response has expired on " . $deadline->toDateString() .
                        ". The option to submit response has been disabled. Please request extension to enable the submission.";
                    $subject = 'Query Response Deadline Expired';
                    $user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                    // notify assessor that it is locked
                    $message = "Applicant's deadline for query response submission has expired on " . $deadline->toDateString() .
                        ". The Upload option for the applicant has been locked.";
                    $subject = 'Expiry Notification for Query Response';
                    $user = User::find($assignment->from_user);

                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                } else {
                    //do nothing
                }


            }//end if


        }


    } // end handle

    private function remind_and_save_details($message, $assignment, $subject, $user)
    {

        $new_notification = [];
        $new_notification['type'] = 'Reminder';
        $new_notification['subject'] = $subject;
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = $message;
        $new_notification['related_id'] = $assignment->dossier_assign_id;
        $new_notification['related_document'] = null;
        $new_notification['alert_level'] = null;

        Notification::send($user, new RemindersNotification($new_notification));

        event(new DossierEvaluationRemindersEvent($user->id, $message));
    }
}
