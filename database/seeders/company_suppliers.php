<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class company_suppliers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('company_suppliers')->insert( 
            [ 
            'user_id'=>10,
            'trade_name'=>'Andorra',
            'country_id'=>'13',
            'city'=>'1',
            'address_line_one'=>'USA',
            'address_line_two'=>'California,Oakland BayArea',
            'country_code'=>'+515',
            'telephone'=>'369784548',
            'email'=>'androra.com.tr',
            'webiste_url'=>'http://andropharmogivenlance.com',
            'contacts_id'=>'1',       
             'created_at'=>now(),
            'updated_at'=>now()

            ]);

                
            DB::table('company_suppliers')->insert( 
            [    'user_id'=>10,
                'trade_name'=>'Bandorra',
                'country_id'=>'12',
                'city'=>'1',
                'address_line_one'=>'USA',
                'address_line_two'=>'California,Oakland BayArea',
                'country_code'=>'+515',
                'telephone'=>'369784548',
                'email'=>'www.androra.com.tr',
                'webiste_url'=>'http://andropharmogivenlance.com',
                'contacts_id'=>'1',       
                 'created_at'=>now(),
                'updated_at'=>now()
    
                ]);
    
    }
}
