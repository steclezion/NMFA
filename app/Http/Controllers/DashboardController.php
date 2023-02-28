<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\applications;
use App\Models\dossier;
use App\Models\dossier_assignment;
use App\Models\dossier_evaluation_progress;
use App\Models\MainTask;
use App\Models\model_has_roles;
use App\Models\QualityControl;
use App\Models\psur;
use Illuminate\Http\Request;
use App\Decision;
use App\Models\Meeting;
use App\Models\Variation;
use App\Models\VariationDecision;
use App\Models\dossier_section_assignment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function get_roles_names($user_id)
    {

        $choose_option_dashs = model_has_roles::join('roles','roles.id','model_has_roles.role_id')
            ->where('model_id',$user_id)
            ->distinct()
            ->orderBy('role_id','ASC')
            ->get();


        $array_roles=array();
        foreach ($choose_option_dashs  as $user)
        {


            array_push($array_roles,$user->name);

        }




        return $array_roles ;
    }

    public  function index(Request $request)
    {

        $roles_names = $this->get_roles_names(auth()->user()->id) ;
        $assessor_ongoing_evaluation_count=$this->assessor_ongoing_dossier(auth()->user()->id);
        $assessor_ongoing_variation=$this->assessor_ongoing_variation(auth()->user()->id);
        $assessor_ongoing_prelimunary_application=$this->assessor_ongoing_prelimunary_application(auth()->user()->id);
        $assessor_ongoing_dossier_section_count=$this->assessor_ongoing__dossier_section(auth()->user()->id);


        $inprogress_applications=$this->inprogress_applications(auth()->user()->id);
        $completed_applications=$this->completed_applications(auth()->user()->id);
        $mah_applications=$this->MAH_applications(auth()->user()->id);
        $due_products=$this->due_products(auth()->user()->id);


        $unassigned_dossiers=$this->unassigned_dossiers(auth()->user()->id);
        $unassigened_preliminary=$this->unassigened_preliminary(auth()->user()->id);
        $deadline_requests=$this->deadline_requests(auth()->user()->id);
        $inspection_sample_test_request_count=$this->inspection_sample_test_request(auth()->user()->id);
        $QC_sample_test_request_count=$this->QC_sample_test_request(auth()->user()->id);



        $request->session()->put('roles_names', $roles_names);


        $notifications=auth()->user()->notifications;
        $notification_count=count($notifications);

        $assigned_psur=$this->assigned_psur(auth()->user()->id);
        $meeting_invitation=$this->meeting_invitation(auth()->user()->id);
        $NMFA_Director_MAH_applications=$this->NMFA_Director_MAH_applications();
        $nmfa_director_pusr_alert=$this->NMFA_Director_notificaitons();

        $ongoing_dossier_evalutions=$this->supervisor_ongoing_dossier(auth()->user()->id);

        return view('dashboard',[
            'roles_names' => @$roles_names,
            'notifications'=>$notifications,
            'notification_count'=>$notification_count,
            'assessor_ongoing_evaluation_count'=>$assessor_ongoing_evaluation_count,
            'assessor_ongoing_dossier_section_count'=>$assessor_ongoing_dossier_section_count,
            'assessor_ongoing_variation'=>$assessor_ongoing_variation,
            'assessor_ongoing_prelimunary_application'=>$assessor_ongoing_prelimunary_application,
            'inprogress_applications'=>$inprogress_applications,
            'completed_applications'=>$completed_applications,
            'mah_applications'=>$mah_applications,
            'due_products'=>$due_products,
            'unassigened_preliminary' =>$unassigened_preliminary,
            'unassigned_dossiers' =>$unassigned_dossiers,
            'deadline'=>$deadline_requests,
            'assigned_psur'=>$assigned_psur,
            'meeting_invitation'=>$meeting_invitation,
            'QC_sample_test_request_count'=>$QC_sample_test_request_count,
            'inspection_sample_test_request_count'=>$inspection_sample_test_request_count,
            'notifications'=>$notifications,
            'notification_count'=>$notification_count,
            'NMFA_Director_MAH_applications_count'=>$NMFA_Director_MAH_applications,
            'ongoing_dossier_evalutions'=>$ongoing_dossier_evalutions,
            'nmfa_director_pusr_alert'=>$nmfa_director_pusr_alert


        ]);
    }
    public function assessor_ongoing_dossier($assessor_id)
    {
        return dossier_assignment::where('assessor_id', $assessor_id)
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->count();
    }
    public function assessor_ongoing_variation($assessor_id)
    {
        return Variation::where('assessor_id', $assessor_id)
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->count();
    }
    public function assessor_ongoing_prelimunary_application($assessor_id)
    {
        return applications::where('assigned_To', $assessor_id)
            ->join('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->where('main_tasks.related_task', 'Application')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->count();
    }
    public function assessor_ongoing__dossier_section($assessor_id)
    {
        return dossier_section_assignment::where('section_to_user_id', $assessor_id)
            ->where('section_received_date',null)
            ->get()->count();
    }






    ///this is code is for applicant dashboard


    public function inprogress_applications($applicant_id)
    {
        $in_evaluation_count= dossier_assignment::join('applications','applications.id','dossier_assignments.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('applications.user_id', $applicant_id)
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->count();
//        $unassigned_count= dossier::join('applications','applications.dossier_id','dossiers.id')
//            ->where("dossiers.assignment_status",1)
//            ->where('applications.user_id', $applicant_id)
//            ->count();

        $priliminary_screening_count=applications::join('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->where('main_tasks.related_task', 'Application')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->where('applications.user_id', $applicant_id)
            ->count();

//        dd($in_evaluation_count,$priliminary_screening_count);
        return $in_evaluation_count + $priliminary_screening_count  ;
    }
    public function completed_applications($applicant_id)
    {
        return dossier_assignment::join('applications','applications.id','dossier_assignments.application_id')
        ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
        ->where('applications.user_id', $applicant_id)
        ->where('main_tasks.related_task', 'Dossier Evaluation')
        ->whereIn("main_tasks.task_status",["Completed","Decision"])
        ->count();
    }


    public function MAH_applications($applicant_id)
    {
        return DB::table('certifications')
            ->join('decisions','decisions.id','certifications.decision_id')
            ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('applications','applications.id','dossier_assignments.application_id')
            ->where('applications.user_id',$applicant_id)
            ->count();
    }
    public function due_products($applicant_id)
    {
        return DB::table('certifications')
            ->join('decisions','decisions.id','certifications.decision_id')
            ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('applications','applications.id','dossier_assignments.application_id')
            ->where('applications.user_id',$applicant_id)
            ->where('certifications.status','reregistration_open')
            ->count();
    }

    public function unassigned_dossiers($supervisor)
    {
        return   dossier::join('dossier_status_lookups', 'dossier_status_lookups.id',
            'dossiers.assignment_status')
            ->join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.id')
            ->where('dossier_status_lookups.id', 1)//for assign
            ->orWhere('dossier_status_lookups.id', 8)//included reassign
            ->count();
    }

    public function unassigened_preliminary($supervisor_id)
    {
        return DB::table('applications')
            ->where('applications.assigned_to',null)
            ->count();
    }
    public function deadline_requests($supervisor_id)
    {


        return dossier_assignment::join('dossier_evaluation_progresses','dossier_evaluation_progresses.dossier_assignment_id','dossier_assignments.id')
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('dossier_assignments.supervisor_id', $supervisor_id)
            ->where('dossier_evaluation_progresses.evaluation_deadline_extended' , 1)
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->where('main_tasks.deadline','<>',null)
            ->count();



    }

    public function assigned_psur($user_id)
    {


        return psur::where('assigned_To',$user_id)
            //->where('psur_review_uploaded_id',null)
            ->count();



    }

    public  function meeting_invitation($user_id)
    {
        return Meeting::where('minutes_id', null)->count();
    }

    public function inspection_sample_test_request($user_id)
    {


        return QualityControl::where('inspection_to_user_id',$user_id)
            ->where('to_qc_document_id',null)
            ->count();



    }
    public function QC_sample_test_request($user_id)
    {


        return QualityControl::where('to_qc_staff_id',$user_id)
            ->where('received_document_id',null)
            ->count();





    }

    public function NMFA_Director_MAH_applications()
    {
        return DB::table('certifications')
            ->join('decisions','decisions.id','certifications.decision_id')
            ->join('dossier_assignments','dossier_assignments.id','decisions.dossier_assignment_id')
            ->join('applications','applications.id','dossier_assignments.application_id')
            ->count();
    }


    public function NMFA_Director_notificaitons()
    {
        return DB::table('psur_alerts')
            ->where('nmfa_director_flag',0)
            ->count();
    }

    public function supervisor_ongoing_dossier($supervisor)
    {
        return dossier_assignment::where('supervisor_id', $supervisor)
            ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
            ->where('main_tasks.related_task', 'Dossier Evaluation')
            ->whereIn("main_tasks.task_status",["Inprogress","pause"])
            ->count();
    }







}
