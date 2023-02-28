<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Assignment_unassignment;
use App\Models\applications;
use Illuminate\Http\Request;
use DataTables;
use App\Models\User;
use App\Models\company_suppliers;
use App\Models\manufacturers;
use App\Models\medicinal_products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash;
use App\Models\TaskTracker;
use App\Models\MainTask;
use App\Http\Controllers\MainTaskController;
use App\Notifications\ApplicationReceiptionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\application_evaluation_progresses;
use App\Events\ApplicationReceiptionEvent;

use Illuminate\Support\Arr;


class AssignmentUnassignmentController extends Controller
{


    function __construct()

    {

        $this->middleware('permission:supervisor_roles');
        //$this->middleware('permission:application-status-list');


    }


    private function get_main_task_id($application_id, $related_type)
    {
        $main_task = MainTask::where('related_id', $application_id)
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
    public function index(Request $request)
    {

        $dataa = User::orderBy('id', 'ASC')->get();


        if ($request->ajax()) {

            $data = applications::join('medicinal_products', 'medicinal_products.application_id', 'applications.application_id')
                ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
                ->join('users', 'users.id', 'applications.user_id')
                ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
                ->join('contacts', 'contacts.application_id', 'applications.application_id')
                ->select('applications.*',
                    DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname'),

                    'medicinal_products.*', 'medicinal_products.product_trade_name as t_name', 'company_suppliers.*', 'medicines.*',
                    'company_suppliers.trade_name as cs_tradename', 'applications.*', 'contacts.*',
                    'contacts.first_name as cfirst_name', 'contacts.middle_name as cmiddle_name',
                    'contacts.last_name as clast_name')
                ->where('contacts.contact_type', '=', 'Supplier')
                ->where('applications.assigned_To', '<>', NULL)
                ->orderBy('application_number', 'ASC')
                ->get();

            // $data = Book::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('assigned_To', function ($row) {
                    $assinged_To_full_Name = User::select('users.*')
                        ->where('id', '=', $row->assigned_To)
                        ->get();
                    return $assinged_To_full_Name[0]->first_name . " " . $assinged_To_full_Name[0]->middle_name . " " . $assinged_To_full_Name[0]->last_name;
                })
                ->addColumn('assigned_By', function ($row) {
                    $assinged_By_full_Name = User::select('users.*')
                        ->where('id', '=', $row->assigned_By)
                        ->get();

                    return $assinged_By_full_Name[0]->first_name . " " . $assinged_By_full_Name[0]->middle_name . " " . $assinged_By_full_Name[0]->last_name;

                })
                ->addColumn('Assginment_Date', function ($row) {
                    return $row->Assginment_Date;
                })
                ->addColumn('action', function ($row) {
                    if ($row->application_status == 'processing') {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
     title="Re-assign" class="edit btn btn-warning btn-sm editAssign">
     <span  style="color:black"> <span class="fas fa-edit"> </span> </span></a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"

    title="Cannot Reassign application has finished preliminary screening" class="edit btn btn-info btn-sm  info_assingment">

    <span  style="color:black"> <span class="fas fa-info"> </span> </span></a>';
                    }
                    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';

                    return $btn;
                })
                ->addColumn('application_status', function ($row) {

                    if ($row->application_status == 'processing') {
                        $badge = 'badge bg-warning';
                    } elseif ($row->application_status == 'Preliminary screening completed') {
                        $badge = 'badge bg-success';
                    } elseif ($row->application_status == 'Preliminary screening rejected') {
                        $badge = 'badge bg-danger';
                    }
                    $btn = "<span class='$badge'>  $row->application_status  </span>";

                    return $btn;
                })
                ->rawColumns(['action', 'application_status'])
                ->make(true);
        }

        //return view('assign_unassign');

