<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class route_administrations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
DB::table('route_administrations')->insert( ['name' => 'Buccal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Dental','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Epidural','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Epicutaneous','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'External','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Implant','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Inhalation','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Injection','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intradermal\Intramuscular','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intraocular','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intraosseous','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intrathecal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intrauterine','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Intravenous','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Mouth/Throat','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Nasal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Ophthalmic','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Oral','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Otic','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Rectal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Subcutaneous','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Sublingual','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Topical','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Transdermal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);
DB::table('route_administrations')->insert( ['name' => 'Vaginal','description'=>'--','created_at'=>now(),'updated_at'=>now()]);


    }
}
