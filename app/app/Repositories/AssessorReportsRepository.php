<?php


namespace App\Repositories;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Decision;
use App\Models\Meeting;
use App\Models\QualityControl;
use App\Models\VariationDecision;
use App\Repositories\Interfaces\AssessorReportsRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\False_;


class AssessorReportsRepository implements AssessorReportsRepositoryInterface
{


    public function all()
    {
        return User::all();
    }

    public function getByUser(User $user)
    {
        return User::where('id', $user->id)->get();
    }


    public function listAssessors()
    {
        // return assessor model
        return DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Assessor')
            ->get();

    }

    public function listApplicants()
    {
        // return assessor model
        return DB::table('company_supplier_template')->get();

    }

    public function listPercMembers()
    {
        // PERC members
        return DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'PERC')
            ->get();
    }

    public function companyCountries()
    {
        return DB::table('countries')->get();
    }


    //merhawi's code
    public function evaluation_tasks($user, $start_time, $end_time, $task_status, $report_type, $registration_route = [], $registration_type = [])
    {

        if ($report_type == 'AssessorTask') {


            $evaluation_details = DB::table('dossier_assignments')
                ->join('dossiers', 'dossier_assignments.dossier_id', 'dossiers.id')
                ->join('applications', 'dossier_assignments.application_id', 'applications.id')
                ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                ->join('users as assessor', 'assessor.id', 'dossier_assignments.assessor_id')
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->whereIn("main_tasks.task_status", $task_status)
                ->whereBetween("main_tasks.start_time", [$start_time, $end_time])
                ->whereIn('dossier_assignments.assessor_id', $user)
                ->select('main_tasks.related_task', 'dossiers.dossier_ref_num as reference_number', 'applications.id as app_id', 'assessor.first_name as assessor_first_name', 'assessor.id as assessor_id',
                    'assessor.middle_name as assessor_middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status', 'main_tasks.task_duration_days_actual')
                ->get();


            return ($this->merge_reports($evaluation_details, 'Evaluation Tasks'));

        } elseif ($report_type == 'EvaluationStatus') {

            $evaluation_details = DB::table('dossier_assignments')
                ->join('dossiers', 'dossier_assignments.dossier_id', 'dossiers.id')
                ->join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
                ->join('applications', 'dossier_assignments.application_id', 'applications.id')
                ->leftjoin('users as assessor', 'assessor.id', 'dossier_assignments.assessor_id')
                ->leftJoin('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                //->whereIn("dossier_status_lookups.status", $task_status)
                ->WhereIn("main_tasks.task_status", $task_status)
                ->whereIn("applications.application_type", $registration_route)
                ->whereIn("applications.registration_type", $registration_type)
                ->whereBetween("dossiers.created_at", [$start_time, $end_time])
                ->whereIn('dossier_assignments.assessor_id', $user)
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->select('main_tasks.related_task', 'main_tasks.task_duration_days_actual',
                    'dossiers.dossier_ref_num as reference_number', 'applications.id as app_id',
                    'assessor.first_name as assessor_first_name', 'assessor.id as assessor_id',
                    'assessor.middle_name as assessor_middle_name',
                    'dossiers.created_at as start_time', 'dossiers.created_at as end_time',
                    'main_tasks.task_status as task_status', 'applications.application_type', 'applications.registration_type')
                ->distinct('dossier_assignments.id')
                ->get();

            return ($this->merge_reports($evaluation_details, 'Evaluation Tasks'));
        }
    }

    public function dossier_section_assignment_tasks($user, $start_time, $end_time, $task_status)
    {
        $dessier_section_assignment_details = DB::table('dossier_section_assignments')
            ->join('dossier_assignments', 'dossier_assignments.id', 'dossier_section_assignments.section_related_id')
            ->join('dossiers', 'dossier_assignments.dossier_id', 'dossiers.id')
            ->join('applications', 'dossier_assignments.application_id', 'applications.id')
            ->join('users as assessor', 'assessor.id', 'dossier_section_assignments.section_to_user_id')
            ->whereBetween("dossier_section_assignments.section_sent_date", [$start_time, $end_time])
            ->whereIn("dossier_section_assignments.status", $task_status)
            ->whereIn('dossier_section_assignments.section_to_user_id', $user)
            ->select('dossier_section_assignments.assignment_description as related_task',
                'dossier_section_assignments.section_received_date as task_duration_days_actual',
                'dossiers.dossier_ref_num as reference_number', 'applications.id as app_id', 'dossier_section_assignments.section_sent_date as start_time',
                'dossier_section_assignments.section_received_date as end_time', 'dossier_section_assignments.status as task_status',
                'assessor.first_name as assessor_first_name', 'assessor.id as assessor_id', 'assessor.middle_name as assessor_middle_name')
            ->get();
        return ($this->merge_reports($dessier_section_assignment_details, 'Dossier Section Assignment Tasks'));


    }

    public function variation_tasks($user, $start_time, $end_time, $task_status)
    {


        if (in_array('Unassigned', $task_status)) {
            array_push($task_status, 'Pending');
            //Add Pending status for Unassigned Variations because the Unassigned status in main_task for variations is 'Pending'
        }
        if (in_array('Completed', $task_status)) {
            array_push($task_status, 'Decision');
            //Add Decision status for completed variations but now forwarded to Decision
        }


        $variation_details = DB::table('variations')
            ->leftjoin('certifications', 'certifications.id', 'variations.certificate_id')
            ->join('decisions', 'decisions.id', 'certifications.decision_id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->leftjoin('users as assessor', 'assessor.id', 'variations.assessor_id') // leftjoin since pending variations have null assessor_id
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn("main_tasks.task_status", $task_status)
            ->whereBetween("main_tasks.start_time", [$start_time, $end_time])
            ->select('main_tasks.related_task', 'variations.variation_reference_number as reference_number', 'variations.id as var_id',
                'applications.id as app_id', 'assessor.first_name as assessor_first_name', 'assessor.id as assessor_id',
                'assessor.middle_name as assessor_middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status', 'main_tasks.task_duration_days_actual')
            ->get();

        return ($this->merge_reports($variation_details, 'Variation Tasks'));


    }

    public function screening_tasks($user, $start_time, $end_time, $task_status)
    {
        $screening_details = DB::table('applications')
            ->join('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->join('users as assessor', 'assessor.id', 'applications.assigned_To')
            ->where('main_tasks.related_task', 'Application')
            ->whereIn("main_tasks.task_status", $task_status)
            ->whereBetween("main_tasks.start_time", [$start_time, $end_time])
            ->whereIn('applications.assigned_To', $user)
            ->select('main_tasks.related_task', 'applications.application_number as reference_number', 'applications.id as app_id', 'assessor.first_name as assessor_first_name', 'assessor.id as assessor_id',
                'assessor.middle_name as assessor_middle_name', 'main_tasks.start_time', 'main_tasks.end_time', 'main_tasks.task_status', 'main_tasks.task_duration_days_actual')
            ->get();
        return ($this->merge_reports($screening_details, 'Screening Tasks'));
    }

    public function psur_tasks($user, $start_time, $end_time, $task_status)
    {
        // TODO - consult sase
        $psur_details = DB::table('applications')
            ->join('psurs', 'applications.application_id', 'psurs.application_id')
            //->join('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->join('users as assessor', 'assessor.id', 'applications.assigned_To')
            //->where('main_tasks.related_task', 'PSUR') //TODO not found in main_task
            //->whereIn("main_tasks.task_status", $task_status)
            //->whereBetween("main_tasks.start_time", [$start_time, $end_time])
            ->whereIn('applications.assigned_To', $user)
            ->select(
                'psurs.psur_refrence_number as reference_number',
                'applications.id as app_id', 'assessor.first_name as assessor_first_name',
                'assessor.id as assessor_id', 'assessor.middle_name as assessor_middle_name'
            //'main_tasks.related_task','main_tasks.start_time', 'main_tasks.end_time',
            // 'main_tasks.task_status', 'main_tasks.task_duration_days_actual'
            )
            ->get();

        // return ($this->merge_reports($psur_details, 'PSUR Tasks'));

    }

    public function get_application_info($application_id)
    {

        return DB::table('applications')
            ->join('users as contact_person', 'contact_person.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
            ->join('agents', 'agents.id', 'applications.agent_id')
            ->leftjoin('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
            ->where('applications.id', $application_id)
            ->select(
                'dosage_forms.name as dosage_name', 'company_suppliers.trade_name as company_name', 'applications.agent_id',
                'applications.id as app_id', 'applications.user_id as applicant_id', 'contact_person.first_name as contact_person_first_name', 'contact_person.middle_name as contact_person_middle_name',
                'applications.application_number', 'applications.application_type', 'medicinal_products.product_trade_name', 'medicines.product_name', 'agents.trade_name as agent_trade_name')->first();


    }

//    public function merge_reports($evaluation_details)
//    {
//
//        $task_type = [];
//
//
//        $data=[];
//        foreach ($evaluation_details as $evaluation_detail) {
//            $temp_data = [];
//            $full_name = $evaluation_detail->assessor_first_name . ' ' . $evaluation_detail->assessor_middle_name;
//            $application_details = $this->get_application_info($evaluation_detail->app_id);
//            $temp_data['reference_number'] = $evaluation_detail->reference_number;
//            $temp_data['full_name'] = $full_name;
//            $temp_data['assessor_id'] = $evaluation_detail->assessor_id;
//            $temp_data['related_task'] = ( $evaluation_detail->related_task ? $evaluation_detail->related_task : " ");
//            $temp_data['product_name'] = $application_details->product_trade_name;
//            $temp_data['status'] = $evaluation_detail->task_status;
//
//            if($application_details->application_type==1)
//            {
//                $appl_type="Standard Mode";
//            }
//            else if( $application_details->application_type == 2)
//            {
//                $appl_type="Fast Track Mode";
//            }
//            else{
//                $appl_type="You have error in merge reports in AssessorReportRepository";
//            }
//            $temp_data['application_type'] = $appl_type;
//            $temp_data['company_name'] = $application_details->company_name;
//            $temp_data['start_date'] = Carbon::create($evaluation_detail->start_time)->format('d-M-Y');
//            $temp_data['end_date'] = Carbon::create($evaluation_detail->end_time)->format('d-M-Y');
//
//             $time_lapse=
//                Carbon::create($evaluation_detail->start_time)->diff(Carbon::create($evaluation_detail->task_duration_days_actual), false);
//            $temp_data['task_duration_days_actual'] = $time_lapse->format('%mm:%dd:%HHr');
//
//
//            /*dump(Carbon::create($evaluation_detail->start_time) , $evaluation_detail->task_duration_days_actual,
//                Carbon::create($evaluation_detail->task_duration_days_actual));
//            dd( $temp_data['task_duration_days_actual']);*/
//
//            $data[] = $temp_data;
//
////            $i++;
//        }
//
//
//        return $data;
//
//    }

    public function merge_reports($evaluation_details, $task_type)
    {


        $task_types [$task_type] = count($evaluation_details);

        $data = [];
        foreach ($evaluation_details as $evaluation_detail) {


            $temp_data = [];
            $full_name = $evaluation_detail->assessor_first_name . ' ' . $evaluation_detail->assessor_middle_name;
            $application_details = $this->get_application_info($evaluation_detail->app_id);

            $temp_data['reference_number'] = $evaluation_detail->reference_number;
            $temp_data['full_name'] = $full_name;
            $temp_data['assessor_id'] = $evaluation_detail->assessor_id;
            if ($task_type == 'Dossier Section Assignment Tasks') {
                $temp_data['related_task'] = 'Dossier Section Assignment';
            } else {
                $temp_data['related_task'] = ($evaluation_detail->related_task ? $evaluation_detail->related_task : " ");
            }


            $temp_data['product_name'] = $application_details->product_trade_name;
            $temp_data['generic_name'] = $application_details->product_name;
            $temp_data['status'] = $evaluation_detail->task_status;

            if ($application_details->application_type == 1) {
                $appl_type = "Standard Mode";
            } else if ($application_details->application_type == 2) {
                $appl_type = "Fast Track Mode";
            } else {
                $appl_type = "You have error in merge reports in AssessorReportRepository";
            }
            $temp_data['application_type'] = $appl_type;
            $temp_data['company_name'] = $application_details->company_name;
            $temp_data['start_date'] = Carbon::create($evaluation_detail->start_time)->format('d-M-Y');
            $temp_data['end_date'] = Carbon::create($evaluation_detail->end_time)->format('d-M-Y');
            $temp_data['actual_end_date'] = Carbon::create($evaluation_detail->task_duration_days_actual)->format('d-M-Y');

            $time_lapse =
                Carbon::create($evaluation_detail->start_time)->diff(Carbon::create($evaluation_detail->task_duration_days_actual), false);
            $temp_data['task_duration_days_actual'] = $time_lapse->format('%mm:%dd:%Hhr');


            $data[] = $temp_data;

        }


        return array($data, $task_types);

    }


    public function list_tasks_by_assesor($data, $assessors_percs)
    {

        $return_data = [];

        foreach ($assessors_percs as $assessors_perc) {

            foreach ($data as $task_type) {


                foreach ($task_type[0] as $task) {

                    if ($task['assessor_id'] == $assessors_perc) {
                        $return_data[$task['full_name']][] = $task;
                    }

                }

            }
        }

        return ($return_data);

    }

    public function unassigned_dossiers($user, $carbon_start_time, $carbon_end_time, $task_status, $registration_route, $registration_type)
    {


        $evaluation_details = DB::table('dossiers')
            ->join('dossier_status_lookups', 'dossier_status_lookups.id', 'dossiers.assignment_status')
            ->join('applications', 'dossiers.id', 'applications.dossier_id')
            ->leftJoin('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->whereIn("dossier_status_lookups.status", $task_status)
            ->whereIn("applications.application_type", $registration_route)
            ->whereIn("applications.registration_type", $registration_type)
            ->where('main_tasks.related_task', 'Application')
            ->select('main_tasks.related_task', 'dossiers.dossier_ref_num as reference_number', 'applications.id as app_id', 'dossiers.created_at as start_time',
                'dossiers.created_at as end_time', 'dossier_status_lookups.status as task_status',
                'applications.application_type', 'applications.registration_type', 'applications.application_number')
            ->distinct('applications.id')
            ->get();


        $task_types = array();
        $task_types['Unassigned Dossiers'] = count($evaluation_details);

        $data = [];
        foreach ($evaluation_details as $evaluation_detail) {
            $temp_data = [];
            $full_name = '';
            $application_details = $this->get_application_info($evaluation_detail->app_id);

            $temp_data['reference_number'] = $evaluation_detail->application_number;
            $temp_data['full_name'] = $full_name;
            $temp_data['assessor_id'] = null;
            $temp_data['related_task'] = "Dossier Evaluation";
            $temp_data['product_name'] = $application_details->product_trade_name;
            $temp_data['generic_name'] = $application_details->product_name;
            $temp_data['status'] = 'Unassigned';
            if ($application_details->application_type == 1) {
                $appl_type = "Standard Mode";
            } else if ($application_details->application_type == 2) {
                $appl_type = "Fast Track Mode";
            } else {
                $appl_type = "You have error in merge reports in AssessorReportRepository";
            }
            $temp_data['application_type'] = $appl_type;
            $temp_data['company_name'] = $application_details->company_name;
            $temp_data['start_date'] = Carbon::create($evaluation_detail->start_time)->format('d-M-Y');
            $temp_data['end_date'] = Carbon::create($evaluation_detail->end_time)->format('d-M-Y');

            $data[] = $temp_data;
        }


        return array($data, $task_types);
    }


    public function get_meeting_info($start_time, $end_time, $meeting_type, $decision_type)
    {

        $registration_meeting_details = [];
        $other_meeting = [];

        if (in_array('Decision_Meeting', $meeting_type)) {
            $registration_meeting_details = DB::table('meetings')
                ->join('decisions', 'decisions.meeting_id', 'meetings.id')
                ->where('meetings.type', 'Decision_Meeting')
                ->whereBetween('meetings.meeting_date', [Carbon::create($start_time), Carbon::create($end_time)])
                ->whereIn('decisions.decision_status', $decision_type)
                ->select('meetings.*')
                ->distinct('meetings.id')
                ->get();
        }
        if (in_array('Other_Meeting', $meeting_type)) {

            $other_meeting = DB::table('meetings')
                ->leftJoin('variation_decisions', 'variation_decisions.meeting_id', 'meetings.id')
                ->leftJoin('variations', 'variation_decisions.variation_id', 'variations.id')
                ->leftJoin('certifications', 'certifications.id', 'variations.certificate_id')
                ->leftJoin('decisions', 'decisions.id', 'certifications.decision_id')
                ->leftJoin('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->leftJoin('applications', 'applications.id', 'dossier_assignments.application_id')
                ->where('meetings.type', 'Other_Meeting')
                ->whereBetween('meetings.meeting_date', [Carbon::create($start_time), Carbon::create($end_time)])
                ->orWhereIn('variation_decisions.decision_status', $decision_type)
                ->select('meetings.*')
                ->distinct('meetings.id')
                ->get();

        }

        return ($this->get_decision_details($registration_meeting_details, $other_meeting, $decision_type));


    }

    public function get_decision_details($regisration_meeting_details, $other_meeting_details, $decision_type)
    {
        $data = null;


        foreach ($regisration_meeting_details as $regisration_meeting_detail) {
            $decsion_details = DB::table('decisions')
                ->where('decisions.meeting_id', $regisration_meeting_detail->id)
                ->whereIn('decision_status', $decision_type)
                ->get();


            $temp = null;
            $accepted_count = 0;
            $rejected_count = 0;
            $deferred_count = 0;
            $not_decided_count = 0;
            $appeal_accepted_count = 0;
            $total = 0;
            $appeal_rejected_count = 0;


            foreach ($decsion_details as $decsion_detail) {
                $temp[$decsion_detail->id]['decision_status'] = $decsion_detail->decision_status;
                $total++;
                if ($decsion_detail->decision_status == 'Accepted') {
                    $accepted_count++;
                } elseif ($decsion_detail->decision_status == 'Rejected' or
                    $decsion_detail->decision_status == 'Reassign' or $decsion_detail->decision_status == 'Reassigned') {
                    $rejected_count++;
                    if ($decsion_detail->appeal_status == null) {

                    } elseif ($decsion_detail->appeal_status == 'Accepted') {
                        $appeal_accepted_count++;
                    } elseif ($decsion_detail->appeal_status == 'Rejected') {
                        $appeal_rejected_count++;
                    }
                } elseif ($decsion_detail->decision_status == 'Deferred') {
                    $deferred_count++;
                } else {
                    $not_decided_count++;

                }

                $temp[$decsion_detail->id]['appeal_status'] = $decsion_detail->appeal_status;

            }

            $data[$regisration_meeting_detail->id]['meeting_id'] = $regisration_meeting_detail->id;
            $data[$regisration_meeting_detail->id]['type'] = $regisration_meeting_detail->type;
            $data[$regisration_meeting_detail->id]['venue'] = $regisration_meeting_detail->venue;
            $data[$regisration_meeting_detail->id]['meeting_description'] = $regisration_meeting_detail->description;
            $data[$regisration_meeting_detail->id]['meeting_date'] = $regisration_meeting_detail->meeting_date;
            $data[$regisration_meeting_detail->id]['time'] = $regisration_meeting_detail->time;
            $data[$regisration_meeting_detail->id]['decisions'] = $temp;
            $data[$regisration_meeting_detail->id]['accepted_count'] = $accepted_count;
            $data[$regisration_meeting_detail->id]['rejected_count'] = $rejected_count;
            $data[$regisration_meeting_detail->id]['deferred_count'] = $deferred_count;
            $data[$regisration_meeting_detail->id]['not_decided_count'] = $not_decided_count;

            $data[$regisration_meeting_detail->id]['total'] = $total;
            $data[$regisration_meeting_detail->id]['appeal_accepted_count'] = $appeal_accepted_count;
            $data[$regisration_meeting_detail->id]['appeal_rejected_count'] = $appeal_rejected_count;

        }

        foreach ($other_meeting_details as $other_meeting_detail) {
            $variation_decsion_details = DB::table('variation_decisions')
                ->where('variation_decisions.meeting_id', $other_meeting_detail->id)
                ->whereIn('decision_status', $decision_type)
                ->get();
            $temp = [[]];
            $accepted_count = 0;
            $rejected_count = 0;
            $not_decided_count = 0;
            $total = 0;
            $appeal_accepted_count = 0;
            $appeal_rejected_count = 0;
            foreach ($variation_decsion_details as $variation_decsion_detail) {

                $temp[$variation_decsion_detail->id]['decision_status'] = $variation_decsion_detail->decision_status;
                $temp[$variation_decsion_detail->id]['appeal_status'] = $variation_decsion_detail->appeal_status;
                $total++;
                if ($variation_decsion_detail->decision_status == 'Accepted') {
                    $accepted_count++;
                } elseif ($variation_decsion_detail->decision_status == 'Rejected') {
                    $rejected_count++;
                    if ($variation_decsion_detail->appeal_status == null) {

                    } elseif ($variation_decsion_detail->appeal_status == 'Accepted') {
                        $appeal_accepted_count++;
                    } elseif ($variation_decsion_detail->appeal_status == 'Rejected') {
                        $appeal_rejected_count++;
                    }
                } else {
                    $not_decided_count++;

                }


            }
            $data[$other_meeting_detail->id]['meeting_id'] = $other_meeting_detail->id;
            $data[$other_meeting_detail->id]['type'] = $other_meeting_detail->type;
            $data[$other_meeting_detail->id]['venue'] = $other_meeting_detail->venue;
            $data[$other_meeting_detail->id]['meeting_description'] = $other_meeting_detail->description;
            $data[$other_meeting_detail->id]['meeting_date'] = $other_meeting_detail->meeting_date;
            $data[$other_meeting_detail->id]['time'] = $other_meeting_detail->time;
            $data[$other_meeting_detail->id]['decisions'] = $temp;
            $data[$other_meeting_detail->id]['accepted_count'] = $accepted_count;
            $data[$other_meeting_detail->id]['rejected_count'] = $rejected_count;
            $data[$other_meeting_detail->id]['not_decided_count'] = $not_decided_count;
            $data[$other_meeting_detail->id]['appeal_accepted_count'] = $appeal_accepted_count;
            $data[$other_meeting_detail->id]['appeal_rejected_count'] = $appeal_rejected_count;

            $data[$other_meeting_detail->id]['total'] = $total;
        }

        return $data;

    }


    public function get_appeal_details($start_date, $end_date, $appeal_decision_type)
    {

        $product_decisions = DB::table('decisions')
            ->leftJoin('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->leftJoin('applications', 'applications.id', 'dossier_assignments.application_id')
            ->whereBetween('decisions.appeal_decision_date', [$start_date, $end_date])
            ->whereIn('decisions.appeal_status', $appeal_decision_type)
            ->select('applications.id as app_id', 'decisions.*')
            ->get();
        $variation_decisions = DB::table('variation_decisions')
            ->leftJoin('variations', 'variation_decisions.variation_id', 'variations.id')
            ->leftJoin('certifications', 'certifications.id', 'variations.certificate_id')
            ->leftJoin('decisions', 'decisions.id', 'certifications.decision_id')
            ->leftJoin('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->leftJoin('applications', 'applications.id', 'dossier_assignments.application_id')
            ->whereBetween('variation_decisions.appeal_decision_date', [$start_date, $end_date])
            ->whereIn('variation_decisions.appeal_status', $appeal_decision_type)
            ->select('applications.id as app_id', 'variation_decisions.*')
            ->get();
        $data = null;
        foreach ($product_decisions as $product_decision) {
            $temp_data = [];
            $application_details = $this->get_application_info($product_decision->app_id);
            $temp_data['product_name'] = $application_details->product_trade_name;
            $temp_data['generic_name'] = $application_details->product_name;
            $temp_data['company_name'] = $application_details->company_name;
            $temp_data['agent_trade_name'] = $application_details->agent_trade_name;
            $temp_data['decision_date'] = $product_decision->appeal_decision_date;
            $temp_data['type'] = 'Registration Decision';
            $temp_data['status'] = $product_decision->appeal_status;

            $data['Product Registration' . $product_decision->id] = $temp_data;
        }
        foreach ($variation_decisions as $variation_decision) {
            $temp_data = [];
            $application_details = $this->get_application_info($variation_decision->app_id);
            $temp_data['product_name'] = $application_details->product_trade_name;
            $temp_data['generic_name'] = $application_details->product_name;
            $temp_data['company_name'] = $application_details->company_name;
            $temp_data['agent_trade_name'] = $application_details->agent_trade_name;
            $temp_data['decision_date'] = $variation_decision->appeal_decision_date;
            $temp_data['type'] = 'Variation Decision';
            $temp_data['status'] = $variation_decision->appeal_status;

            $data['Variation Decision' . $variation_decision->id] = $temp_data;
        }

        return $data;

    }

    public function get_processed_apps($start_date, $end_date, $decision_type, $registration_route, $application_type, $applicant, $country)
    {

        $processed_products = DB::table('decisions')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('agents', 'agents.id', 'applications.agent_id')
            ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->whereBetween('decisions.decision_date', [$start_date, $end_date])
            ->whereIn('decisions.decision_status', $decision_type)
            ->whereIn('applications.application_type', $registration_route)
            ->whereIn('applications.registration_type', $application_type)
            ->whereIn('company_suppliers.trade_name', $applicant)
            ->whereIn('company_suppliers.country_id', $country)
            ->select('applications.id as app_id', 'applications.application_type', 'applications.registration_type', 'decisions.decision_status',
                'medicinal_products.product_trade_name', 'medicines.product_name as generic_name',
                'company_suppliers.trade_name', 'countries.country_name', 'decisions.decision_date', 'agents.trade_name as agent_trade_name')
            ->get();

        return AssessorReportsRepository::merge_processed_product($processed_products, 'Product Registration');

    }


    public function get_received_apps($start_date, $end_date, $status_type, $registration_route, $application_type, $applicant, $country)
    {


        $received_applications = DB::table('applications')
            ->leftjoin('manufacturers', 'applications.application_id', '=', 'manufacturers.application_id')
            ->leftjoin('medicinal_products', 'applications.application_id', '=', 'medicinal_products.application_id')
            ->leftjoin('company_suppliers', 'applications.application_id', '=', 'company_suppliers.application_id')
            ->leftjoin('agents', 'agents.id', 'applications.agent_id')
            ->leftjoin('invoices', 'applications.application_id', '=', 'invoices.application_id')
            ->leftjoin('medicines', 'medicinal_products.medicine_id', '=', 'medicines.id')
            ->leftjoin('checklists', 'checklists.application_id', 'applications.application_id')
            ->leftjoin('contacts', 'applications.application_id', '=', 'contacts.application_id')
            ->join('main_tasks', 'main_tasks.related_id', 'applications.id')
            ->Join('countries', 'countries.id', 'company_suppliers.country_id')
            ->whereBetween('applications.created_at', [$start_date, $end_date])
            ->whereIn('applications.application_status', $status_type)
            ->whereIn('applications.application_type', $registration_route)
            ->whereIn('applications.registration_type', $application_type)
            ->whereIn('company_suppliers.trade_name', $applicant)
            ->whereIn('company_suppliers.country_id', $country)
            ->where('contacts.contact_type', 'Supplier')
            ->where('main_tasks.related_task', 'Application')
            ->select('applications.id as app_id',
                'applications.application_number as reference_number',
                'applications.application_type',
                'applications.application_status',
                'applications.registration_type',
                'agents.trade_name as local_agent_company_name',
                'medicinal_products.product_trade_name',
                'medicines.product_name as generic_name',
                'company_suppliers.trade_name', 'countries.country_name',
                'applications.created_at as app_created_at')
            ->distinct('app_id')
            ->get();


        return $this->merge_received_product($received_applications, 'Product Registration');

    }

    public function get_received_variations($start_date, $end_date, $applicant, $country)
    {



        $received_variations = DB::table('variations')
            ->Join('certifications', 'certifications.id', 'variations.certificate_id')
            ->Join('decisions', 'decisions.id', 'certifications.decision_id')
            ->Join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->Join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->Join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->leftjoin('agents', 'agents.id', 'applications.agent_id')
            ->Join('countries', 'countries.id', 'company_suppliers.country_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->where('main_tasks.related_task', 'Variation')
            ->whereBetween('variations.created_at', [$start_date, $end_date])
            ->whereIn('company_suppliers.trade_name', $applicant)
            ->whereIn('company_suppliers.country_id', $country)
            ->select('applications.id as app_id', 'applications.application_number', 'applications.application_type',
                'applications.registration_type',
                'agents.trade_name as local_agent_company_name',
                'medicinal_products.product_trade_name', 'medicines.product_name as generic_name', 'company_suppliers.trade_name',
                'countries.country_name',
                'variations.created_at as app_created_at', 'variations.status as application_status2',
                'variations.variation_reference_number as reference_number',
                'main_tasks.task_status as application_status')
            ->get();



        return $this->merge_received_product($received_variations, 'Variation Registration');

    }


    public function get_processed_variations($start_date, $end_date, $decision_type, $registration_route, $application_type, $applicant, $country)
    {

        $processed_variations = DB::table('variation_decisions')
            ->Join('variations', 'variation_decisions.variation_id', 'variations.id')
            ->Join('certifications', 'certifications.id', 'variations.certificate_id')
            ->Join('decisions', 'decisions.id', 'certifications.decision_id')
            ->Join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->Join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->Join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->leftjoin('agents', 'agents.id', 'applications.agent_id')
            ->Join('countries', 'countries.id', 'company_suppliers.country_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->whereBetween('variation_decisions.decision_date', [$start_date, $end_date])
            ->whereIn('variation_decisions.decision_status', $decision_type)
            ->whereIn('applications.application_type', $registration_route)
            ->whereIn('company_suppliers.trade_name', $applicant)
            ->whereIn('company_suppliers.country_id', $country)
            ->select('applications.id as app_id', 'applications.application_type',
                'applications.registration_type', 'variation_decisions.decision_status',
                'agents.trade_name as local_agent_company_name',
                'medicinal_products.product_trade_name', 'medicines.product_name as generic_name', 'company_suppliers.trade_name',
                'countries.country_name', 'variation_decisions.decision_date')
            ->get();


        return $this->merge_processed_product($processed_variations, 'Variation Registration');

    }

    public function merge_received_product($data, $process_type)
    {
        $data_array = [];
        foreach ($data as $processed_data) {
            if ($processed_data->application_type == 1) {
                $appl_type = "Standard Mode";
            } else if ($processed_data->application_type == 2) {
                $appl_type = "Fast Track Mode";
            } else {
                $appl_type = "You have error in merge reports in AssessorReportRepository";
            }
            $temp_data = [];
            if ($process_type == 'Product Registration' and $processed_data->registration_type == 'New') {
                $registration_type = 'Registration';
            } elseif ($process_type == 'Product Registration' and $processed_data->registration_type == 'Re-new') {
                $registration_type = 'Re-registration';
            } else {
                $registration_type = $process_type;
            }



            //$temp_data['application_number']=$processed_data->application_number;
            $temp_data['reference_number'] = $processed_data->reference_number;
            $temp_data['application_status'] = $processed_data->application_status;
            $temp_data['trade_name'] = $processed_data->trade_name;
            $temp_data['application_created_date'] = $processed_data->app_created_at;
            $temp_data['country_name'] = $processed_data->country_name;
            $temp_data['application_type'] = $appl_type;
            $temp_data['process_type'] = $registration_type;
            $temp_data['product_name'] = $processed_data->product_trade_name;
            $temp_data['generic_name'] = $processed_data->generic_name;
            $temp_data['local_agent_company_name'] = $processed_data->local_agent_company_name;


            $data_array[] = $temp_data;

        }
        return $data_array;
    }


    public function merge_processed_product($data, $process_type)
    {

        $data_array = [];

        foreach ($data as $processed_data) {
            if ($processed_data->application_type == 1) {
                $appl_type = "Standard Mode";
            } else if ($processed_data->application_type == 2) {
                $appl_type = "Fast Track Mode";
            } else {
                $appl_type = "You have error in merge reports in AssessorReportRepository";
            }
            $temp_data = [];
            if ($process_type == 'Product Registration' and $processed_data->registration_type == 'New') {
                $registration_type = 'Product Registration';
            } elseif ($process_type == 'Product Registration' and $processed_data->registration_type == 'Re-new') {
                $registration_type = 'Product Re-registration';
            } else {
                $registration_type = $process_type;
            }


            $temp_data['decision_status'] = $processed_data->decision_status;
            $temp_data['trade_name'] = $processed_data->trade_name;
            $temp_data['decision_date'] = $processed_data->decision_date;
            $temp_data['country_name'] = $processed_data->country_name;
            $temp_data['application_type'] = $appl_type;
            $temp_data['process_type'] = $registration_type;
            $temp_data['product_name'] = $processed_data->product_trade_name;
            $temp_data['generic_name'] = $processed_data->generic_name;
            if (@$processed_data->local_agent_company_name) {
                $temp_data['agent_trade_name'] = $processed_data->local_agent_company_name;
            }
            if (@$processed_data->agent_trade_name) {
                $temp_data['agent_trade_name'] = $processed_data->agent_trade_name;
            }


            $data_array[] = $temp_data;

        }

        return $data_array;
    }

    public function get_processed_psurs($start_date, $end_date, $decision_type, $registration_route, $application_type, $applicant, $country)
    {
        // TODO: Implement get_processed_psurs() method.
    }

    public function list_sample_tests_by_product($start_time, $end_time)
    {

        $results = DB::table('quality_controls')
            ->join('dossier_assignments', 'dossier_assignments.id', 'quality_controls.qc_related_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->whereBetween('quality_controls.inspection_sent_date', [Carbon::create($start_time), Carbon::create($end_time)])
            ->where('quality_controls.qc_received_date', '<>', null)
            ->select('applications.application_number', 'medicinal_products.product_trade_name', 'medicines.product_name',
                'medicines.id as medicine_id', 'company_suppliers.trade_name as company_name',
                'quality_controls.inspection_sent_date', 'quality_controls.qc_received_date')
            ->get();


        // for display(view) create a 2d array from the above 1d query result
        // the index of outer array is product_name, the inner array will contain multiple
        // items of this product_name (could be sample tests of different applications, or same application multiple requests)
        $sample_tests = array();

        foreach ($results as $result) {

            foreach ($result as $k => $v) {

                if ($k == 'product_name') {

                    $time_lapse = Carbon::create($result->inspection_sent_date)->diff(Carbon::create($result->qc_received_date), false);
                    //header of outer array is value of index product_name
                    // then create another inner array within this index, containing details of this product_name
                    // if another entry is found for the same product_name, it is created as another inner array inside the product_name
                    $sample_tests[$v][] = array(
                        'medicine_id' => $result->medicine_id,
                        'application_number' => $result->application_number,
                        'product_trade_name' => $result->product_trade_name,
                        'company_name' => $result->company_name,
                        'inspection_sent_date' => Carbon::create($result->inspection_sent_date)->format('M d, Y'),
                        'qc_received_date' => Carbon::create($result->qc_received_date)->format('M d, Y'),
                        'time_lapse' => $time_lapse->format('%mm:%dd:%HHr')
                    );
                }
            }

        }//first foreach

        // to read this 2d array
//       foreach ($output as $key_outer => $inner_array){
//           //dump($key_outer);   // this is the product_name  (outer array)
//            foreach($inner_array as $key_inner => $details){
//                // dump($details['medicine_id']);   //use index name to get the values of the inner array
//            }
//        }

        return $sample_tests;

    }

    public function list_sample_tests($start_time, $end_time)
    {

        $results = DB::table('quality_controls')
            ->join('dossier_assignments', 'dossier_assignments.id', 'quality_controls.qc_related_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->whereBetween('quality_controls.inspection_sent_date', [Carbon::create($start_time), Carbon::create($end_time)])
            ->where('quality_controls.qc_received_date', '<>', null)  // test must have been completed (report from QC sent)
            ->select('applications.application_number', 'medicinal_products.product_trade_name', 'medicines.product_name',
                'medicines.id as medicine_id', 'company_suppliers.trade_name as company_name',
                'quality_controls.inspection_sent_date', 'quality_controls.qc_received_date')
            ->get();

        return $results;


    }

    public function count_sample_test_requests_by_year()
    {

        // count sample test sent to QC by year
        // This query does not consider whether result is received or not

        return QualityControl::selectRaw('year(inspection_sent_date) as year , count(*) as count')
            ->groupBy('year')
            ->get();

    }


    public function count_sample_test_requests_by_year2($start_date, $end_date)
    {

        // count sample test sent to QC by year
        // This query does not consider whether result is received or not

        return QualityControl::selectRaw('year(inspection_sent_date) as year , count(*) as count')
            ->whereBetween('inspection_sent_date', [Carbon::create($start_date), Carbon::create($end_date)])
            ->groupBy('year')
            ->get();

    }

    public function count_perc_meetings_by_year($start_date, $end_date)
    {

        return Meeting::selectRaw('year(meeting_date) as year , count(*) as count')
            ->whereBetween('meeting_date', [Carbon::create($start_date), Carbon::create($end_date)])
            ->groupBy('year')
            ->get();

    }

    public function count_appeals_by_year($start_date, $end_date)
    {

        $application_decision_appeals = Decision::selectRaw('year(appeal_decision_date) as year , count(*) as count')
            ->whereBetween('appeal_decision_date', [$start_date, $end_date])
            ->groupBy('year')
            ->get();

        $variation_appeals = VariationDecision::selectRaw('year(appeal_decision_date) as year , count(*) as count')
            ->whereBetween('appeal_decision_date', [$start_date, $end_date])
            ->groupBy('year')
            ->get();

        $all_appeals = array();

        if (isset($application_decision_appeals)) {
            foreach ($application_decision_appeals as $d) {

                $all_appeals['decision'][$d->year] = $d->count;

            }
        }

        if (isset($variation_appeals)) {
            foreach ($variation_appeals as $d) {

                $all_appeals['variation'][$d->year] = $d->count;
            }
        }


        return $all_appeals;
    }


    public function count_applications_by_year($data, $application_category)
    {


        $date = '';

        if ($application_category == 'applications_received') {
            $date = 'application_created_date';
        } elseif ($application_category == 'applications_processed') {
            $date = 'decision_date';
        }


        $count = [];

        // FIRST PASS
        //first initialize existing years count as 0
        foreach ($data as $report) {

            $year = Carbon::create($report[$date])->format('Y');

            if (@$count[$year] == null) {
                $count[$year] = 0;
            }
        }


        // SECOND PASS
        //now, use year as index and increment count by 1, whenever year of application is the same
        foreach ($data as $report) {
            $year_outer = Carbon::create($report[$date])->format('Y');

            foreach ($count as $year_inner => $total) {

                if ($year_outer == $year_inner) {

                    $count[$year_outer] = $count[$year_outer] + 1;
                }


            }
        }

        return $count;

    }


    public function get_regulatory_time_taken($start_date, $end_date, $time, $registration_route, $assessors)
    {


        $operator = null;

        if (in_array('in_time', $time) and in_array('post_time', $time)) {
            $operator = 'both';
        } elseif (in_array('in_time', $time)) {
            $operator = '<';
        } elseif (in_array('post_time', $time)) {
            $operator = '>';
        }


        $dossiers = '';
        if ($operator == '>' or $operator == '<') {

            $dossiers = DB::table('dossier_assignments')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('acknowledgement_letters', 'acknowledgement_letters.application_id', 'applications.application_id')
                ->join('users as lead_assessor', 'lead_assessor.id', 'dossier_assignments.assessor_id')
                ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                ->join('queries', 'queries.query_related_id', 'dossier_assignments.id')
                ->join('decisions', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->join('certifications', 'certifications.decision_id', 'decisions.id')
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->whereIn('main_tasks.task_status', ['completed', 'Completed', 'queued', 'Decision'])
                ->whereNotNull('queries.query_received_date')  //response replied insures status of dos. eval. has been changed from pause to inprogress
                ->whereBetween('main_tasks.start_time', [$start_date, $end_date])
                ->whereIn('lead_assessor.id', $assessors)
                ->whereIn('applications.application_type', $registration_route)
                ->whereColumn('main_tasks.task_duration_days_actual', $operator, 'main_tasks.end_time')
                ->select('applications.application_number', 'applications.application_type', 'applications.id as app_id',
                    'dossier_assignments.id as dossier_assignment_id',
                    'queries.query_sent_date as evaluation_paused_date', 'queries.query_received_date as evaluation_resumed_date',
                    'certifications.certified_date',
                    'acknowledgement_letters.application_id as application_id', 'acknowledgement_letters.date as applicant_acknowledged_date',
                    'main_tasks.task_duration_days_actual', 'main_tasks.end_time',
                    'lead_assessor.first_name', 'lead_assessor.middle_name')
                ->get();


        } else if ($operator == 'both') {

            $dossiers = DB::table('dossier_assignments')
                ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                ->join('acknowledgement_letters', 'acknowledgement_letters.application_id', 'applications.application_id')
                ->join('users as lead_assessor', 'lead_assessor.id', 'dossier_assignments.assessor_id')
                ->join('main_tasks', 'main_tasks.related_id', 'dossier_assignments.id')
                ->leftjoin('queries', 'queries.query_related_id', 'dossier_assignments.id') // leftjoin for tasks having no issued query
                ->join('decisions', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
                ->join('certifications', 'certifications.decision_id', 'decisions.id')
                ->where('main_tasks.related_task', 'Dossier Evaluation')
                ->whereIn('main_tasks.task_status', ['completed', 'Completed', 'queued', 'Decision'])
                ->whereNotNull('queries.query_received_date')  //response replied insures status of dos. eval. has been changed from pause to inprogress
                ->whereBetween('main_tasks.start_time', [$start_date, $end_date])
                ->whereIn('lead_assessor.id', $assessors)
                ->whereIn('applications.application_type', $registration_route)
                ->select('applications.application_number', 'applications.id as app_id', 'applications.application_type',
                    'dossier_assignments.id as dossier_assignment_id',
                    'certifications.certified_date',
                    'acknowledgement_letters.application_id as application_id', 'acknowledgement_letters.date as applicant_acknowledged_date',
                    'queries.query_sent_date as evaluation_paused_date', 'queries.query_received_date as evaluation_resumed_date',
                    'main_tasks.task_duration_days_actual', 'main_tasks.end_time',
                    'lead_assessor.first_name', 'lead_assessor.middle_name')
                ->get();

        }


        // --------------- GET Regulatory Time Taken --------------------

        // Dossier Evaluation may be paused for multiple times
        // compute total paused days ($non_regulatory_days_sum) for each dossier_assignment
        // output: [doss_assig_id] => [$regulatory_days_sum, $non_regulatory_days_sum]

        $report = array();

        foreach ($dossiers as $dossier_outer) {

            //determine whether task completion is in_time or post_time
            // planned_end_date - actual = if positive in_time else post_time

            $time_diff = Carbon::create($dossier_outer->task_duration_days_actual)->diffInDays(Carbon::create($dossier_outer->end_time), False);

            if ($time_diff > 0) {
                // actual end time is within scheduled end time
                $end_time_status = 'In time';
            } else {
                // actual end time is after scheduled end time
                $end_time_status = 'Post time';
            }


            $report[$dossier_outer->dossier_assignment_id]['application_number'] = $dossier_outer->application_number;
            $report[$dossier_outer->dossier_assignment_id]['assessor_full_name'] = $dossier_outer->first_name . ' ' . $dossier_outer->middle_name;

            $application_details = $this->get_application_info($dossier_outer->app_id);

            $report[$dossier_outer->dossier_assignment_id]['generic_name'] = $application_details->product_name;
            $report[$dossier_outer->dossier_assignment_id]['product_name'] = $application_details->product_trade_name;
            $report[$dossier_outer->dossier_assignment_id]['company_name'] = $application_details->company_name;
            $report[$dossier_outer->dossier_assignment_id]['application_type'] = $application_details->application_type;
            $total_time_taken = Carbon::create($dossier_outer->applicant_acknowledged_date)->diffInDays(Carbon::create($dossier_outer->certified_date));
            $report[$dossier_outer->dossier_assignment_id]['total_time_taken'] = $total_time_taken;
            $report[$dossier_outer->dossier_assignment_id]['end_time_status'] = $end_time_status;


            //compute for multiple pauses (if multiple)
            $non_regulatory_days_sum = 0;   // reset sum for each dossier
            foreach ($dossiers as $dossier_inner) {

                if ($dossier_outer->dossier_assignment_id == $dossier_inner->dossier_assignment_id) {

                    $paused_days = Carbon::create($dossier_inner->evaluation_paused_date)->diffInDays(Carbon::create($dossier_inner->evaluation_resumed_date));
                    $non_regulatory_days_sum = $non_regulatory_days_sum + $paused_days;
                }

                $report[$dossier_outer->dossier_assignment_id]['non_regulatory_days_sum'] = $non_regulatory_days_sum;
            }

            $report[$dossier_outer->dossier_assignment_id]['regulatory_time_taken'] = $total_time_taken - $non_regulatory_days_sum;


        }


        return $report;

    }


    public function get_variation_assessment_time_taken($start_date, $end_date)
    {


        // Task: for one authorized product(certificate_id) there might be many variations
        // fetch all and display time-taken (decided_time - assigned_time)

        $variations = DB::table('dossier_assignments')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
            ->join('company_suppliers', 'company_suppliers.application_id', 'applications.application_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->join('decisions', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('certifications', 'certifications.decision_id', 'decisions.id')
            ->join('variations', 'variations.certificate_id', 'certifications.id')
            ->join('main_tasks', 'main_tasks.related_id', 'variations.id')
            ->leftjoin('variation_decisions', 'variation_decisions.variation_id', 'variations.id')
            ->whereNotNull('variation_decisions.decision_date')
            ->where('main_tasks.related_task', 'Variation')
            ->whereIn('main_tasks.task_status', ['completed', 'Completed', 'Decision'])
            ->whereBetween('main_tasks.start_time', [$start_date, $end_date])
            ->select(
                'certifications.certificate_number', 'certifications.registration_number', 'variations.variation_reference_number',
                'applications.application_number', 'variations.assigned_datetime',
                'variation_decisions.decision_date', 'variation_decisions.decision_status',
                'medicinal_products.product_trade_name', 'medicines.product_name', 'company_suppliers.trade_name as company_name')
            ->get();

        return $variations;
    }
}