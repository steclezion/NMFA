<?php

namespace App\Http\Controllers;

use App\Decision;
use App\Models\applications;
use App\Models\AppSetting;
use App\Notifications\QC;
use App\Notifications\InformationNotification;
use Illuminate\Http\Request;
use App\Models\dossier;
use App\Models\User;
use App\Models\Variation;
use App\Models\dossier_assignment;
use App\Http\Controllers\MainTaskController;
use App\Events\DossierAssignmentEvent;
use App\Models\MainTask;
use Illuminate\Support\Facades\Config;
use App\Models\DossierStatusLookup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Models\dossier_evaluation_progress;
use Illuminate\Support\Facades\Notification;


class DossierAssignmentController extends Controller
{

    //this function below retrives the main task id based on the given assignment id
    private function get_main_task_id($dossier_ass_id, $related_type = 'Dossier Evaluation')
    {

        $main_task = MainTask::where('related_id', $dossier_ass_id)
            ->where('related_task', $related_type)
            ->first();
        if ($main_task) {
            return $main_task;
        } else {

            return 0; //means false
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unassigned_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->leftjoin('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->where('dossier_status_lookups.id', 1)
            ->select('dossiers.*', 'company_suppliers.trade_name as company_name',
                'applications.application_number as application_id', 'applications.application_id as app_id',
                'applications.application_type','applications.registration_type',
                'medicines.product_name')
            ->get();
        $all_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->leftjoin('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->leftjoin('applications', 'applications.dossier_id', 'dossiers.id')
            ->leftjoin('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->orWhere('main_tasks.related_task', 'Dossier Evaluation')
            ->orwhereIn("main_tasks.task_status",["Inprogress","pause"])
            ->where('dossier_status_lookups.id', "<>", 4)
            ->select('dossiers.*', 'dossier_assignments.id as dossier_assignment_id', 'applications.application_id')
            ->get();

        $assigned_dossiers = dossier::join('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->leftjoin('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->where('dossier_status_lookups.id', 2)
            ->select('dossiers.*', 'company_suppliers.trade_name as company_name','medicines.product_name',
                'dossier_assignments.id as dossier_assignment_id',
                'main_tasks.task_status')
            ->get();

        $reassign_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->leftjoin('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('decisions', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->where('dossier_status_lookups.id', 8)
            ->where('decisions.decision_status', 'Reassign')
            ->select('dossiers.*', 'company_suppliers.trade_name as company_name', 'dossier_assignments.id as dossier_assignment_id', 'applications.application_id', 'applications.application_type', 'medicinal_products.product_trade_name')
            ->get();

        $breadcrumb_title = 'Dossiers';
        return view('dossier_assignment.dossier_assignment_tab', ['unassigned_dossiers' => $unassigned_dossiers, 'reassign_dossiers' => $reassign_dossiers,
            'assigned_dossiers' => $assigned_dossiers, 'all_dossiers' => $all_dossiers, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function assigned_index()
    {

        $assigned_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->leftjoin('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->where('dossier_status_lookups.id', 2)
            ->where('main_tasks.task_status', '!=', 'Decision')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->select('dossiers.*', 'dossier_assignments.id as dossier_assignment_id', 'users.first_name', 'users.middle_name')
            ->get();
        $breadcrumb_title = 'Assigned Dossiers';
        return view('dossier_assignment.assigned_dossier.index', ['assigned_dossiers' => $assigned_dossiers, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function all_index()
    {
        $all_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->leftjoin('dossier_assignments', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->where('dossier_status_lookups.id', "<>", 4)
            ->select('dossiers.*', 'dossier_assignments.id as dossier_assignment_id')
            ->get();

        $breadcrumb_title = 'All Dossiers';
        return view('dossier_assignment.list_all.index', ['all_dossiers' => $all_dossiers, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function assign_dossier_index($id)
    {


        $dossier = dossier::where('dossiers.id', $id)
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->select('dossiers.*', 'applications.application_number', 'applications.application_type', 'medicines.product_name')->first();
//        dd($dossier);
        //
        $assessors = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Assessor')
            ->get();
        $breadcrumb_title = 'Assign Dossier';
        $unassigned_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->where('dossier_status_lookups.id', 1)
            ->select('dossiers.*')
            ->get();
        return view('dossier_assignment.assign_dossier.assign', ['unassigned_dossiers' => $unassigned_dossiers, 'dossier' => $dossier, 'assessors' => $assessors, 'breadcrumb_title' => $breadcrumb_title]);
    }


    public function reassign_dossier_index($id)
    {


//         $dossier = dossier::where('dossiers.id',$id)
//             ->join('applications','applications.dossier_id','dossiers.id')
//             ->join('medicinal_products','medicinal_products.id','applications.medical_product_id')
//             ->join('medicines','medicines.id','medicinal_products.medicine_id')
//             ->select('dossiers.*','applications.application_number','applications.application_type','medicines.product_name')->first();
// //        dd($dossier);

        $dossier = dossier_assignment::where('dossier_assignments.dossier_id', $id)
            ->join('users', 'users.id', 'dossier_assignments.assessor_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->select('dossiers.*', 'dossier_assignments.id as dossier_assignment_id', 'users.first_name', 'users.middle_name', 'applications.application_number', 'applications.application_type', 'medicines.product_name')->first();
//        
        $assessors = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Assessor')
            ->get();
        $breadcrumb_title = 'Assign Dossier';
        $unassigned_dossiers = dossier::join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->where('dossier_status_lookups.id', 1)
            ->select('dossiers.*')
            ->get();
        return view('dossier_assignment.reassign_dossier', ['unassigned_dossiers' => $unassigned_dossiers, 'dossier' => $dossier, 'assessors' => $assessors, 'breadcrumb_title' => $breadcrumb_title]);
    }

    public function assign_dossier(Request $request)
    {


        // todo $assessor must be required
        try {
            DB::transaction(function () use ($request) {

                $assessor_id = $request->input('assessor');
                $dossier_id = $request->input('dossier_id');

                // session user id(super visor id)


                $assigned_datetime = date('Y-m-d H:i:s');

                $application = applications::where('dossier_id', $dossier_id)->first();
                $registration_number = $application->re_registration_number;

                if ($application->application_type == 1) {
                    $track_type = 'SR';
                    $duration_days = '130';

                } elseif ($application->application_type == 2) {

                    $track_type = 'FR';
                    $duration_days = '60';
                } else {

                    return Redirect()->back()->with('danger', 'Invalid Application Number.');
                }
                $end_datetime = date('Y-m-d H:i:s', strtotime('+ ' . $duration_days . ' days'));

                $dossier_ref_num = $this->generate_dossier_ref($dossier_id, $track_type,$registration_number);
                dossier::where('id', $dossier_id)->update([
                    'dossier_ref_num' => $dossier_ref_num
                ]);
                //Progress update and activity update

                //Assignment
                $dossier_assignment = new dossier_assignment();
                $application = applications::where('dossier_id', $dossier_id)->first();
                $dossier_assignment->dossier_id = $dossier_id;
                $dossier_assignment->assessor_id = $assessor_id;
                $dossier_assignment->supervisor_id = auth()->user()->id;//this is from session
                $dossier_assignment->application_id = $application->id;
                $dossier_assignment->assigned_datetime = $assigned_datetime;
                $dossier_assignment->save();


                dossier::where('id', $dossier_id)->update([
                    'assignment_status' => 2
                ]);
                // initialize main task details

                dossier_evaluation_progress::insert([
                    'dossier_assignment_id' => $dossier_assignment->id,
                    'progress_percentage' => $application->progress_percentage,
                ]);
                // dd($dossier_assignment->id);

                $task_name = 'Dossier Assignment';
                $related_task = 'Dossier Evaluation';
                $related_id = $dossier_assignment->id;
                $start_time = $assigned_datetime;
                $end_time = $end_datetime;
                $stopping_reason = '';
                $task_duration_days_actual = null;
                $is_active = 1;
                $is_complete = 0;
                $is_archived = 0;
                $task_status = 'Inprogress';
                $deadline = null;
                $deadline_extended_to = '';
                //notify before days
                $alert_before_days = 10;

                $task_inserted = MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days, $start_time,
                    $end_time, $deadline, $task_status, $alert_before_days);

                if (!$task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting task details.
                    Your changes have not been updated to database.');
                }


                //update activity for timeline
                $main_task = $this->get_main_task_id($related_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Dossier Evaluation';
                $task_activity_title = 'New Dossier Assignment';
                $user = User::find($assessor_id);
                $content_details = 'New Dossier Assigned to ' . $user->first_name . ' ' . $user->middle_name . ' for evaluation.';
                $route_link = '';
                $activity_status = 'Inprogress';
                $uploaded_document_id = null;


                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'New dossier Assigment. Dossier Ref. Num: ' . $dossier_ref_num;
                $new_notification['subject'] = 'New Dossier Assignment';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['alert_level'] = 'high';
                $new_notification['related_id'] = $related_id;
                $new_notification['related_document'] = '';
                $new_notification['remark'] = '';

                $user = User::find($assessor_id);

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor_id, 'New Dossier Assignment. Dossier Ref. Num: ' . $dossier_ref_num));

            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', 'MainTaskNotInsertedException: ' . $e->getMessage());
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        return redirect('/dossier_assignment/assigned')->with('success', 'Dossier Assigned Successfully.');
        //return Redirect()->back()->with('success', 'Dossier Assigned Successfully.');
    }


    public function reassign_dossier(Request $request)
    {

        try {


            DB::transaction(function () use ($request) {

                $assessor_id = $request->input('assessor');
                $dossier_id = $request->input('dossier_id');
                $dossier_assignment_id = $request->input('dossier_assignment_id');
                $dossier_assignment_data = dossier_assignment::where('id', $dossier_assignment_id)->first();
                $dossier = dossier::where('id', $dossier_id)->first();

                // session user id(super visor id)


                $assigned_datetime = date('Y-m-d H:i:s');

                $application = applications::where('dossier_id', $dossier_id)->first();
                $progress = 0;
                if ($application->application_type == 1) {
                    $track_type = 'SR';
                    $duration_days = '130';
                    $progress = 10;


                } elseif ($application->application_type == 2) {

                    $track_type = 'FR';
                    $duration_days = '60';
                    $progress = 20;

                } else {

                    return Redirect()->back()->with('danger', 'Invalid Application Number.');
                }
                $end_datetime = date('Y-m-d H:i:s', strtotime('+ ' . $duration_days . ' days'));

                $dossier_ref_num = $dossier->dossier_ref_num;

                applications::where('dossier_id', $dossier_id)->update([
                    'progress_percentage' => $progress
                ]);


                //Progress update and activity update

                //Assignment
                $dossier_assignment = new dossier_assignment();
                $application = applications::where('dossier_id', $dossier_id)->first();
                $dossier_assignment->dossier_id = $dossier_id;
                $dossier_assignment->assessor_id = $assessor_id;
                $dossier_assignment->supervisor_id = auth()->user()->id;//this is from session
                $dossier_assignment->application_id = $application->id;
                $dossier_assignment->assigned_datetime = $assigned_datetime;
                $dossier_assignment->save();


                dossier::where('id', $dossier_id)->update([
                    'assignment_status' => 2
                ]);
                // initialize main task details

                dossier_evaluation_progress::insert([
                    'dossier_assignment_id' => $dossier_assignment->id,
                    'progress_percentage' => $application->progress_percentage,
                ]);
                // dd($dossier_assignment->id);

                $task_name = 'Dossier Reassignment';
                $related_task = 'Dossier Evaluation';
                $related_id = $dossier_assignment->id;
                $start_time = $assigned_datetime;
                $end_time = $end_datetime;
                $stopping_reason = '';
                $task_duration_days_actual = null;
                $is_active = 1;
                $is_complete = 0;
                $is_archived = 0;
                $task_status = 'Inprogress';
                $deadline = $end_datetime;
                $deadline_extended_to = '';
                //notify before days
                $alert_before_days = 10;

                $task_inserted = MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days, $start_time,
                    $end_time, $deadline, $task_status, $alert_before_days);

                if (!$task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting task details.
                    Your changes have not been updated to database.');
                }


                //update activity for timeline
                $main_task = $this->get_main_task_id($related_id);
                $end_time = date('Y-m-d H:i:s', strtotime('+ 30 days'));
                $issued_datetime = date('Y-m-d H:i:s');
                $task_category = 'Dossier Evaluation';
                $task_activity_title = 'Dossier Reassignment';
                $user = User::find($assessor_id);
                $content_details = 'New Dossier Assigned to ' . $user->first_name . ' ' . $user->middle_name . ' for evaluation.';
                $route_link = '';
                $activity_status = 'Inprogress';
                $uploaded_document_id = null;


                //insert this into task tracker
                $main_task_inserted = MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time,
                    $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

                if (!$main_task_inserted) {
                    throw new MainTaskNotInsertedException('Problem inserting activity details.
                    Your changes have not been updated to database.');
                }

                $new_notification = [];
                $new_notification['type'] = 'Notification';
                $new_notification['data'] = 'Dossier Reassignment. Dossier Ref. Num: ' . $dossier_ref_num;
                $new_notification['subject'] = 'Dossier Reassignment';
                $new_notification['from_user'] = auth()->user()->first_name . ' ' . auth()->user()->middle_name;
                $new_notification['alert_level'] = 'high';
                $new_notification['related_id'] = $related_id;
                $new_notification['related_document'] = '';
                $new_notification['remark'] = '';

                Decision::where('dossier_assignment_id', $dossier_assignment_id)
                    ->where('decisions.decision_status', 'Reassign')
                    ->update(
                    [
                        'decision_status' => 'Reassigned'
                    ]
                );
                $user = User::find($assessor_id);

                Notification::send($user, new InformationNotification($new_notification));
                event(new DossierAssignmentEvent($assessor_id, 'Reassigned Dossier Assignment. Dossier Ref. Num: ' . $dossier_ref_num));

            }); // end transaction

        } catch (MainTaskNotInsertedException $e) {
            return Redirect()->back()->with('danger', 'MainTaskNotInsertedException: ' . $e->getMessage());
        } catch (\Exception $e) {
            return Redirect()->back()->with('danger', 'Problem with Database Operations. ' . $e->getMessage());
        }

        $assigned_assessor_id = $request->input('assessor');
        $user = User::find($assigned_assessor_id);

        return redirect('/dossier_assignment/assigned')->with('success', 'Dossier Reassigned Successfully to '.$user->first_name .' '. $user->last_name );
    }


    private function generate_dossier_ref($dossier_id, $track_type,$registration_number)
    {

        // generate Reference ID;
        //get from application the track_type either FR/SR


        //get year from  the System
        //get dossier count from the dossier table

        /*first check if the year is equal with the current year in db if the year is not equal
        the reset the dossier_ref_num_counter to 1 and update current year to the new year
        */
        $year_setting = AppSetting::where('name', 'current_year')->first();
        $year_in_db = $year_setting->value;
        $year_from_system = date('Y');

        if ($year_in_db == $year_from_system) {
            $dossier_ref_num_setting = AppSetting::where('name', 'dossier_ref_num_counter')->first();
            $count = $dossier_ref_num_setting->value;
            $zero_filled_counter = sprintf('%04d', $count);
            $ref_num = $track_type . '/' . $registration_number .'/'. $year_from_system . '/' . $zero_filled_counter;
            //increment the counter
            AppSetting::where('name', 'dossier_ref_num_counter')->update(
                [
                    'value' => $count + 1,
                ]
            );



        } else {
            //update the current year in db to the system year
            AppSetting::where('name', 'current_year')->update(
                [
                    'value' => $year_from_system,
                ]
            );
            //reset the counter to 1
            $count = 1;
            AppSetting::where('name', 'dossier_ref_num_counter')->update(
                [
                    'value' => $count + 1,
                ]
            );

            AppSetting::where('name', 'product_registration_counter')->update(
                [
                    'value' => $count ,
                ]
            );
            AppSetting::where('name', 'letter_reference_number_counters')->update(
                [
                    'value' => $count ,
                ]
            );

            $zero_filled_counter = sprintf('%04d', $count);
            $ref_num = $track_type . '/' . $year_from_system . '/' . $zero_filled_counter;

        }


        return $ref_num;
    }

    public function retrieve_assessor_assignments(Request $request)
    {

        try {

            $id = $request->id;


            $return_data = "";

            $assessor_check = dossier_assignment::where('assessor_id', $id)
                ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->whereIn("main_tasks.task_status",["Inprogress","pause"])
                ->get();
            if (count($assessor_check) > 0) {
                $assessor_assignment_details = dossier_assignment::where('assessor_id', $id)
                    ->join('users', 'users.id', 'dossier_assignments.assessor_id')
                    ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                    ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
                    ->join('applications','applications.id','dossier_assignments.application_id')
                    ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                    ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                    ->where('main_tasks.related_task', 'Dossier Evaluation')
                    ->whereIn("main_tasks.task_status",["Inprogress","pause"])
                    ->whereIn('dossiers.assignment_status', [2, 3])  // inprogres, pause
                    ->select('dossier_assignments.*', 'dossiers.dossier_ref_num', 'medicines.product_name')
                    ->get();
                $i = 1;

//               dd($assessor_assignment_details);

                foreach ($assessor_assignment_details as $assignment) {
                    $main_task = MainTask::where('related_id', $assignment->id)
                        ->where('related_task', 'Dossier Evaluation')
                        ->first();
                    $application = applications::find($assignment->application_id);

                    //$return_data .= "<tr><td>" . $i++ . "</td>";
                    $return_data .= "<td>" . $assignment->dossier_ref_num . "</td>";
                    $return_data .= "<td> Dossier Evaluation</td>";
                    $return_data .= "<td>" . $assignment->product_name . "</td>";
                    $return_data .= "<td>" . $main_task->end_time . "</td>";
                    $return_data .= "<td> <div class='progress'>
                        <div class='progress-bar bg-gradient-green progress-bar-striped'
                            role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100'
                            style='width:" . $application->progress_percentage . "%'>
                            <span >" . $application->progress_percentage . "% Complete </span>
                        </div>

                    </div></td>";
                    if($main_task->task_status == 'Inprogress'){
                    $return_data .= "<td><span class='badge badge-primary'>In-progress</span></td>";
                    }elseif($main_task->task_status == 'pause'){
                        $return_data .= "<td><span class='badge badge-warning'>Paused</span></td>";
                    }else {
                        $return_data .= "<td><span class='badge badge-secondary'>" . $main_task->task_status . "</span></td>";
                    }

                    $return_data .= "<td><a href='/dossier_evaluation/edit/".$assignment->id."'  class='btn btn-info btn-sm'  ><i class='fas fa-list'></i></a></td></tr> ";
                }

            }


            $assessor_check = Variation::where('assessor_id', $id)
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn("main_tasks.task_status",["Inprogress","Pending","inprogress", 'pause'])
            ->get();

        if (count($assessor_check) > 0) {
            $assessor_assignment_details = Variation::where('variations.assessor_id', $id)
                ->join('users', 'users.id', 'variations.assessor_id')
                ->join('certifications','certifications.id','variations.certificate_id')
                ->join('decisions','decisions.id','certifications.decision_id')
                ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
                ->join('applications','applications.id','dossier_assignments.application_id')
                ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
                ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
                ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
                ->where('main_tasks.related_task', 'Variation')
                ->whereIn("main_tasks.task_status",["Inprogress","Pending","inprogress", 'pause'])
                ->select('variations.*','medicines.product_name')
                ->get();
            $i = 1;

               // dd($assessor_assignment_details);

            foreach ($assessor_assignment_details as $assignment) {
                $main_task = MainTask::where('related_id', $assignment->id)
                    ->where('related_task', 'Variation')
                    ->first();
                //$return_data .= "<tr><td>" . $i++ . "</td>";
                $return_data .= "<td>" . $assignment->variation_reference_number . "</td>";
                $return_data .= "<td> Variation Assessment</td>";
                $return_data .= "<td>" . $assignment->product_name . "</td>";
                $return_data .= "<td>" . $main_task->end_time . "</td>";
                $return_data .= "<td> <span class='badge bg-success badge-btn font-weight-bold'>In Variation Evaluation</span></td>";

                if($assignment->status  == 'Inprogress'){
                    $return_data .= "<td><span class='badge badge-primary'>In-progress</span></td>";
                }elseif($assignment->status  == 'pause'){
                    $return_data .= "<td><span class='badge badge-warning'>Paused</span></td>";
                }else {
                    $return_data .= "<td><span class='badge badge-secondary'>" . $assignment->status  . "</span></td>";
                }

                $return_data .= "<td><a href='/variation_evaluation/edit/" . $assignment->id . "' class='btn btn-info btn-sm'  ><i class='fas fa-list'></i></a></td></tr> ";
            }

            //TODO - retrieve PSUR TASKS Here

                return response()->json(['assignment' => $return_data, 'assessor_name' => $assessor_assignment_details[0]->first_name . ' ' . $assessor_assignment_details[0]->middle_name]);
            } else {
                $return_data .= "";
                return response()->json(['assignment' => $return_data, 'assessor_name' => '']);
            }
        } catch (\Exception $e) {
            return response()->json(['assignment' => $e, 'data' => "", 'item' => 'error' . $e]);
        }
        return response()->json(['assignment' => 'generic', 'item' => 'item_success']);
    }

}
