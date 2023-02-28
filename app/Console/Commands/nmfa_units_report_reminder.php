<?php

namespace App\Console\Commands;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Models\dossier_status_lookup;
use App\Models\User;
use App\Models\dossier_section_assignment;
use App\Notifications\RemindersNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\dossier;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class nmfa_units_report_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:nmfa_units_report_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder for report submissions assigned to NMFA units/PERC';

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
     *
     * @return int
     */
    public function handle()
    {

        $assignments = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('dossier_section_assignments', 'dossier_assignments.id', 'dossier_section_assignments.section_related_id')
            ->where('dossier_status_lookups.status', '!=', 'Completed')  //and
            ->where('dossier_status_lookups.status', '!=', 'Queued')
            ->select([
                'dossiers.dossier_ref_num',
                'dossier_assignments.id as dossier_assign_id',
                'dossier_section_assignments.id',
                'dossier_section_assignments.status',
                'dossier_section_assignments.section_related_id',
                'dossier_section_assignments.section_sent_date as sent_date',
                'dossier_section_assignments.section_received_date as received_date',
                'dossier_section_assignments.section_to_user_id as to_user',
                'dossier_section_assignments.section_from_user_id as from_user',
                'dossier_section_assignments.section_deadline as deadline'
            ])
            ->get();

        $reminder_limit = Config::get('site_vars.nmfa_units_remind_before_days');

        foreach ($assignments as $assignment) {


            $deadline = Carbon::Create($assignment->deadline);
            //$sent_date = Carbon::Create($assignment->sent_date);
            $diff_in_days = $deadline->diffInDays(Carbon::now());

            // send reminder if report is not received yet and
            // remaining days are <=  $reminder_limit but > 0
            if ($assignment->received_date == null) {

                // remind daily from 11 to 1 days and lock at day 0
                if ($diff_in_days <= $reminder_limit + 1 and $diff_in_days > 0 and $assignment->status=='Assigned') {

                    $message = "Reminder for Dossier: " . $assignment->dossier_ref_num . ". " . $diff_in_days . " days remaining to complete task.";
                    $subject = 'Deadline Reminder';
                    $user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                } elseif ($diff_in_days == 0) { //expired but report is still not uploaded

                    // on expire, disable report uploading (user has to request extension)
                    dossier_section_assignment::where('id', $assignment->id)->update([
                        'status' => 'Locked'
                    ]);

                    // notify NMFA units to request extension after expiry
                    $message = "Notification for Dossier: " . $assignment->dossier_ref_num .
                        ". The deadline for report submission has expired on " . $deadline->toDateString() .
                        ". The option to submit report has been disabled. Please request extension to enable the submission.";
                    $subject = 'Deadline Expired';
                    $user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                    // notify assessor that it is locked
                    $message = "Notification for Dossier: " . $assignment->dossier_ref_num .
                        ". The deadline for dossier section report submission has expired on " . $deadline->toDateString() .
                        ". The Upload option for the respective Unit has been locked.";
                    $subject = 'Expiry Notification Sent';
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
        $message = $new_notification['type'].':'.$subject;
        event(new DossierEvaluationRemindersEvent($user->id, $message));

    }
}
