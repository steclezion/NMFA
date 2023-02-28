<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\application_evaluation_progresses;
use App\Models\applications;
use App\Models\MainTasks;
use App\Models\TaskTracker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\ApplicationReceiptionEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationReceiptionNotification;
use App\Notifications\RemindersNotification;

class applicant_paymentsample_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:update_application_payment_daycount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Applicant  application payment  and sample deadline  counter';

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
        $application_tasks =DB::table('applications')
->join('main_tasks', 'applications.id', 'main_tasks.related_id')
 ->join('task_trackers', 'main_tasks.id', 'task_trackers.task_id')
 ->join('application_evaluation_progresses', 'task_trackers.id', 'application_evaluation_progresses.task_id')
->select('applications.id as app_id',
'applications.application_id as application_id',
'applications.user_id  as applicant',
'main_tasks.related_task as related_task',
'main_tasks.task_name as taskname',
'task_trackers.extention_days as extention_days',
'task_trackers.id as task_id',
'task_trackers.activity_status as task_status',
'task_trackers.task_category as task_category',
'task_trackers.end_time as deadline',
'application_evaluation_progresses.id as eval_progress_id',
'application_evaluation_progresses.day_count as day_count')
 ->get();

 //$reminder_limit = Config::get('site_vars.applicant_query_remind_before_days');

 $reminder_limit = 10;

 foreach ($application_tasks as $application_task ) {
    if ($application_task->task_status != "pause" ||  $application_task->task_status != "Locked") {

    $deadline = Carbon::Create( $application_task->deadline);
    $diff_in_days = $deadline->diffInDays(Carbon::now());

            // todo: next line is for testing - delete it
            //$diff_in_days = -1;

            // send reminder if applicant did not respond to the query yet and
            // remaining days are <=  $reminder_limit but >= 0


                if ($diff_in_days % 10 == 0  and $diff_in_days <= 20 and $diff_in_days > 0 ) {

                    $message = "Application payment reminder: " . $diff_in_days . " days remaining to respond to the query.";
                    $subject = 'Deadline Reminder';
                    $user=User::where('id',$application_task->applicant)->first();
                    //$user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $application_task, $subject, $user);

                } elseif ($diff_in_days == 0) { //expired but response is still not sent

                    // on expire, disable response uploading (applicant has to request extension)

                    task_trackers::where('task_id', $application_task->task_id)->update([
                        'activity_status' => 'Locked'    //todo mera: disable upload button for units
                    ]);

                    // notify applicant to request extension after expiry
                    $message = "The deadline for application  payment has expired on " . $deadline->toDateString() .
                        ". Your application hass locked. Please request extension to enable unlock the application.";
                    $subject = 'Deadline of application payment expired';
                    $user=User::where('id',$application_task->applicant)->first();


                    $this->remind_and_save_details($message, $application_task, $subject, $user);

                    // notify assessor that it is locked
                    $message = "Applicant's deadline for query response submission has expired on " . $deadline->toDateString() .
                        ". The Upload option for the applicant has been locked.";
                    $subject = 'Expiry Notification of  Application Payment Response Sent';
                    $user=User::where('id',$application_task->applicant)->first();


                    $this->remind_and_save_details($message, $application_task, $subject, $user);

                } else {
                    //do nothing
                }


            //end if


        }}


    } // end handle

    private function remind_and_save_details($message, $application_task, $subject, $user)
    {

        $new_notification = [];
        $new_notification['type'] = 'Reminder';
        $new_notification['subject'] = $subject;
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = $message;
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $application_task->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;


        Notification::send($user, new ApplicationReceiptionNotification($new_notification));

        event(new ApplicationReceiptionEvent($user->id, $message));
    }


}
