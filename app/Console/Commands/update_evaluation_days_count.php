<?php

namespace App\Console\Commands;

use App\Events\DossierAssignmentEvent;
use App\Events\DossierEvaluationRemindersEvent;
use App\Models\dossier_evaluation_progress;
use App\Models\dossier_assignment;
use App\Models\MainTask;
use App\Models\User;
use App\Notifications\RemindersNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class update_evaluation_days_count extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peru:update_eval_day_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dossier evaluation day count';

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

        $assignments = dossier_assignment::join('dossiers', 'dossier_id', 'dossier_assignments.dossier_id')
            ->join('dossier_evaluation_progresses',
                'dossier_evaluation_progresses.dossier_assignment_id', 'dossier_assignments.id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->select(
                'dossiers.dossier_ref_num',
                'dossier_assignments.id as dossier_assign_id',
                'dossier_assignments.supervisor_id as from_user_id',
                'dossier_assignments.assessor_id as to_user_id',
                'main_tasks.task_status as task_status',
                'main_tasks.task_duration_days_plan as dossier_eval_total_days',
                'dossier_evaluation_progresses.id as eval_progress_id',
                'dossier_evaluation_progresses.day_count as day_count'
            )
            ->get();

        foreach ($assignments as $assignment) {

            //todo -mera when extending deadline add num of days to existing task_duration_days_plan
            // for SR days = 130, for FR days = 60
            $dossier_eval_total_days = $assignment->dossier_eval_total_days;
            // update counter only if evaluation status is Inprogress
            // In case of 'pause' counter should not be updated, also 'complete' ..etc no need to count
            if ($assignment->task_status == "Inprogress"){

                if ($assignment->day_count < $dossier_eval_total_days) {


                    // remind assessor 10 days before deadline
                    $remind_dossier_eval_deadline_before_days = Config::get('site_vars.remind_dossier_eval_deadline_before_days');
                    $reminder_day = $dossier_eval_total_days - $remind_dossier_eval_deadline_before_days;

                    if ($assignment->day_count + 1 == $reminder_day) {
                        $message = "Reminder for Dossier: " . $assignment->dossier_ref_num . ". "
                            . $remind_dossier_eval_deadline_before_days . " days remaining to complete dossier evaluation.";
                        $subject = 'Dossier Evaluation Deadline Reminder';
                        $user = User::find($assignment->to_user_id);
                        $this->remind_and_save_details($message, $assignment, $subject, $user);

                    }

                    // increment day count by one
                    dossier_evaluation_progress::where('id', $assignment->eval_progress_id)
                        ->update(
                            [
                                'day_count' => $assignment->day_count + 1,
                            ]
                        );

                } elseif ($assignment->day_count + 1 == $dossier_eval_total_days){
                    // deadline reached but evaluation is still not completed so
                    // lock the evaluation and notify both assessor and supervisor
                    MainTask::where('related_id', $assignment->dossier_assign_id)
                        ->update(
                            [
                                'task_status' => 'Locked',
                            ]
                        );

                    $supervisor_message = "Dossier Evaluation Deadline Expired for Dossier: " . $assignment->dossier_ref_num .
                        ". The Dossier Evaluation has been Locked.";

                    $assessor_message = $supervisor_message . ' Please request your supervisor for extension.';

                    $subject = 'Dossier Evaluation Locked';
                    $assessor = User::find($assignment->to_user_id);
                    $supervisor = User::find($assignment->from_user_id);

                    //Notify Assessor
                    $this->remind_and_save_details($assessor_message, $assignment, $subject, $assessor);

                    //Notify Supervisor
                    $this->remind_and_save_details($supervisor_message, $assignment, $subject, $supervisor);

                }
            } //end if


        }

    } //handle

    private function remind_and_save_details($message, $assignment, $subject, $user)
    {

        $new_notification = [];
        $new_notification['type'] = 'Reminder';
        $new_notification['subject'] = $subject;
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = $message;
        $new_notification['related_id'] = $assignment->dossier_assign_id;
        $new_notification['related_document'] = null;
        $new_notification['alert_level'] = '';

        Notification::send($user, new RemindersNotification($new_notification));

        event(new DossierEvaluationRemindersEvent($user->id, $message));
    }
}
