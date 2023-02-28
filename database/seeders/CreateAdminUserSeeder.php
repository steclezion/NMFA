<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ////  'password'=>'$2y$10$atVdj3FeStPKgiOJD675LegkqNAOKiVQdwX6WDUAip556JJsL3iz2',
        $Quality_control = User::create(['first_name' => 'Quality', 'middle_name' => 'Control', 'last_name' => 'Unit', 'email' => 'quality_control@nmfa.com','country_id'=>'78','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Quality Control','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Supervisor = User::create(['id'=>10, 'first_name'=>'Supervisor','middle_name'=>'supervisor','last_name'=>'-','email' => 'supervisor@nmfa.com','country_id'=>'68','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Supervisor','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Assessor_one =  User::create(['id'=>1,'first_name'=>'assessor_one','middle_name'=>'-',  'last_name'=>'-','email' => 'assessor_one@nmfa.com','country_id'=>'48','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Assessor_one','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Assessor_two =  User::create(['id'=>2, 'first_name'=>'assessor_two','middle_name'=>'Qeleta','last_name'=>'g','email' => 'assessor_two@nmfa.com','country_id'=>'38','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Assessor_two','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Assessor_three =  User::create(['id'=>5, 'first_name'=>'assessor_three','middle_name'=>'three_assessor','last_name'=>'-','email' => 'assessor_three@nmfa.com','country_id'=>'28','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Assessor_three','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Inspection = User::create(['id'=>3,'first_name'=>'inspection','middle_name'=>'-','last_name'=>'-','email' => 'inspection@nmfa.com','country_id'=>'18','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Inspection','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Applicant = User::create(['id'=>4,'first_name'=>'Applicant','middle_name'=>'-','last_name'=>'-','email' => 'applicant@nmfa.com','country_id'=>'6','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Applicant','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Admin = User::create(['id'=>8,'first_name'=>'Admin','middle_name'=>'-','last_name'=>'-','email' => 'Admin@nmfa.com','country_id'=>'58','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Admin','website_url'=>'http://t@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Nmfa_director = User::create(['id'=>9,'first_name'=>'Nmfa Director','middle_name'=>'-','last_name'=>'-','email' => 'nmfa_director@nmfa.com','country_id'=>'58','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'Director','website_url'=>'http://nmfa_director@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);
        $Perc = User::create(['id'=>6,'first_name'=>'Perc','middle_name'=>'-','last_name'=>'-','email' => 'perc@nmfa.com','country_id'=>'58','street'=> 'Mereb','city'=> 'Asmara','state'=>'Maekel','addressline_one'=>'Asmara,Line','addressline_two'=>'Asmara,Line,two','postal_code'=>'304', 'country_code'=>'+291', 'telephone'=>'7459736','fax'=>'200-n','user_name'=>'perc','website_url'=>'http://nmfa_director@gmail.com','two_factor_recovery_codes'=>'','two_factor_secret'=>'','business_address'=>'Asmara,Eritrea,Mereb-street block 228 room numeber 82','email_verified_at'=>now(),'created_at'=>now(),'updated_at'=>now(),'password' => bcrypt('1396')]);


        //$role = Role::create(['name' => 'Admin']);
        $role = Role::create(['name' => 'Applicant']);$role = Role::create(['name' => 'Assessor']);$role = Role::create(['name' => 'Quality Control']);$role = Role::create(['name' => 'Inspection']); $role = Role::create(['name' => 'PERC']);
       
        $role = Role::create(['name' => 'Supervisor']); $role = Role::create(['name' => 'Nmfa director']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);

       
        $Applicant->assignRole([2]); //Applicant Permission
        $Admin->assignRole([7,2,1,3,6,4,5]); //Admin Permission
        $Quality_control->assignRole([4,6]); //Quality Control
        $Assessor_one->assignRole([3]); //Assessor_one
        $Assessor_two->assignRole([3]); //Assessor_teo
        $Assessor_three->assignRole([3]); //Assessor_three
        $Inspection->assignRole([5,6]); //Inspection
        $Supervisor->assignRole([7]); //Supervisor
        $Nmfa_director->assignRole([8]); // Director
        $Perc->assignRole([6]); // Perc
      



        //Nmfa Director Roles
            DB::table('role_has_permissions')->insert( ['permission_id'=>'14','role_id'=>'8']);
         
    

            
    //Applicant Roles
        DB::table('role_has_permissions')->insert( ['permission_id'=>'5','role_id'=>'2']);
        DB::table('role_has_permissions')->insert( ['permission_id'=>'6','role_id'=>'2']);
        DB::table('role_has_permissions')->insert( ['permission_id'=>'7','role_id'=>'2']);


    //Supervisor Roles
          DB::table('role_has_permissions')->insert( ['permission_id'=>'11','role_id'=>'7']);
          DB::table('role_has_permissions')->insert( ['permission_id'=>'1','role_id'=>'7']);
          DB::table('role_has_permissions')->insert( ['permission_id'=>'2','role_id'=>'7']);
          DB::table('role_has_permissions')->insert( ['permission_id'=>'3','role_id'=>'7']);
          DB::table('role_has_permissions')->insert( ['permission_id'=>'4','role_id'=>'7']);
        
          //Quality Control

          DB::table('role_has_permissions')->insert( ['permission_id'=>'10','role_id'=>'4']);

          //Assessor
          DB::table('role_has_permissions')->insert( ['permission_id'=>'12','role_id'=>'3']);
          //Assessor
           DB::table('role_has_permissions')->insert( ['permission_id'=>'9','role_id'=>'5']);
        //PERC
        DB::table('role_has_permissions')->insert( ['permission_id'=>'13','role_id'=>'6']);
  



       


    }
}
