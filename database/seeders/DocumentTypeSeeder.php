<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('document_types')->insert( ['id'=>1,'document_type'=>'Standard Dossier Evaluation','description'=>'Documents needed for dossier evaluation in Standard Evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>2,'document_type'=>'Fast Track','description'=> 'Fast Track','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>3,'document_type'=>'Declaration','description'=>'Declaration','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>4,'document_type'=>'QC Related Documents','description'=>'QC Related Document','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>5,'document_type'=>'Query Issue Related Documents','description'=>'Query Issue Related Documents','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>6,'document_type'=>'Dossier','description'=>'Dossier','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>7,'document_type'=>'Assessment report','description'=>'Assessment report for Regular (Initial) Evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>8,'document_type'=>'Invoice','description'=>'Invoice Report','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>9,'document_type'=>'Receipts','description'=>'Receipts Report','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>10,'document_type'=>'Acknowledgement Letter For Preliminary Screening','description'=>'Acknowledgement Letter Report','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>11,'document_type'=>'Upload  Sealed Acknowledgement Letter For Preliminary Screening To Applicant','description'=>'Acknowledgement Letter Report uploaded to Applicant','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>12,'document_type'=>'Query For Preliminary Screening','description'=>'Query For Preliminary Screening','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>13,'document_type'=>'Upload Sealed  Preliminary Screening To Applicant','description'=>'Upload Sealed  Preliminary Screening To Applicant','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>14,'document_type'=>'Upload Answer Query of Preliminary Screening To Assesor','description'=>'Upload Answer Query of Preliminary Screening To Assesor','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>15,'document_type'=>'Upload Curriculum Vitae','description'=>'Upload CV','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>16,'document_type'=>'Commented Assessment report for Regular (Initial) Evaluation','description'=>'Supervisors Comment','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>17,'document_type'=>'Application receipt of registrations','description'=>'Assessor Produces Application Receipt Registration','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>18,'document_type'=>'Upload Application receipt of registrations','description'=>'Upload Application receipt of registrations','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>19,'document_type'=>'Financial Notification','description'=>'Financial Notification','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>20,'document_type'=>'Upload Financial Notification','description'=>'Upload Financial Notification','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>21,'document_type'=>'Invoice uploaded','description'=>'Invoice Sealed Report upload','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>22,'document_type'=>'Upload PERC Decision Invitation ','description'=>'Letter to PERC committee for decision','created_at'=>now(),'updated_at'=>now()]);

        //todo 23 - 26 used by merhawi update them here
//        DB::table('document_types')->insert( ['id'=>23,'document_type'=>'Assessment Report for Deferment Evaluation','description'=>'Assessment Report for Deferment Evaluation','created_at'=>now(),'updated_at'=>now()]);
//        DB::table('document_types')->insert( ['id'=>24,'document_type'=>'Commented Assessment Report for Deferment Evaluation','description'=>'Commented Assessment Report for Deferment Evaluation','created_at'=>now(),'updated_at'=>now()]);
//        DB::table('document_types')->insert( ['id'=>25,'document_type'=>'Assessment Report for Variation Evaluation','description'=>'Assessment Report for Variation Evaluation','created_at'=>now(),'updated_at'=>now()]);
//        DB::table('document_types')->insert( ['id'=>26,'document_type'=>'Commented Assessment Report for Variation Evaluation','description'=>'Commented Assessment Report for Variation Evaluation','created_at'=>now(),'updated_at'=>now()]);

        DB::table('document_types')->insert( ['id'=>27,'document_type'=>'Assessment Report for Deferment Evaluation','description'=>'Assessment Report for Deferment Evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>28,'document_type'=>'Commented Assessment Report for Deferment Evaluation','description'=>'Commented Assessment Report for Deferment Evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>29,'document_type'=>'Assessment Report for Variation Evaluation','description'=>'Assessment Report for Variation Evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>30,'document_type'=>'Commented Assessment Report for Variation Evaluation','description'=>'Commented Assessment Report for Variation Evaluation','created_at'=>now(),'updated_at'=>now()]);

        DB::table('document_types')->insert( ['id'=>31,'document_type'=>'Upload Application PSUR file','description'=>'Applicant will upload PSUR file to the NMFA director','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>32,'document_type'=>'Upload alert notification','description'=>'NMFA director will upload alert notfication','created_at'=>now(),'updated_at'=>now()]);


        DB::table('document_types')->insert( ['id'=>33,'document_type'=>'Acknowledgement Letter for the receipt of a Periodic Safety Update Report (PSUR)','description'=>'Acknowledgement Letter Report for PSUR','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>34,'document_type'=>'Upload  Sealed Acknowledgement Letter for the receipt of a Periodic Safety Update Report (PSUR)  To Applicant','description'=>'Acknowledgement Letter Report uploaded to Applicant for PSUR','created_at'=>now(),'updated_at'=>now()]);

        DB::table('document_types')->insert( ['id'=>35,'document_type'=>'Upload review report of PSUR ','description'=>'Upload review report of PSUR ','created_at'=>now(),'updated_at'=>now()]);
        DB::table('document_types')->insert( ['id'=>36,'document_type'=>'Upload Swift Payment','description'=>'Upload swift payment','created_at'=>now(),'updated_at'=>now()]);


    }
}
