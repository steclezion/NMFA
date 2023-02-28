<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        DB::table('templates')->Delete();
        DB::table('templates')->insert( [ 'id'=>1, 'ref_num'=>null, 'name'=>'Letter to QC to Inspection Unit', 'description'=>'This the Letter send to Inspection Unit', 'path'=>'html_templates.letter_to_qc_analysis', 'template_type'=>4, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>2,  'ref_num'=>null, 'name'=>'Issue Query', 'description'=>'For Issuing Query', 'path'=>'html_templates.query_issue_cover_letter', 'template_type'=>5, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>3,  'ref_num'=>'Report form 1/ver 1.0', 'name'=>'Checklist for receiving registration applications', 'description'=>'Abridged WHO-CRP', 'path'=>'templates/Template 2.1. Assessment Report Form - Verification WHO-CRP.docx', 'template_type'=>8, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>4,  'ref_num'=>'Report form 2/ver 1.0', 'name'=>'Assessment report for WHO-CRP Verification', 'description'=>'Abridged WHO-CRP', 'path'=>'templates/Template 2.1. Assessment Report Form - Verification WHO-CRP.docx', 'template_type'=>2, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>5,  'ref_num'=>'Report form 3/ver 1.0', 'name'=>'Assessment report for WHO-CRP Abridged/abbreviated', 'description'=>'Abridged WHO-CRP', 'path'=>'templates/Template 2.2. Assessment Report Form - Abridged WHO-CRP.docx', 'template_type'=>2, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>6,  'ref_num'=>'Report form 4/ver 1.0', 'name'=>'Assessment report for CEP', 'description'=>'Assessment Report Form - CEP API', 'path'=>'templates/Template 2.3. Assessment Report Form - CEP API.docx', 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>7,  'ref_num'=>'Report form 5/ver 1.0', 'name'=>'Assessment report for APIMF Applicants part', 'description'=>"Assessment Report Form - APIMF Applicant's  Part", 'path'=>"templates/Template 2.4. Assessment Report Form - APIMF Applicant's  Part.docx", 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>8,  'ref_num'=>'Report form 6/ver 1.0', 'name'=>'Assessment report for APIMF Restricted part', 'description'=>'Assessment Report Form - APIMF Restricted  Part', 'path'=>'templates/Template 2.5. Assessment Report Form - APIMF Restricted  Part.docx', 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>9,  'ref_num'=>'Report form 7/ver 1.0', 'name'=>'Assessment report for Full API', 'description'=>'Assessment Report Form - Full API', 'path'=>'templates/Template 2.6. Assessment Report Form - Full API.docx', 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>10,  'ref_num'=>'Report form 8/ver 1.0', 'name'=>'Assessment report for SmPC', 'description'=>'Assesment Report Form - SmPC', 'path'=>'templates/Template 2.7. Assessment Report Form - SmPC.docx', 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>11,  'ref_num'=>'Report form 9/ver 1.0', 'name'=>'Assessment report for PIL', 'description'=>'Assessment Report Form - PILs', 'path'=>'templates/Template 2.8. Assessment Report Form - PILs.docx', 'template_type'=>1, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>12,  'ref_num'=>null, 'name'=>'Decision Committee Invitation', 'description'=>'Letter sent to PERC committee regarding decision meeting', 'path'=>'html_templates.product_evaluation_committee', 'template_type'=>21, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>13,  'ref_num'=>null, 'name'=>'Other Issues Committee Invitation', 'description'=>'Letter sent to PERC committee regarding other issues ', 'path'=>'html_templates.other_perc_meeting', 'template_type'=>25, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>14,  'ref_num'=>null, 'name'=>'Variation Query ', 'description'=>'For Issuing Query', 'path'=>'html_templates.variation_query_issue_cover_letter', 'template_type'=>35, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>15,  'ref_num'=>null, 'name'=>'Issue Query Details', 'description'=>'For Issuing Query', 'path'=>'html_templates.query_issue_details', 'template_type'=>15, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>16,  'ref_num'=>null, 'name'=>'Variation Query Details ', 'description'=>'For Editing Query Details', 'path'=>'html_templates.variation_query_details', 'template_type'=>16, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);

        DB::table('templates')->insert( [ 'id'=>18, 'ref_num'=>null, 'name'=>'Cease Letter', 'description'=>'Cease Letter sent to applicant', 'path'=>'html_templates.cease', 'template_type'=>40, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('templates')->insert( [ 'id'=>19, 'ref_num'=>null, 'name'=>'Suspend Letter', 'description'=>'Suspend Letter sent to applicant', 'path'=>'html_templates.suspend', 'template_type'=>41, 'is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);

    }
}
