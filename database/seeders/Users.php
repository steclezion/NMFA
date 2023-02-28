<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('users')->insert([
        
           

           
           [
                'id'=>12,
                'first_name'=>'Simon',
                'middle_name'=>'OG',
                'last_name'=>'gebre',
                'country_id'=>'12',
                'street'=> 'referendum',
                'city'=> 'Asmara',
                'state'=>'Maekel',
                'addressline_one'=>'Asmara,Line',
                'addressline_two'=>'Asmara,Line,two',
                'postal_code'=>'304',
                'country_code'=>'+291',
                'telephone'=>'7459736',
                'fax'=>'200-n',
                'user_name'=>'Simon',
                'website_url'=>'http://steclezion@gmail.com',
                'email'=>'sami@gmail.com',
                'password'=>'$2y$10$4gLOk3mE2PRbyto69DlXOOl0qNO6tX5ejjo46zqkRwsf4xNCFovd.',
                'two_factor_recovery_codes'=>'',
                'two_factor_secret'=>'',
                'business_address'=>'Asmara,Eritrea,Referendum-street block 228 room numeber 24',
                'email_verified_at'=>now(),
                'created_at'=>now(),
                'updated_at'=>now()],
             [
                 'id'=>7,
                 'first_name'=>'Merhawi',
                 'middle_name'=>'Tsegai',
                 'last_name'=>'gebre',
                 'country_id'=>'12',
                 'street'=> 'referendum',
                 'city'=> 'Asmara',
                 'state'=>'Maekel',
                 'addressline_one'=>'Asmara,Line',
                 'addressline_two'=>'Asmara,Line,two',
                 'postal_code'=>'304',
                 'country_code'=>'+291',
                 'telephone'=>'7459736',
                 'fax'=>'200-n',
                 'user_name'=>'Merhawi',
                 'website_url'=>'http://merhawitsegai@gmail.com',
                 'email'=>'merhawi@gmail.com',
                 'password'=>'$2y$10$4gLOk3mE2PRbyto69DlXOOl0qNO6tX5ejjo46zqkRwsf4xNCFovd.',
                 'two_factor_recovery_codes'=>'',
                 'two_factor_secret'=>'',
                 'business_address'=>'Asmara,Eritrea,Referendum-street block 228 room numeber 24',
                 'email_verified_at'=>now(),
                 'created_at'=>now(),
                 'updated_at'=>now()]]


        );


    $role = Role::create(['name' => 'Admin']);
    $permissions = Permission::pluck('id','id')->all();
    $role->syncPermissions($permissions);
//    $user=DB::table('users')->where('id',10)->first();
//    $user->assignRole([$role->id]);
//        $user=DB::table('users')->where('id',7)->first();
//        $user->assignRole([$role->id]);


}


}