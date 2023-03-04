<?php

namespace App\Http\Controllers;

use App\Models\TaskTracker;
use App\Models\uploaded_documents;
use Illuminate\Http\Request;
use App\Models\MainTask;
use Illuminate\Support\Facades\DB;


class MainTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_tasks()
    {
        //
        $main_tasks = MainTask::all();
        $breadcrumb_title = 'Main Tasks';
        return view('main_task.index', ['main_tasks' => $main_tasks, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function index_kanban()
    {
        //
        $assigned = MainTask::where('task_status', 'assigned')->get();
        $in_progress = MainTask::where('task_status', 'in progress')->get();
        $complete = MainTask::where('task_status', 'complete')->get();
        $breadcrumb_title = 'KANBAN BOARD';
        return view('main_task.index_kanban', ['assigned' => $assigned, 'in_progress' => $in_progress, 'complete' => $complete, 'breadcrumb_title' => $breadcrumb_title]);
    }

    //there should be an id for retriving every dossiers activity
    public function index_timeline()
    {
        //
        $tasks = TaskTracker::all();
        $breadcrumb_title = 'Time Line';
        return view('main_task.index_timeline', ['tasks' => $tasks, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public static function insertTask($task_name, $related_type, $related_id, $task_duration_plan, $start_time,
                                      $end_time, $deadline, $task_status, $alert_before_days = 1)
    {
        try {
            DB::beginTransaction();
            $inserted = MainTask::insert([
                'task_name' => $task_name,
                'related_task' => $related_type,
                'related_id' => $related_id,
                'task_duration_days_plan' => $task_duration_plan,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'task_duration_days_actual' => null,
                'deadline' => $deadline,
                'task_status' => $task_status,
                'is_archived' => 0,
                'is_complete' => 1,
                'alert_before_days' => $alert_before_days,
            ]);

            if (!$inserted) {
                DB::rollBack();
                return false;
            } else {
                DB::commit();
                return true;
            }


        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

    }

    public static function insertActivity($task_id, $start_time, $end_time, $task_category,
                                          $task_activity_title, $content_detail, $route_link,
                                          $activity_status, $uploaded_document_id)
    {
        try {
            DB::beginTransaction();
            $inserted = TaskTracker::insert([
                'task_id' => $task_id, 
                'start_time' => $start_time,
                'end_time' => $end_time,
                'task_category' => $task_category,
                'task_activity_title' => $task_activity_title,
                'content_detail' => $content_detail,
                'route_link' => $route_link,
                'activity_status' => $activity_status,
                'is_active' => 1,
                'uploaded_document_id' => $uploaded_document_id
            ]);
            if (!$inserted) {
                DB::rollBack();
                return false;
            } else {
                DB::commit();
                return true;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }


    }


    public static function insertActivitywithQuery($task_id, $start_time, $end_time, $task_category,
    $task_activity_title, $content_detail, $route_link,
    $activity_status, $uploaded_document_id,$related_id)
{
    try {
        DB::beginTransaction();
        $inserted = TaskTracker::insert([
            'task_id' => $task_id, 
            'start_time' => $start_time,
            'end_time' => $end_time,
            'task_category' => $task_category,
            'task_activity_title' => $task_activity_title,
            'content_detail' => $content_detail,
            'route_link' => $route_link,
            'activity_status' => $activity_status,
            'is_active' => 1,
            'uploaded_document_id' => $uploaded_document_id,
            'related_id'  =>$related_id
        ]);
        if (!$inserted) {
            DB::rollBack();
            return false;
        } else {
            DB::commit();
            return true;
        }

    } catch (\Exception $e) {
        DB::rollBack();
        return false;
    }

}

    public function show_task($id)
    {
        //
        $task = MainTask::find($id);
        $breadcrumb_title = 'Task Detail';
        return view('main_task.detail', ['task' => $task, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function show_timeline($id)
    {
        //
        $task = TaskTracker::find($id);
        $document = uploaded_documents::where('id', $task->uploaded_document_id)->first();
        $breadcrumb_title = 'Task Detail';
        return view('main_task.time_line_detail', ['task' => $task, 'breadcrumb_title' => $breadcrumb_title, 'document' => $document]);
    }


}
