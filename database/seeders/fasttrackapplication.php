<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class fasttrackapplication extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('fasttrackapplication')->insert( ['name'=>'WHO PQP','description'=>'-','created_at'=>now(),'updated_at'=>now()]);
        DB::table('fasttrackapplication')->insert( ['name'=>'SRA product','description'=>'-','created_at'=>now(),'updated_at'=>now()]);
        DB::table('fasttrackapplication')->insert( ['name'=>'MoH Tender','description'=>'-','created_at'=>now(),'updated_at'=>now()]);

    }
}
