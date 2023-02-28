<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Events\ApplicationReceiptionEvent;
use App\Models\application_evaluation_progress;
use App\Models\applications;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Config;
use App\Models\TaskTracker;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class assessor_prelimnary_screenning_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:preliminary_screening_daycount';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assessor preliminary screening for new application 10 days';

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
            $result =DB::table('applications')
    ->join('main_tasks', 'applications.id', 'main_tasks.related_id')
     ->join('task_trackers', 'main_tasks.id', 'task_trackers.task_id')
     ->join('application_evaluation_progresses', 'task_trackers.id', 'application_evaluation_progresses.task_id')
    ->select('applications.id as app_id',
    'applications.application_id as application_id',
    'applications.assigned_To as assesor',
    'applications.user_id  as applicant',
    'task_trackers.activity_status as task_status',
    //'application_evaluation_progresses.count_status as count_status',
    'main_tasks.related_task as related_task',
    'main_tasks.task_name as taskname',
    'task_trackers.extention_days as extention_days',
    'task_trackers.end_time as deadline',
    'task_trackers.task_category as task_category',
    'application_evaluation_progresses.id as eval_progress_id',
    'application_evaluation_progresses.day_count as day_count')
     ->get();
     foreach ($result as $r) {


        if ($r->task_status != "pause" ||  $r->task_status != "Locked") {


        $deadline = Carbon::Create( $r->deadline);
        $diff_in_days = $deadline->diffInDays(Carbon::now());

            // todo: next line is for testing - delete it
           // $diff_in_days = -1;
            $reminder_limit=5;
            // send reminder if applicant did not respond to the query yet and
            // remaining days are <=  $reminder_limit but >= 0


                if ($diff_in_days = $reminder_limit+1 and $diff_in_days > 0) {

                    $message = "Application screening report reminder: " . $diff_in_days . " days remaining to  finish Screening for application no ".$r->application_id." ";
                    $subject = 'Deadline Reminder';
                    $user=User::where('id',$r->assesor)->first();
                    //$user = User::find($assignment->to_user);

                    $this->remind_and_save_details($message, $r, $subject, $user);

                } elseif ($diff_in_days == 0) { //expired but response is still not sent

                    // on expire, disable response uploading (applicant has to request extension)

                    task_trackers::where('task_id', $r->task_id)->update([
                        'activity_status' => 'Locked'
                    ]);

                    // notify applicant to request extension after expiry
                    $message = "The deadline for screening application  report has expired on " . $deadline->toDateString() .
                        ". The  application has been locked. Please request extension to enable unlock the application.";
                    $subject = 'Deadline of application screening report';
                    $user=User::where('id',$r->assesor)->first();


                    $this->remind_and_save_details($message, $r, $subject, $user);

                    // notify assessor that it is locked
                    $message = "Assessor's deadline for screening report submission has expired on " . $deadline->toDateString() .

                    $subject = 'Expiry Notification for  application screening report';
                    $user=User::where('id',$r->assesor)->first();


                    $this->remind_and_save_details($message, $r, $subject, $user);

                } else {
                    //do nothing
                }


            //end if


        }}


}

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
