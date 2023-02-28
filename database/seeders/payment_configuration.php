<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class payment_configuration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('payment_configuration')->insert( 
            [ 
            'payment_type' =>'Application_fee',
             'amount'=>'500.00',
             'set_date'=>now(),
             'description'=>'application fee',
             'is_active'=>'1',
            'created_at'=>now(),
            'updated_at'=>now()

            ]);


            DB::table('payment_configuration')->insert( 
                [ 
                'payment_type' =>'Application_Reregistration',
                 'amount'=>'1500.00',
                 'set_date'=>now(),
                 'description'=>'application fee',
                 'is_active'=>'1',
                'created_at'=>now(),
                'updated_at'=>now()
    
                ]);


    }
}
