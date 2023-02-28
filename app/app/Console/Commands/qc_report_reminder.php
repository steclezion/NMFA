<?php

namespace App\Console\Commands;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Models\dossier_status_lookup;
use App\Models\QualityControl;
use App\Models\User;
use App\Models\dossier_section_assignment;
use App\Notifications\RemindersNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\dossier;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class qc_report_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:qc_report_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind QC unit in 1/3 and 2/3 of total time for report
                                submissions assigned to QC unit for sample test';

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

        // fetch details of tasks assigned to QC
        $assignments = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('quality_controls', 'dossier_assignments.id', 'quality_controls.qc_related_id')
            ->where('dossier_status_lookups.status', '!=', 'Completed')  //and
            ->where('dossier_status_lookups.status', '!=', 'Queued')
            ->where('quality_controls.qc_received_date', null)
            ->where('quality_controls.status', '!=', 'Locked')
            ->select([
                'dossiers.dossier_ref_num',
                'dossier_assignments.id as dossier_assign_id',
                'quality_controls.id as qc_id',
                'quality_controls.status',
                'quality_controls.qc_related_id',
                'quality_controls.to_qc_sent_date as sent_date',
                'quality_controls.qc_received_date as received_date',
                'quality_controls.to_qc_staff_id as to_user', // deadline reminder will be sent to this qc account
                'quality_controls.inspection_to_user_id as from_user',
                'quality_controls.qc_deadline as deadline'
            ])
            ->get();


        // first reminder 1/3 of total time
        $first_reminder_config = Config::get('site_vars.qc_report_first_reminder');
        // second reminder 2/3 of total time
        $second_reminder_config = Config::get('site_vars.qc_report_second_reminder');

        foreach ($assignments as $assignment) {


            $deadline = Carbon::Create($assignment->deadline);
            $sent_date = Carbon::Create($assignment->sent_date);
            $total_days = $deadline->diffInDays($sent_date);
            $first_reminder_day = round($total_days * $first_reminder_config);
            $second_reminder_day = round($total_days * $second_reminder_config);

            $elapsed_days = $sent_date->diffInDays(Carbon::now());
            $diff_in_days = $total_days - $elapsed_days;

            $message = "Reminder for Dossier: " . $assignment->dossier_ref_num . ". "
                . $diff_in_days . " days remaining to complete sample test.";

            if ($assignment->received_date == null) {
                if ($first_reminder_day == $elapsed_days) {

                    // save reminder details (as notification) and fire the reminder
                    $subject = 'Deadline Reminder';
                    $user = User::find($assignment->to_user);
                    $this->remind_and_save_details($message, $assignment, $subject, $user);
                } elseif ($second_reminder_day == $elapsed_days) {

                    // save reminder details (as notification) and fire the reminder
                    $subject = 'Deadline Reminder';
                    $user = User::find($assignment->to_user);
                    $this->remind_and_save_details($message, $assignment, $subject, $user);
                } elseif ($diff_in_days <= -1 and $assignment->status != 'Locked') {  //expired but report is still not uploaded

                    // on expire, disable report uploading (user has to request extension)
                    QualityControl::where('id', $assignment->qc_id)->update([
                        'status' => 'Locked'
                    ]);

                    // notify QC unit to request extension after expiry
                    $message = "Notification for Dossier: " . $assignment->dossier_ref_num .
                        ". The deadline for sample test report submission has expired on " . $deadline->toDateString() .
                        ". The option to submit report has been disabled. Please request extension to enable the submission.";
                    $subject = 'Deadline Expired';
                    $user = User::find($assignment->to_user);
                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                    // notify assessor that it is locked
                    $message = "Notification for Dossier: " . $assignment->dossier_ref_num .
                        ". The deadline for sample test report submission has expired on " . $deadline->toDateString() .
                        ". The Upload option for the respective Unit has been locked.";
                    $subject = 'Expiry Notification Sent';
                    $user = User::find($assignment->from_user);
                    $this->remind_and_save_details($message, $assignment, $subject, $user);

                } else {

                }

            } //end if ($assignment->received_date == null)


        }


    }


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

