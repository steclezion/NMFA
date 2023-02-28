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

class app_query_response_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   protected $signature = 'peru:app_query_response_reminder';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Reminder applicant to submit query response prior to deadline';

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

       $assignments = DB::table('applications')
       ->join('main_tasks', 'applications.id', 'main_tasks.related_id')
        ->join('task_trackers', 'main_tasks.id', 'task_trackers.task_id')
        ->join('application_evaluation_progresses', 'task_trackers.id', 'application_evaluation_progresses.task_id')
        ->where('task_trackers.task_category','Query')
        ->where('task_trackers.activity_status','Locked')
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
       'task_trackers.id as task_id',
       'task_trackers.task_category as task_category',
       'application_evaluation_progresses.id as eval_progress_id',
       'application_evaluation_progresses.day_count as day_count')
        ->get();

       $reminder_limit = Config::get('site_vars.applicant_query_remind_before_days');
       // todo: next line is for testing - delete it
       //$reminder_limit = 14;

       foreach ($assignments as $assignment) {


           $deadline = Carbon::Create($assignment->deadline);
           $diff_in_days = $deadline->diffInDays(Carbon::now());

           // todo: next line is for testing - delete it
           //$diff_in_days = -1;

           // send reminder if applicant did not respond to the query yet and
           // remaining days are <=  $reminder_limit but >= 0
           if ($assignment->received_date == null) {

               if ($diff_in_days <= $reminder_limit+1 and $diff_in_days > 0) {

                   $message = "Query Response Reminder: " . $diff_in_days . " days remaining to respond to the query.";
                   $subject = 'Deadline Reminder';
                   $user = User::find($assignment->applicant);

                   $this->remind_and_save_details($message, $assignment, $subject, $user);

               } elseif ($diff_in_days == 0) { //expired but response is still not sent

                   // on expire, disable response uploading (applicant has to request extension)
                   Tasktracker::where('task_id', $assignment->task_id)->update([
                       'activity_status' => 'Locked'    //todo mera: disable upload button for units
                   ]);

                   // notify applicant to request extension after expiry
                   $message = "The deadline for query response has expired on " . $deadline->toDateString() .
                       ". The option to submit response has been disabled. Please request extension to enable the submission.";
                   $subject = 'Query Response Deadline Expired';
                   $user = User::find($assignment->applicant);

                   $this->remind_and_save_details($message, $assignment, $subject, $user);

                   // notify assessor that it is locked
                   $message = "Applicant's deadline for query response submission has expired on " . $deadline->toDateString() .
                       ". The Upload option for the applicant has been locked.";
                   $subject = 'Expiry Notification of Query Response';
                   $user = User::find($assignment->assesor);

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
       $new_notification['related_id'] = $assignment->application_id;
       $new_notification['related_document'] = null;
       $new_notification['alert_level'] = null;

       Notification::send($user, new RemindersNotification($new_notification));

       event(new DossierAssignmentEvent($user->id, $message));
   }

}
