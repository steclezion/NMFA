<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class local_agent_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('local_agent_template')->insert([
        'user_id'=>10, 
        'trade_name'=>'Erimedical East Africa P.L.C', 
        'country_id'=>'68',
        'city'=>'Asmara',
        'address_line_one'=>'Asmara',
        'address_line_two'=>'Eritro-German Building, Asmara',
        'state'=>'Eritrea',
        'postal_code'=>'4381',
        'country_code'=>'+291',
        'telephone'=>'+291-1-121945', 
        'email'=>'erimedical.plc@gmail.com',
        'webiste_url'=>'',
        'created_at'=>now(),
        'updated_at'=>now() 
        ]);

        DB::table('local_agent_template')->insert([
            'user_id'=>10, 
            'trade_name'=>'Karrar Hidad Karrar for Medicine and Medical Supplies', 
            'country_id'=>'68',
            'city'=>'Asmara',
            'address_line_one'=>'Asmara',
            'address_line_two'=>'Bada St. 746/11, Asmara',
            'state'=>'Eritrea',
            'postal_code'=>'1026',
            'country_code'=>'+291',
            'telephone'=>'+291-17116675,+291-17116675', 
            'email'=>'khkpharmamedic@gmail.com',
            'webiste_url'=>'',
            'created_at'=>now(),
            'updated_at'=>now() 
            ]);

            DB::table('local_agent_template')->insert([
                'user_id'=>10, 
                'trade_name'=>'Dej. Gonafer & Sons P.L.Co. (Eritrea) Healthcare Division', 
                'country_id'=>'68',
                'city'=>'Asmara',
                'address_line_one'=>'BDHO Street 171, House No. 42/46',
                'address_line_two'=>'Bada St. 746/11, Asmara',
                'state'=>'Eritrea',
                'postal_code'=>'1107',
                'country_code'=>'+291',
                'telephone'=>'+291-1-110928', 
                'email'=>'gonaferhealthcare@gmail.com',
                'webiste_url'=>'',
                'created_at'=>now(),
                'updated_at'=>now() 
                ]);
        
           
           
         
        
          
    }
}
