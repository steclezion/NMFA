<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class contacts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contacts')->insert( 
            [ 
            'user_id'=>10,
            'first_name'=>'Simon',
            'middle_name'=>'Yemane',
            'last_name'=>'Uqbit',
            'country_id'=>'14',
            'position'=>'Seniour Officier',
            'city'=>'SanHoath',
            'address_line_one'=>'California',
            'address_line_two'=>'California,Oakland House number 82',
            'postal_code'=>'2015',
            'telephone'=>'+50547377373',       
            'webiste_url'=>'http://andropharmogivenlance.com',
            'email'=>'Simona_polo_paradizo@gmail.com',
            'contact_type'=>'Supplier',
            'created_at'=>now(),
            'updated_at'=>now()

            ]);

            DB::table('contacts')->insert( 
                [ 
                'user_id'=>10,
                'first_name'=>'daDimon',
                'middle_name'=>'saDemane',
                'last_name'=>'saDqbit',
                'country_id'=>'15',
                'position'=>'Manager Officier',
                'city'=>'SanHoath',
                'address_line_one'=>'California',
                'address_line_two'=>'California,Oakland House number 82',
                'postal_code'=>'2015',
                 'telephone'=>'+50547377373',       
               'webiste_url'=>'http://androphardmogivenlance.com',
                'email'=>'Simona_polo_paradsddizo@gmail.com',
                'contact_type'=>'Supplier',
                'created_at'=>now(),
                'updated_at'=>now()
    
                ]);
    }
}
