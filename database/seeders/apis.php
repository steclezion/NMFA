<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class apis extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('apis')->insert( 
            [
            'api_manufacturer_id'=>1,
            'api_name'=>'ORS',
            'api_type'=> 'api',
            'description'=>'--',
             'created_at'=>now(),
            'updated_at'=>now()
            ] );
            DB::table('apis')->insert( 
                [
                'api_manufacturer_id'=>2,
                'api_name'=>'Vitammin-B12',
                'api_type'=> 'api',
                'description'=>'--',
                 'created_at'=>now(),
                'updated_at'=>now()
                ] );
    }
}
