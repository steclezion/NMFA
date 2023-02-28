<?php

namespace App\Console\Commands;
use App\Models\application_evaluation_progresses;
use App\Models\applications;
use App\Models\MainTasks;
use App\Models\TaskTraker;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use App\Events\ApplicationReceiptionEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Console\Command;


class update_application_days_count extends Command
{
   
  /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_application:daycount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update application and screening day count (max is 90 days)';

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
'applications.user_id  as applicant',
//'task_trakers activity_status as task_status',
//'application_evaluation_progresses.count_status as count_status',
'main_tasks.related_task as related_task',
'main_tasks.task_name as taskname',
'task_trackers.extention_days as extention_days',
'task_trackers.task_category as task_category',
'application_evaluation_progresses.id as eval_progress_id',
'application_evaluation_progresses.day_count as day_count')
 ->get();
//dd($result);


        foreach ($result as $r) {

           if ($r->task_category != "pause") {

            if($r->day_count  < 30 &&  $r->day_count % 5 == 0 )
            {

                if($r->task_category="Applying"  )
                {
 
                  $user=User::where('id',$r->applicant)->first();
                  
                  $new_notification=[];
                  $new_notification['type']='Application';
                  $new_notification['data']='Dead line for application';
                  $new_notification['subject']='Application  deadline Notification';
                  $new_notification['alert_level']='high';
                  $new_notification['related_document']=  '';
                  $new_notification['remark']='remark';
                  // ::send($users, new ($invoice));
                 // $user=auth()->user();
          
                  Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                   event(new ApplicationReceiptionEvent($user->id, 'Application  deadline notification.' . ' ' . $user->first_name . ' ' . $user->last_name));
             
                }
                
             }

            else if($r->task_category="Applying" && $r->day_count  == 30 )
               {

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Application';
                 $new_notification['data']='Deadline  reached for application';
                 $new_notification['subject']='Applicant crossed deadline Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'Your application locked due to deadline Mr.' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }

            }
               if($r->day_count  == 5 && $r->task_category="Screening" ){
              

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Screening';
                 $new_notification['data']=' Deadline Screening for application'.$r->application_id;
                 $new_notification['subject']='Screening  deadline Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'Screening  deadline passed Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }
            
               else if($r->day_count  == 10 && $r->task_category="Screening" )
               {

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Screening';
                 $new_notification['data']='  Deadline for intial Screening for application'.$r->application_id;
                 $new_notification['subject']='Screening  deadline  passed  Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'Screening  deadline passed  Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }
               else if($r->day_count + $r->extention_days == 14 && $r->task_category="Screening" )
               {

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Screening';
                 $new_notification['data']=' Extended Deadline  crossed for intial Screening for application'.$r->application_id;
                 $new_notification['subject']='Screening  deadline Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'Screening Extended deadline passed  Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }
            
               if($r->day_count  <   30 && $r->day_count % 10 == 0){
               if( $r->task_category="Payment" )
               {

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Payment';
                 $new_notification['data']='Pay As Soon as for PERU'.$r->application_id;
                 $new_notification['subject']='Payment  deadline Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'payment  deadline Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }}
               else if ($r->day_count  == 30 && $r->task_category="Payment")
               {
                $user=User::where('id',$r->applicant)->first();
                 
                $new_notification=[];
                $new_notification['type']='Payment';
                $new_notification['data']='Final payment Deadline'.$r->application_id;
                $new_notification['subject']=' Payment  deadline crossed Notification';
                $new_notification['alert_level']='high';
                $new_notification['related_document']=  '';
                $new_notification['remark']='remark';
                // ::send($users, new ($invoice));
               // $user=auth()->user();
        
                Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                 event(new ApplicationReceiptionEvent($user->id, 'payment  deadline Passed Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
           
               }
            
/*
               if($r->day_count  = 7 && $r->task_category="Final-Screening" )
               {
               

                 $user=User::where('id',$r->applicant)->first();
                 
                 $new_notification=[];
                 $new_notification['type']='Screening';
                 $new_notification['data']=' Final Screening for application'.$r->application_id;
                 $new_notification['subject']='Final Screening  deadline Notification';
                 $new_notification['alert_level']='high';
                 $new_notification['related_document']=  '';
                 $new_notification['remark']='remark';
                 // ::send($users, new ($invoice));
                // $user=auth()->user();
         
                 Notification::send($user, new ApplicationReceiptionNotification($new_notification));
                  event(new ApplicationReceiptionEvent($user->id, 'Final Screening  deadline Notification' . ' ' . $user->first_name . ' ' . $user->last_name));
            
               }
               
            */


               if($r->day_count  <= 30   && $r->task_category="Applying"  )
               {
               application_evaluation_progresses::where('id', $r->eval_progress_id)
                    ->update(
                        [
                            'day_count' => $r->day_count + 1,
                        ]
                    );
                }

                if($r->day_count  < 10 +$r->extention_days   && $r->task_category="Screening" ||   $r->task_category="Final-Screening" )
               {
               application_evaluation_progresses::where('id', $r->eval_progress_id)
                    ->update(
                        [
                            'day_count' => $r->day_count + 1,
                        ]
                    );
                }
                if($r->day_count  <= 30   && $r->task_category="Payment"  )
               {
               application_evaluation_progresses::where('id', $r->eval_progress_id)
                    ->update(
                        [
                            'day_count' => $r->day_count + 1,
                        ]
                    );
                }
          }

        }
        
    }


