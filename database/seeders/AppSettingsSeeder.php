<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('app_settings')->insert(['name'=>'dossier_ref_num_counter','value'=>1 ]);
        DB::table('app_settings')->insert(['name'=>'current_year','value'=>date('Y') ]);
        DB::table('app_settings')->insert(['name'=>'product_registration_counter','value'=>1 ]);
        DB::table('app_settings')->insert(['name'=>'letter_reference_number_counter','value'=>1 ]);
        DB::table('app_settings')->insert(['name'=>'Next_RL_count','value'=>1 ]);
       
    }
}
