<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;


use App\Repositories\AssessorReportsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf as PDFF;
use Mpdf\MpdfException;
use function Sodium\add;


class AssessorReportsController extends Controller
{
    // inject repository
    private $assessorReportsRepository;

    /**
     * AssessorReportsController constructor.
     * @param $assessorReportsRepository
     */
    public function __construct(AssessorReportsRepository $assessorReportsRepository)
    {
        $this->assessorReportsRepository = $assessorReportsRepository;
    }

    public function assessor_tasks_report()
    {

        $all_users = $this->assessorReportsRepository->all();


        $assessors = $this->assessorReportsRepository->listAssessors();
        $perc_members = $this->assessorReportsRepository->listPercMembers();


        return view('reports.assessor_tasks_report', ['assessors' => $assessors, 'perc_members' => $perc_members]);
    }


    public function get_assessor_tasks(Request $request)
    {

        $assessors = $this->assessorReportsRepository->listAssessors();
        $perc_members = $this->assessorReportsRepository->listPercMembers();

        list($search_history, $assessor_tasks, $counter) = $this->retrieve_assessor_tasks($request);

        $submit_btn = $request->input('submit_btn');
        if ($submit_btn == 'search') {
            return view('reports.assessor_tasks_report', ['assessors' => $assessors, 'counter' => $counter,
                'perc_members' => $perc_members, 'data' => $assessor_tasks, 'search_history' => $search_history]);
        } else {
            $assessor_task = 'AssessorTask' . '_' . now();
            try {
                $document = new PDFF([
                        'mode' => "utf-8",
                        'format' => "A4",
                        'margin_header' => "1",
                        'margin_top' => "30",
                        'margin_bottom' => "15",
                        'margin_footer' => "2",
                    ]
                );
            } catch (MpdfException $e) {
                dd('Unable to Create MPDF Object');
            }

            $header = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline: filename="' . $assessor_task . '"'];

            $document->SetHTMLHeader('<img src="images/nmfa_header.png" width="100%" height="100px"/>');
            $document->SetHTMLFooter('<img src="images/nmfa_footer.png" width="100%"/>');


            $document->WriteHTML('reports.assessor_tasks_report', HTMLParserMode::HTML_BODY);


            Storage::disk('documents')->put($assessor_task, $document->Output($assessor_task, "S"));
            return Storage::disk('documents')->download($assessor_task, 'Request', $header);

        }

    }

    //call get_assessor_tasks to get tasks and add print functionality
    // and return data to view

    public function assessor_tasks_list()
    {

        $assessor_tasks = $this->get_assessor_tasks();


    }


    public function evaluation_status_report_index()
    {


        return view('reports.evaluation_status');

    }

    public function get_evaluation_status(Request $request)
    {

        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $registration_type = $request->input('application_type');
        $task_status = $request->input('task_status');
        $registration_route = $request->input('registration_route');


        //dd($start_date, $end_date, $registration_type,$task_status,$registration_route);


        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');

        if ($registration_route == null or in_array("all", $registration_route)) {
            $registration_route = ["1", "2"];
        }


        if ($task_status == null or in_array("All", $task_status)) {
            //$task_status = ['Unassigned', 'Assigned', 'Inprogress', 'Completed', 'Paused'];
            $task_status = ['Unassigned', 'Inprogress', 'Completed', 'Paused'];
        }


        $users_temp = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', 'Assessor')
            ->select('users.id')
            ->distinct('users.id')
            ->get();

        $user = [];
        foreach ($users_temp as $user_temp) {
            $user[] = $user_temp->id;

        }


        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['task_status'] = $task_status;
        $search_history['registration_route'] = $registration_route;

        if ($registration_type == null or in_array("all", $registration_type)) {
            $all_reports = True;
            $registration_type = ['New', 'Re-new', 'Variation', 'PSUR'];
            $search_history['application_type'] = ['New', 'Re-new', 'Variation', 'PSUR'];
        } else {
            $all_reports = False;
            $search_history['application_type'] = $registration_type;
        }

        if ($all_reports) {

            if (in_array('Unassigned', $task_status)) {
                $returned_data[] = $this->assessorReportsRepository->unassigned_dossiers($user, $carbon_start_time, $carbon_end_time, ['Unassigned'], $registration_route, $registration_type);

            }
            $returned_data[] = $this->assessorReportsRepository->evaluation_tasks($user, $carbon_start_time, $carbon_end_time,
                $task_status, "EvaluationStatus", $registration_route, $registration_type);


            $returned_data[] = $this->assessorReportsRepository->variation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);


        } else {

            if (in_array("New", $registration_type) or in_array("Re-new", $registration_type)) {


                if (in_array('Unassigned', $task_status)) {
                    $returned_data[] = $this->assessorReportsRepository->unassigned_dossiers($user, $carbon_start_time, $carbon_end_time, ['Unassigned'], $registration_route, $registration_type);

                }
                $returned_data[] = $this->assessorReportsRepository->evaluation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status, "EvaluationStatus", $registration_route, $registration_type);

            }

