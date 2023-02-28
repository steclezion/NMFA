<?php

namespace App\Console\Commands;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Models\certification;
use App\Models\User;
use App\Notifications\RemindersNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class registration_expiry_notification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:registration_expiry_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify applicant and supervisor prior to product registration deadline';

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
        // every month --- read certified applications and check exipry
        // if 6 months left -- notifiy applicant
        //if 3 months left -- notifiy supervisor
        //if expired (and no re-registration application) lock registered application
        // (?? what to look in applicant side ? in assessor side // in supervisor side ?
        // what status to use because LOCKED is already present
        // notify applicant and supervisor - application locked

        $certified_applications = certification::join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->select('certifications.*', 'dossier_assignments.supervisor_id as supervisor_id',
                'dossier_assignments.id as dossier_assign_id',
                'applications.user_id as applicant_id')
            ->get();

        foreach ($certified_applications as $certified_application) {

            $certified_date = Carbon::create($certified_application->certified_date);
            $expiry_date = Carbon::create($certified_application->expiry_date);
            $now = Carbon::now();

            // In Carbon diff b/n 2022-01-01 and 2021-02-01 (one month range) returns 0.
            // so diff of 5 means 6 months to go
            $diff_in_months = $now->diffInMonths($expiry_date, false);
            $diff_in_days = $now->diffInDays($expiry_date, false);


            // remind applicant
            // every month starting from 6 months to 0 month
            if ($diff_in_months >= 0 and $diff_in_months < 7 and $certified_application->status !='reregistration_initiated') {


                //Open registration
                certification::where($certified_application->id)->update(
                    [
                        'status' => 'reregistration_open'
                    ]
                );

                // notify applicant
                $subject = 'Registration Expiry Reminder';
                $message = "The registration of Product --- expires on " . $certified_application->expiry_date .
                    ". To continue marketing the product, you need to re-register the product before the expiry date.";

                $user = User::find($certified_application->applicant_id);

                $this->remind_and_save_details($message, $certified_application, $subject, $user);


                // notify supervisor
                // every month starting from 4 months to 0 month
                if ($diff_in_months < 4) {  // 4 or 3 months to go (diff of 3, 2, 1, 0?)

                    $subject = 'Registration Expiry Reminder';
                    $message = "The registration of Product --- expires on " . $certified_application->expiry_date;

                    $user = User::find($certified_application->supervisor_id);

                    $this->remind_and_save_details($message, $certified_application, $subject, $user);

                }

            } elseif ($diff_in_months < 0 and $certified_application->status != 'reregistration_initiated') {

                if ($diff_in_months == -1) {
                    // one month past expiry date
                    // change re-registration status to expired
                    // (front end: Disable Re-register button 2. lock all processes in all releases related to this application)
                    certification::where($certified_application->id)->update(
                        [
                            'status' => 'reregistration_expired'  // lock process related to this application
                        ]
                    );

                    // notify applicant of the expiry status
                    $subject = 'Registration Expired';
                    $message = "The Registration Period of Product --- has expired.";

                    $user = User::find($certified_application->applicant_id);
                    $this->remind_and_save_details($message, $certified_application, $subject, $user);

                    // notify supervisor of the expiry status
                    $user = User::find($certified_application->supervisor_id);
                    $this->remind_and_save_details($message, $certified_application, $subject, $user);


                } elseif ($diff_in_months < -3) {

                    // 3 months past expiry
                    // check if applicant has requested renewal, if not requested  last 3 months - disable renewal chance.
                    // or applicant requested and was granted acceptance to renew but did not initiate registration within the 3 months
                    // change certification status to reregistration_expired


                    if (($certified_application->reregister_requested_deadline == null) or
                        ($certified_application->status != 'reregistration_initiated')) {

                        certification::where($certified_application->id)->update(
                            [
                                'status' => 'reregistration_expired'  // lock re-registration forever
                            ]
                        );


                        // notify applicant of the expiry status
                        $subject = 'Re-registration Period Expired';
                        $message = "The Second Registration Renewal Period of Product --- has expired.";

                        $user = User::find($certified_application->applicant_id);
                        $this->remind_and_save_details($message, $certified_application, $subject, $user);

                        // notify supervisor of the expiry status
                        $user = User::find($certified_application->supervisor_id);
                        $this->remind_and_save_details($message, $certified_application, $subject, $user);


                    }
                }


            } else{
                //do nothing
            }

        }

    } //end handle

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