        return view('assign_unassign.assigned', compact('dataa'));


    }


    public function all_assigned_unassigned(Request $request)
    {


        $dataa = User::orderBy('id', 'ASC')->get();


        if ($request->ajax()) {


            $data = applications::join('medicinal_products', 'medicinal_products.application_id', 'applications.application_id')
                ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
                ->join('users', 'users.id', 'applications.user_id')
                ->join('medicines', 'medicinal_products.medicine_id', 'medicines.id')
                ->join('contacts', 'contacts.application_id', 'applications.application_id')
                ->select('applications.*',
                    DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname'),
                    'medicinal_products.*', 'medicinal_products.product_trade_name as t_name', 'company_suppliers.*',
                    'company_suppliers.trade_name as cs_tradename', 'applications.*', 'contacts.*', 'medicines.*',
                    'contacts.first_name as cfirst_name', 'contacts.middle_name as cmiddle_name',
                    'contacts.last_name as clast_name')
                ->where('contacts.contact_type', '=', 'Supplier')
                ->where('application_number', '<>', NULL)
                // ->where('applications.assigned_To','<>',NULL)
                ->orderBy('application_number', 'ASC')
                ->get();

            // $data = Book::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('assigned_To', function ($row) {
                    if ($row->assigned_To != '') {
                        $assinged_To_full_Name = User::select('users.*')
                            ->where('id', '=', $row->assigned_To)
                            ->get();
                        return $assinged_To_full_Name[0]->first_name . " " . $assinged_To_full_Name[0]->middle_name . " " . $assinged_To_full_Name[0]->last_name;
                    } else {
                        return '-';
                    }

                })
                ->addColumn('assigned_By', function ($row) {
                    if ($row->assigned_By != '') {
                        $assinged_By_full_Name = User::select('users.*')
                            ->where('id', '=', $row->assigned_By)
                            ->get();
                        return $assinged_By_full_Name[0]->first_name . " " . $assinged_By_full_Name[0]->middle_name . " " . $assinged_By_full_Name[0]->last_name;
                    } else {
                        return '-';
                    }

                })
                ->addColumn('Assginment_Date', function ($row) {
                    return $row->Assginment_Date;
                })
                ->addColumn('action', function ($row) {
                    if ($row->assigned_By != '') {


                        if ($row->application_status == 'processing') {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
                      title="Re-assign" class="edit btn btn-warning btn-sm editAssign">
                      <span  style="color:black"> <span class="fas fa-edit"> </span> </span></a>';
                        } else {
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
                 
                     title="Cannot Reassign application has finished preliminary screening" class="edit btn btn-info btn-sm  info_assingment">
                 
                     <span  style="color:black"> <span class="fas fa-info"> </span> </span></a>';
                        }


                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
                    data-original-title="Assign"  title="Assign"  class="edit btn btn-warning btn-sm editAssign"> <span class="fas fa-edit"> </span></a>';

                        // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';

                        return $btn;
                    }
                    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';

                    return $btn;
                })
                ->addColumn('application_status', function ($row) {

                    if ($row->application_status == 'processing') {
                        $badge = 'badge bg-warning';
                    } elseif ($row->application_status == 'Preliminary screening completed') {
                        $badge = 'badge bg-success';
                    } elseif ($row->application_status == 'Preliminary screening rejected') {
                        $badge = 'badge bg-danger';
                    }
                    $btn = "<span class='$badge'>  $row->application_status  </span>";

                    $btn = '<a href="javascript:void(0)"  class="badge bg-warning">' . $status . '</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'application_status'])
                ->make(true);
        }

        //return view('assign_unassign');

        return view('assign_unassign.unassigned_assigned_all', compact('dataa'));


    }


    public function unassigned(Request $request)
    {


        $dataa = User::orderBy('id', 'ASC')->get();


        if ($request->ajax()) {


         $data = applications::join('medicinal_products','medicinal_products.application_id','applications.application_id')
                ->join('medicines','medicinal_products.medicine_id','medicines.id')
                ->join('company_suppliers','company_suppliers.application_id','applications.application_id')
                ->join('users','users.id','applications.user_id')
                ->join('contacts','contacts.application_id','applications.application_id')
                ->select('applications.application_number',
                    DB::raw('concat(contacts.first_name," ",contacts.last_name) as fullname'),'medicinal_products.*',
                    'medicinal_products.product_trade_name as t_name','company_suppliers.*','medicines.*',
                    'company_suppliers.trade_name as cs_tradename','applications.*',
                    'contacts.*', 'contacts.first_name as cfirst_name','contacts.middle_name as cmiddle_name','contacts.last_name as clast_name'
                )
                ->where('contacts.contact_type','=','Supplier')
                ->where('applications.assigned_To','=',NULL)
                ->where('applications.application_number','<>','')
                ->orderBy('applications.application_number','ASC')
                ->get();



            // $data = Book::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {


                    if ($row->application_status == 'processing') {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
                title="Assign" class="edit btn btn-warning btn-sm editAssign"> 
                <span class="fas fa-edit"> </span> </a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->application_id . '"
         
             title="Cannot Reassign application has finished preliminary screening" class="edit btn btn-info btn-sm  info_assingment">
         
             <span  style="color:black"> <span class="fas fa-info"> </span> </span></a>';
                    }


                    // $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->application_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAssign">Un Assign</a>';

                    return $btn;
                })
                ->addColumn('application_status', function ($row) {

                    if ($row->application_status == 'processing') {
                        $badge = 'badge bg-warning';
                    } elseif ($row->application_status == 'Preliminary screening completed') {
                        $badge = 'badge bg-success';
                    } elseif ($row->application_status == 'Preliminary screening rejected') {
                        $badge = 'badge bg-danger';
                    }
                    $btn = "<span class='$badge'>  $row->application_status  </span>";

                    return $btn;
                })
                ->rawColumns(['action', 'application_status'])
                ->make(true);
        }

        //return view('assign_unassign');

        return view('assign_unassign.unassigned', compact('dataa'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $application = applications::where('application_id', $request->application_id)->first();
        if ($application->assigned_To == null) {
            $fresh = true;
        } else {
            $fresh = false;
        }

        $assign = applications::updateOrCreate(
            ['application_id' => $request->application_id],
            ['assigned_To' => $request->assigned_To,
                'assigned_By' => $request->assigned_By,
                'Assginment_Date' => now()],
    );

        $application = applications::where('application_id', $request->application_id)->first();

        $duration_days = 10;

        //MainTaskController::insertTask($task_name, $related_task, $related_id, $duration_days,$start_time,$end_time,$deadline, $task_status, $alert_before_days);

        $main_task = $this->get_main_task_id($application->id, 'Application');
        $end_time = date('Y-m-d H:i:s', strtotime('+ ' . $duration_days . ' days'));
        $issued_datetime = date('Y-m-d H:i:s');
        $task_category = 'Screening';
        $task_activity_title = 'Screening process';
        $content_details = 'Application assigned to assesor for screening ';
        $route_link = '';
        $activity_status = 'Inprogress';
        $uploaded_document_id = null;


        if (!$fresh) {
            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);
        }
        if ($fresh) {
            MainTaskController::insertActivity($main_task->id, $issued_datetime, $end_time, $task_category, $task_activity_title, $content_details, $route_link, $activity_status, $uploaded_document_id);

        }


        if (!$fresh) {
            $tasks = TaskTracker::where('task_id', $main_task->id)
                ->where('task_category', 'Screening')
                ->first();
            TaskTracker::where('id', $tasks->id)
                ->update(
                    [
                        'start_time' => $issued_datetime,
                    ]
                );
        }


        $assign = applications::updateOrCreate(['application_id' => $request->application_id], ['assigned_To' => $request->assigned_To, 'assigned_By' => $request->assigned_By, 'Assginment_Date' => now()], );

        $user = User::where('id', $assign->assigned_To)->first();


        $new_notification = [];


        $new_notification['type'] = 'Notification';
        $new_notification['subject'] = 'New Application Screening Assigned By Supervisor';
        $new_notification['from_user'] = 'System Reminder';
        $new_notification['data'] = 'Appication with application No: ' . $application->application_number . ' has been assigned  by supervisor';
        $new_notification['related_document'] = null;
        $new_notification['related_id'] = $request->application_id;
        $new_notification['alert_level'] = null;
        $new_notification['remark'] = null;
        // ::send($users, new ($invoice));
        if (!$fresh) {
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'New appication with application No: ' . $application->application_number . ' has been assigned by supervisor for screening'));
        } else {

            $new_notification = [];
            $new_notification['type'] = 'Notification';
            $new_notification['subject'] = 'New Application Screening Assigned By Supervisor';
            $new_notification['from_user'] = 'System Reminder';
            $new_notification['data'] = 'Application with application No: ' . $application->application_number . ' has been  re-assigned  by supervisor.';
            $new_notification['related_document'] = null;
            $new_notification['related_id'] = $request->application_id;
            $new_notification['alert_level'] = null;
            $new_notification['remark'] = null;
            Notification::send($user, new ApplicationReceiptionNotification($new_notification));
            event(new ApplicationReceiptionEvent($user->id, 'Application with application No: ' . $application->application_number . ' has been re-assigned by supervisor  for screening.'));

        }

        return response()->json($assign);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        // dd( response()->json($book));
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id)->delete();

        return response()->json($book);
    }
}

?>