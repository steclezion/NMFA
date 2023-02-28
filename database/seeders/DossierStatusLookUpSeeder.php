<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DossierStatusLookUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dossier_status_lookups')->insert( ['status'=>'Unassigned', 'description'=> 'Dossier is not assigned to an assessor','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Assigned', 'description'=> 'Dossier is assigned to an assessor','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=> 'Inprogress', 'description'=> 'Dossier is assigned and is Inprogress','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Completed', 'description'=> 'Dossier evaluation is completed.','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Pending', 'description'=>  'Dossier is assigned to an assessor but the assessor has not yet started evaluation','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Queued', 'description'=>  'Dossier is Queued for Certification/Registration to Committee','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Locked', 'description'=>  'Dossier evaluation is temporarily locked for reasons such as task deadline expiry, evaluation deadline expiry.','created_at'=>now(),'updated_at'=>now()]);
        DB::table('dossier_status_lookups')->insert( ['status'=>'Reassigned', 'description'=>  'Dossier is to be reassigned after appeal of applicant (after rejection decision) is accepted.','created_at'=>now(),'updated_at'=>now()]);



    }
}
