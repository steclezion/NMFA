<?php

namespace App\Console\Commands;
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
use Illuminate\Console\Command;
use App\Notifications\RemindersNotification;

use Illuminate\Support\Facades\Config;

class applicant_application_submission_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:update_application_daycount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update application submission days count ';

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
'task_trackers.activity_status as activity_status',
//'application_evaluation_progresses.count_status as count_status',
'main_tasks.related_task as related_task',
'main_tasks.task_name as taskname',
'task_trackers.extention_days as extention_days',
'task_trackers.end_time as deadline',
'task_trackers.id as task_id',
'task_trackers.start_time as start_time',
'task_trackers.task_category as task_category',
'application_evaluation_progresses.id as eval_progress_id',
'application_evaluation_progresses.day_count as day_count')
 ->get();
foreach ($application_tasks as $application_task ) {
if ($application_task->activity_status != "pause" ||  $application_task->activity_status != "Locked") {

        $deadline = Carbon::Create($application_task->deadline);
        //$sent_date = Carbon::Create($assignment->sent_date);
        $diff_in_days = $deadline->diffInDays(Carbon::now());

  
     

            if ( $diff_in_days > 0) {
                if($application_task->day_count  < 30 &&  $application_task->day_count % 5 == 0 )
                {

                $message = "Reminder for application  submission: " . $application_task->application_id . ". " . $diff_in_days . " days remaining to complete application and submit.";
                $subject = 'Deadline Reminder';
                $user = User::find($application_task->applicant);

                $this->remind_and_save_details($message, $application_task, $subject, $user);
                }
            } 
            elseif ($diff_in_days == 0) { //expired but report is still not uploaded

               
                task_trackers::where('id', $application_task->task_id)->update([
                    'activity_status' => 'Locked'    
                ]);

                // notify applicant to request extension after expiry
                $message = "Notification for application submission exipration  on: " . $application_task->application_id .
                    ". The deadline for application submission has expired on " . $deadline->toDateString() .
                    ". The option to fill application  has been disabled. Please request extension to enable the submission.";
                $subject = 'Deadline application submission expired';
                $user = User::find($application_task->applicant);

                $this->remind_and_save_details($message, $application_task, $subject, $user);

                

            } else {
                //do nothing
            }






       if($application_task->day_count  <= 30   && $application_task->task_category="Applying"  )
         {
         application_evaluation_progresses::where('id', $application_task->eval_progress_id)
              ->update(
                  [
                      'day_count' => $application_task->day_count + 1,
                  ]
              );
          }
        }
    }

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
