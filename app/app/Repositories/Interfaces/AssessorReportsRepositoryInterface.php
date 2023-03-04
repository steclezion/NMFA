<?php


namespace App\Repositories\Interfaces;

use App\Models\User;

interface AssessorReportsRepositoryInterface
{
    public function all();

    public function getByUser(User $user);

    public function listAssessors();

    public function listApplicants();
    public function companyCountries();

    public function listPercMembers();

    public function evaluation_tasks( $user, $start_time, $end_time, $task_status,$report_type,$registration_route,$application_type);
    public function variation_tasks($user, $start_time, $end_time, $task_status);
    public function screening_tasks($user, $start_time, $end_time, $task_status);
    public function psur_tasks($user, $start_time, $end_time, $task_status);
    public function dossier_section_assignment_tasks($user, $start_time, $end_time, $task_status);
    public function get_application_info($application_id);

    public function merge_reports($details, $task_type);
    public function list_tasks_by_assesor($data,$assessors_percs);
    public function unassigned_dossiers($user, $carbon_start_time, $carbon_end_time, $task_status,$registration_route,$registration_type);
    public function list_sample_tests($task_start_date, $task_end_date);
    public function count_sample_test_requests_by_year();
    public function count_sample_test_requests_by_year2($start_date, $end_date);
    public function get_meeting_info($carbon_start_time,$carbon_end_time,$meeting_type,$decision_type);
    public function get_decision_details($regisration_meeting_details,$other_meeting_details,$decision_type);
    public function get_appeal_details($start_date,$end_date,$appeal_decision_type);
    public function get_processed_apps($start_date,$end_date,$decision_type,$registration_route,$application_type,$applicant,$country);
    public function get_processed_variations($start_date,$end_date,$decision_type,$registration_route,$application_type,$applicant,$country);
    public function get_processed_psurs($start_date,$end_date,$decision_type,$registration_route,$application_type,$applicant,$country);
    public function merge_processed_product($data,$process_type);

    public function get_received_apps($start_date, $end_date, $status_type, $registration_route, $application_type, $applicant, $country);
    public function merge_received_product($data, $process_type);

    public function count_perc_meetings_by_year($start_date, $end_date);
    public function count_appeals_by_year($start_date, $end_date);
    public function count_applications_by_year($data, $application_category);

    public function get_regulatory_time_taken($start_date, $end_date, $isInTime, $registration_route,$assessor);
    public function get_variation_assessment_time_taken($start_date, $end_date);
}
