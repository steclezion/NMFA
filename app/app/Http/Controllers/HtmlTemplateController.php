<?php

namespace App\Http\Controllers;

use App\Models\template;
use Illuminate\Support\Facades\DB;
use App\Models\dossier_assignment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\query_drafts;


class HtmlTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $templates = DB::table('templates')->where('is_active', 1)->get();
        $breadcrumb_title = 'Templates';
        return view('html_templates.index', ['breadcrumb_title' => $breadcrumb_title, 'templates' => $templates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $breadcrumb_title = 'View Templates';
        return view('html_templates.create', ['breadcrumb_title' => $breadcrumb_title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $template = DB::table('templates')->where('id', $id)->first();
        $ref = 'this is the reference number';
        $breadcrumb_title = 'Templates';
        return view($template->path, ['ref' => $ref, 'breadcrumb_title' => $breadcrumb_title, 'template' => $template]);
    }
    public function view_html_template($id, $dossier_asg_id)
    {
        // dd($dossier_asg_id);
        if($dossier_asg_id=='Edit'){
            $template = DB::table('templates')->where('id', $id)->first();
            $breadcrumb_title = 'View '.$template->name;
            return view($template->path, ['breadcrumb_title' => $breadcrumb_title, 'template'=>$template]);


        }
        else
        {

                $template = DB::table('templates')->where('id', $id)->first();
                $date = 'this todays day';
                $breadcrumb_title = $template->name;


                $dossier_evaluation_details = dossier_assignment::where('dossier_assignments.id', $dossier_asg_id)
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->join('users as assessors', 'assessors.id', 'dossier_assignments.assessor_id')
            ->join('users as supervisors', 'supervisors.id', 'dossier_assignments.supervisor_id')
            ->join('applications', 'applications.id', 'dossier_assignments.application_id')
                    ->leftjoin('checklists', 'checklists.application_id', 'applications.application_id')
                    ->join('users as applicant', 'applicant.id', 'applications.user_id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('dosage_forms', 'dosage_forms.id', 'medicinal_products.dosage_form_id')
                    ->join('route_administrations', 'route_administrations.id', 'medicinal_products.route_administration_id')
            ->join('company_suppliers', 'company_suppliers.id', 'applications.company_supplier_id')
                    ->join('countries', 'countries.id', 'company_suppliers.country_id')
            ->select(
                'dossier_assignments.id as dossier_ass_id',
                'dossier_assignments.assigned_datetime',
                'dossiers.dossier_ref_num',
                'dossier_assignments.dossier_id',
                'dossiers.assignment_status',
                'assessors.first_name',
                'assessors.middle_name',
                'supervisors.first_name as name',
                'supervisors.middle_name as m_name',
                'medicinal_products.*',
                'dosage_forms.name as dosage_name',
                'applications.progress_percentage',
                'applications.application_type',
                'applications.application_number',
                'company_suppliers.trade_name as company_name',
                'company_suppliers.city',
                'company_suppliers.state',
                'company_suppliers.address_line_one',
                'countries.country_name',
                'applicant.first_name as applicant_first_name',
                'applicant.middle_name  as applicant_middle_name',
                'route_administrations.name as route_administration_name',
                'dosage_forms.name as dosage_form_name',
                'checklists.sample_received_date'
            )
            ->first();
                //this is for QC
            $users=DB::table('roles')
                ->join('model_has_roles','roles.id','model_has_roles.role_id')
                ->join('users','users.id','model_has_roles.model_id')
                ->where('roles.name','Inspection')
                ->get();
            if($template->template_type==5){
                $saved_draft=query_drafts::where('dossier_assignment_id',$dossier_asg_id)->first();
//
                if($saved_draft!=null) {

                    return view('html_templates.saved_query', ['breadcrumb_title' => $breadcrumb_title, 'dossier_evaluation_details' => $dossier_evaluation_details, 'data' => $saved_draft->html_draft,'template'=>$template]);
                }

            }

                    $date = date('d-M-Y');
                    return view($template->path, ['breadcrumb_title' => $breadcrumb_title, 'users'=>$users,'dossier_evaluation_details' => $dossier_evaluation_details, 'date' => $date,'template'=>$template]);

        }
           }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function latter_to_qc()
    // {
    //     $template = DB::table('templates')->where('id', 3)->first();
    //     $breadcrumb_title = $template->html_name;
    //     $date = 'this is the date';
    //     return view('html_templates.letter_to_qc_analysis', ['breadcrumb_title' => $breadcrumb_title, 'template' => $template]);
    // }
    // public function edit($id)
    // {
    //     //
    //     $breadcrumb_title = 'Edit';
    //     $template = DB::table('templates')->where('id', $id)->first();
    //     // dd($template);
    //     return view('html_templates.edit', ['breadcrumb_title' => $breadcrumb_title, 'template' => $template]);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
