<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class api_manufacturers extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('api_manufacturers')->insert( 
            [
              'user_id'=>10,
            'manufacturer_name'=>'Azel',
            'country_id'=>'12',
            'city'=> 'Asmara',
            'state'=>'Maekel',
            'addressline_one'=>'Asmara,Line',
            'addressline_two'=>'Asmara,Line,two',
            'postal_code'=>'304',
            'telephone'=>'117498',
             'created_at'=>now(),
            'updated_at'=>now()
            ] );

            DB::table('api_manufacturers')->insert( 
                [
                'user_id'=>10,
                'manufacturer_name'=>'Jordan Azel',
                 'country_id'=>'12',
                'city'=> 'Asmara',
                'state'=>'Maekel',
                'addressline_one'=>'Asmara,Line',
                'addressline_two'=>'Asmara,Line,two',
                'postal_code'=>'304',
                'telephone'=>'117498',
                 'created_at'=>now(),
                'updated_at'=>now()
                ] );
    }
}