            if
            (in_array("Variation", $registration_type)) {


                $returned_data[] = $this->assessorReportsRepository->variation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);


            }
            if
            (in_array("psur", $registration_type)) {  //TODO check psur ? or PSUR in view

               // $returned_data[] = $this->assessorReportsRepository->psur_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);

            }


        }


        /* $i=0;
         $data=[];
         foreach ($returned_data as $data_array)

         {
             foreach ($data_array as $d)
             {
                 $data[$i]=$d;
                 $i++;
             }
         }*/


        $i = 0;
        $counter = [];
        $data = [];
        foreach ($returned_data as $data_array) {

            foreach ($data_array[0] as $d) {

                $data[$i] = $d;
                $i++;

            }
            foreach ($data_array[1] as $k => $d) {
                $counter[$k] = $d;

            }
        }


        $submit_btn = $request->input('submit_btn');
        if ($submit_btn == 'search') {
            return view('reports.evaluation_status', ['data' => $data, 'counter' => $counter, 'search_history' => $search_history]);
        }


    }


    public function meeting_reports()
    {
        return view('reports.meeting_report');
    }

    public function get_meetings(Request $request)
    {

        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $meeting_type = $request->input('meeting_type');
        $decision_type = $request->input('decision_type');
        if ($meeting_type == null or in_array("All", $meeting_type)) {
            $meeting_type = ['Decision_Meeting', 'Other_Meeting'];
        }


        if ($decision_type == null or in_array("All", $decision_type)) {
            $decision_type = ['Accepted', 'Rejected', 'Deferred', 'Reassign', 'Reassigned'];
        } else if ($decision_type != null and in_array("Rejected", $decision_type)) {

            $decision_type[] = 'Reassign';
            $decision_type[] = 'Reassigned';
        }

        $search_history['decision_type'] = $decision_type;
        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['meeting_type'] = $meeting_type;


        $data = $this->assessorReportsRepository->get_meeting_info($start_date, $end_date, $meeting_type, $decision_type);

        $number_of_perc_meetings = $this->assessorReportsRepository->count_perc_meetings_by_year($start_date, $end_date);


        return view('reports.meeting_report', ['data' => $data, 'number_of_perc_meetings' => $number_of_perc_meetings,
            'search_history' => $search_history]);

    }

    public function appeal_reports()
    {
        return view('reports.appeals_report');
    }


    public function applications_processed()
    {

        $applicants = $this->assessorReportsRepository->listApplicants();
        $countries = $this->assessorReportsRepository->companyCountries();


        return view('reports.applications_processed', ['applicants' => $applicants, 'countries' => $countries]);
    }


    public function applications_received()
    {

        $applicants = $this->assessorReportsRepository->listApplicants();
        $countries = $this->assessorReportsRepository->companyCountries();

        return view('reports.applications_received', ['applicants' => $applicants, 'countries' => $countries]);

    }


    public function get_appeal(Request $request)
    {
        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $decision_type = $request->input('decision_type');

        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');

        if ($decision_type == null or in_array("All", $decision_type)) {
            $decision_type = ['Accepted', 'Rejected'];
        }


        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['decision_type'] = $decision_type;

        $data = $this->assessorReportsRepository->get_appeal_details($carbon_start_time, $carbon_end_time, $decision_type);

        $all_appeals = $this->assessorReportsRepository->count_appeals_by_year($carbon_start_time, $carbon_end_time);

        return view('reports.appeals_report', ['data' => $data, 'all_appeals' => $all_appeals, 'search_history' => $search_history]);

    }

    public function get_application_received(Request $request)
    {
        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $registration_type = $request->input('application_type');
        // $task_status=$request->input('task_status');
        $registration_route = $request->input('registration_route');
        $status_type = $request->input('status_type');
        $applicant_input = $request->input('applicant');
        $country_input = $request->input('country');
        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');

        $search_history['Applicant_id'] = $applicant_input;


        if ($applicant_input == 'All' or $applicant_input == '') {
            $applicants = DB::table('company_supplier_template')->select('company_supplier_template.trade_name')->get();
            foreach ($applicants as $c) {
                $applicant[] = $c->trade_name;
            }
            $search_history['Applicant_id'] = 'All';
            $search_history['Applicant_name'] = 'All';

        } else {

            $applicants = DB::table('company_supplier_template')->where('trade_name', $applicant_input)->first();
            $applicant[] = $applicants->trade_name;
            $search_history['Applicant_name'] = $applicants->trade_name;
        }

        $search_history['country_id'] = $country_input;

        if ($country_input == 'All' or $country_input == '') {
            $counties = DB::table('countries')->get();
            foreach ($counties as $c) {
                $country[] = $c->id;
            }
            $search_history['country_name'] = 'All';
            $search_history['country_id'] = 'All';
        } else {

            $countries = DB::table('countries')->where('countries.id', $country_input)
                ->select('countries.id', 'countries.country_name')
                ->first();

            $country[] = $countries->id;
            $search_history['country_name'] = $countries->country_name;


        }

        if ($registration_route == null or in_array("all", $registration_route)) {
            $registration_route = ["1", "2"];
        }

        if ($status_type == null or in_array("All", $status_type)) {
            $status_type = ['Preliminary screening completed', 'Preliminary screening rejected', 'processing'];
        }

        if ($registration_type == null or in_array("all", $registration_type)) {
            $all_reports = True;
            $registration_type = ['New', 'Re-new', 'Variation', 'PSUR'];
            $search_history['application_type'] = ['New', 'Re-new', 'Variation', 'PSUR'];


        } else {
            $all_reports = False;
            $search_history['application_type'] = $registration_type;
        }


        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['status_type'] = $status_type;
        $search_history['registration_route'] = $registration_route;


        $returned_data = [];
        if ($all_reports) {

            $returned_data[] = $this->assessorReportsRepository->get_received_apps($carbon_start_time, $carbon_end_time, $status_type, $registration_route, $registration_type, $applicant, $country);

            $returned_data[] = $this->assessorReportsRepository->get_received_variations($carbon_start_time,$carbon_end_time,$applicant,$country);

            //psur


        } else {

            if (in_array("New", $registration_type) or in_array("Re-new", $registration_type)) {

                $returned_data[] = $this->assessorReportsRepository->get_received_apps($carbon_start_time, $carbon_end_time, $status_type, $registration_route, $registration_type, $applicant, $country);

            }

            if (in_array("Variation", $registration_type)) {

                $returned_data[] = $this->assessorReportsRepository->get_received_variations($carbon_start_time, $carbon_end_time, $applicant, $country);


            }
            if (in_array("psur", $registration_type)) {

            }

        }


        $applicants = $this->assessorReportsRepository->listApplicants();
        $countries = $this->assessorReportsRepository->companyCountries();

        $i = 0;
        $data = [];
        foreach ($returned_data as $data_array) {
            foreach ($data_array as $d) {
                $data[$i] = $d;
                $i++;
            }
        }


        $application_count_by_year = $this->assessorReportsRepository->count_applications_by_year($data, 'applications_received');


        $submit_btn = $request->input('submit_btn');
        if ($submit_btn == 'search') {
            return view('reports.applications_received', ['data' => $data, 'application_count_by_year' => $application_count_by_year, 'search_history' => $search_history, 'applicants' => $applicants, 'countries' => $countries]);
        }


    }


    public function get_application_processed(Request $request)
    {

        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $registration_type = $request->input('application_type');
//        $task_status=$request->input('task_status');
        $registration_route = $request->input('registration_route');
        $decision_type = $request->input('decision_type');
        $applicant_input = $request->input('applicant');
        $country_input = $request->input('country');

        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');


        $search_history['Applicant_id'] = $applicant_input;
        if ($applicant_input == 'All' or $applicant_input == '') {
            $applicants = DB::table('company_supplier_template')->select('company_supplier_template.trade_name')->get();
            foreach ($applicants as $c) {
                $applicant[] = $c->trade_name;
            }
            $search_history['Applicant_id'] = 'All';
            $search_history['Applicant_name'] = 'All';

        } else {

            $applicants = DB::table('company_supplier_template')->where('trade_name', $applicant_input)->first();

            $applicant[] = $applicants->trade_name;
            $search_history['Applicant_name'] = $applicants->trade_name;

        }
        $search_history['country_id'] = $country_input;
        if ($country_input == 'All' or $country_input == '') {
            $counties = DB::table('countries')->get();
            foreach ($counties as $c) {
                $country[] = $c->id;
            }
            $search_history['country_name'] = 'All';
            $search_history['country_id'] = 'All';
        } else {

            $countries = DB::table('countries')->where('countries.id', $country_input)
                ->select('countries.id', 'countries.country_name')
                ->first();

            $country[] = $countries->id;
            $search_history['country_name'] = $countries->country_name;


        }

        if ($registration_route == null or in_array("all", $registration_route)) {
            $registration_route = ["1", "2"];
        }

        if ($decision_type == null or in_array("All", $decision_type)) {
            $decision_type = ['Accepted', 'Rejected', 'Deferred'];
        }
        if ($registration_type == null or in_array("all", $registration_type)) {
            $all_reports = True;
            $registration_type = ['New', 'Re-new', 'Variation', 'PSUR'];
            $search_history['application_type'] = ['New', 'Re-new', 'Variation', 'PSUR'];
        } else {
            $all_reports = False;
            $search_history['application_type'] = $registration_type;
        }


        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['decision_type'] = $decision_type;
        $search_history['registration_route'] = $registration_route;


        $returned_data = [];
        if ($all_reports) {

            $returned_data[] = $this->assessorReportsRepository->get_processed_apps($carbon_start_time, $carbon_end_time, $decision_type, $registration_route, $registration_type, $applicant, $country);

            $returned_data[] = $this->assessorReportsRepository->get_processed_variations($carbon_start_time, $carbon_end_time, $decision_type, $registration_route, $registration_type, $applicant, $country);

            //psur


        } else {

            if (in_array("New", $registration_type) or in_array("Re-new", $registration_type)) {

                $returned_data[] = $this->assessorReportsRepository->get_processed_apps($carbon_start_time, $carbon_end_time, $decision_type, $registration_route, $registration_type, $applicant, $country);

            }

            if
            (in_array("Variation", $registration_type)) {

                $returned_data[] = $this->assessorReportsRepository->get_processed_variations($carbon_start_time, $carbon_end_time, $decision_type, $registration_route, $registration_type, $applicant, $country);

            }
            if
            (in_array("psur", $registration_type)) {

            }

        }


        $applicants = $this->assessorReportsRepository->listApplicants();
        $countries = $this->assessorReportsRepository->companyCountries();


        $i = 0;
        $data = [];
        foreach ($returned_data as $data_array) {
            foreach ($data_array as $d) {
                $data[$i] = $d;
                $i++;
            }
        }


        $application_count_by_year = $this->assessorReportsRepository->count_applications_by_year($data, 'applications_processed');


        $submit_btn = $request->input('submit_btn');
        if ($submit_btn == 'search') {
            return view('reports.applications_processed', ['data' => $data, 'application_count_by_year' => $application_count_by_year, 'search_history' => $search_history, 'applicants' => $applicants, 'countries' => $countries]);
        }


    }


    public function assessor_tasks_timelapse_index()
    {

        $assessors = $this->assessorReportsRepository->listAssessors();
        $perc_members = $this->assessorReportsRepository->listPercMembers();

        return view('reports.assessor_tasks_timelapse', ['assessors' => $assessors, 'perc_members' => $perc_members]);

    }

    public function get_assessor_tasks_timelapse(Request $request)
    {

        $assessors = $this->assessorReportsRepository->listAssessors();
        $perc_members = $this->assessorReportsRepository->listPercMembers();

        list($search_history, $assessor_tasks, $counter) = $this->retrieve_assessor_tasks($request);


        $submit_btn = $request->input('submit_btn');
        if ($submit_btn == 'search') {
            return view('reports.assessor_tasks_timelapse',
                ['assessors' => $assessors,
                    'perc_members' => $perc_members, 'data' => $assessor_tasks, 'search_history' => $search_history, 'counter' => $counter]);
        }
        //TODO: print button

    }

    /**
     * @param Request $request
     * @param $search_history
     * @return array
     */
    public function retrieve_assessor_tasks(Request $request)
    {
        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $task_type = $request->input('task_type');
        $task_status = $request->input('task_status');

        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');


        $user_from_input = $request->input('user');
        $user = [];

        if ($task_status == null or in_array('All', $task_status)) {
            $task_status = ['pause', 'Inprogress', 'Completed', 'Decision'];
        }


        if ($user_from_input == 'All' or $user_from_input == '') {
            $users_temp = DB::table('roles')
                ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                ->join('users', 'users.id', 'model_has_roles.model_id')
                ->where('roles.name', 'Assessor')
                ->orWhere('roles.name', 'PERC')
                ->select('users.id')
                ->distinct('users.id')
                ->get();

            foreach ($users_temp as $user_temp) {
                $user[] = $user_temp->id;

            }
            $search_history['assessor_name'] = 'All';

            $search_history['user_id'] = 'All';


        } else {
            $users_temp = User::where('id', $user_from_input)->get();
            foreach ($users_temp as $user_temp) {
                $user[] = $user_temp->id;

            }
            $assessor = User::find($user_from_input);

            $search_history['assessor_name'] = $assessor->first_name . ' ' . $assessor->middle_name;

            $search_history['user_id'] = $user_from_input;


        }

        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;
        $search_history['task_status'] = $task_status;

        if ($task_type == null or in_array("all", $task_type)) {
            $task_type = ['all'];
            $search_history['task_type'] = ['prelimunary', 'dossier', 'dossier_section', 'variation', 'psur'];
        } else {
            $search_history['task_type'] = $task_type;
        }
        $data = [];
        $returned_data = [];
        if (in_array("all", $task_type)) {
            $returned_data[] = $this->assessorReportsRepository->screening_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);
            $returned_data[] = $this->assessorReportsRepository->evaluation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status, "AssessorTask");
            $returned_data[] = $this->assessorReportsRepository->dossier_section_assignment_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);
            $returned_data[] = $this->assessorReportsRepository->variation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);

            //TODO consult sase
            // PSUR TASKS

        } else {

            if (in_array("prelimunary", $task_type)) {

                $returned_data[] = $this->assessorReportsRepository->screening_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);


            }
            if (in_array("dossier", $task_type)) {

                $returned_data[] = $this->assessorReportsRepository->evaluation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status, "AssessorTask");
            }
            if (in_array("dossier_section", $task_type)) {


                $returned_data[] = $this->assessorReportsRepository->dossier_section_assignment_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);

            }
            if
            (in_array("variation", $task_type)) {


                $returned_data[] = $this->assessorReportsRepository->variation_tasks($user, $carbon_start_time, $carbon_end_time, $task_status);

            }
            if
            (in_array("psur", $task_type)) {
                //TODO consult sase
                // PSUR TASKS
            }

        }


        $i = 0;
        $counter = [];


        foreach ($returned_data as $data_array) {
            foreach ($data_array[0] as $d) {
                $data[$i] = $d;
                $i++;
            }
            foreach ($data_array[1] as $k => $d) {
                $counter[$k] = $d;

            }
        }


        $assessor_tasks = $this->assessorReportsRepository->list_tasks_by_assesor($returned_data, $user);

        return array($search_history, $assessor_tasks, $counter);
    }


    public function sample_test_report_index()
    {

        return view('reports.sample_test_report');
    }

    public function get_sample_test_report(Request $request)
    {

        $start_time = $request->input('task_start_date');
        $end_time = $request->input('task_end_date');

        $search_history['start_date'] = $start_time;
        $search_history['end_date'] = $end_time;

        $data = $this->assessorReportsRepository->list_sample_tests($start_time, $end_time);

        $sample_tests_count_by_year = $this->assessorReportsRepository->count_sample_test_requests_by_year2($start_time, $end_time);


        return view('reports.sample_test_report', ['search_history' => $search_history, 'data' => $data, 'sample_tests_count_by_year' => $sample_tests_count_by_year]);

    }

    public function regulatory_time_taken_index()
    {

        $assessors = $this->assessorReportsRepository->listAssessors();

        return view('reports.regulatory_time_taken_report', ['assessors' => $assessors]);
    }


    public function get_regulatory_time_taken(Request $request)
    {


        # ---------- Values Passed to Controller to filter report ------------#
        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');
        $time = $request->input('time');
        $registration_route = $request->input('registration_route');
        $selected_assessors = $request->input('user');
        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');

        # ---------- END // Values Passed to Controller to filter report ------------#


        # ---------- The Selected Values in View, that are going to be returned back to View as Search history  ------------#
        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;

        if ($time == null or in_array("all", $time)) {
            $time = ['in_time', 'post_time'];
            $search_history['time'] = ['in_time', 'post_time'];
        } else {
            $search_history['time'] = $time;
        }

        if ($registration_route == null or in_array("all", $registration_route)) {
            $registration_route = [1, 2];
            $search_history['registration_route'] = [1, 2];

        } else {

            $search_history['registration_route'] = $registration_route;

        }

        $user_from_input = $request->input('user');
        $user = [];

        if ($user_from_input == null or in_array("All", $user_from_input)) {

            $users_temp = DB::table('roles')
                ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                ->join('users', 'users.id', 'model_has_roles.model_id')
                ->where('roles.name', '=', 'Assessor')
                ->select('users.id')
                ->distinct('users.id')
                ->get();


            //collect only user_ids
            foreach ($users_temp as $user_temp) {
                $user[] = $user_temp->id;
            }


            $search_history['assessor_ids'] = $user;

            $selected_assessors = $user;

        } else {

            $assessors = User::whereIn('id', $user_from_input)
                ->select('users.id')
                ->get();


            foreach ($assessors as $user_temp) {

                $user_ids[] = $user_temp->id;
            }

            $search_history['assessor_ids'] = $user_ids;


        }

        $assessors = DB::table('roles')
            ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->where('roles.name', '=', 'Assessor')
            ->select('users.*')
            ->distinct('users.id')
            ->get();

        # ----------END // The Selected Values in View, that are going to be returned back to View as Search history  ------------#


        $data = $this->assessorReportsRepository->get_regulatory_time_taken($carbon_start_time, $carbon_end_time,
            $time, $registration_route, $selected_assessors);


        return view('reports.regulatory_time_taken_report', ['search_history' => $search_history, 'assessors' => $assessors, 'data' => $data]);

    }


    public function get_variation_assessment_time_taken_index()
    {

        return view('reports.variations_assessment_time_taken_report');
    }

    public function get_variation_assessment_time_taken_report(Request $request)
    {

        # ---------- Values Passed to Controller to filter report ------------#
        $start_date = $request->input('task_start_date');
        $end_date = $request->input('task_end_date');

        $carbon_start_time = Carbon::create($start_date)->format('Y-m-d');
        $carbon_end_time = Carbon::create($end_date)->format('Y-m-d');

        # ---------- END // Values Passed to Controller to filter report ------------#

        $search_history['start_date'] = $start_date;
        $search_history['end_date'] = $end_date;


        $data = $this->assessorReportsRepository->get_variation_assessment_time_taken($carbon_start_time, $carbon_end_time);

        return view('reports.variations_assessment_time_taken_report', ['search_history' => $search_history, 'data' => $data]);
    }


}
