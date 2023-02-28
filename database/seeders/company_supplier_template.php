<?php

namespace Database\Seeders;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class company_supplier_template extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 

        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Azel Pharmacuetical Sh. Co.','country_id'=>'68','state'=>'Eritrea','country_code'=>'+291','created_at'=>now(),'updated_at'=>now() ]);
        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Mylan Laboratories Limited','country_id'=>'102','state'=>'India','country_code'=>'+91','created_at'=>now(),'updated_at'=>now()]);
        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Ipca Laboratories Limited','country_id'=>'102','state'=>'India','country_code'=>'+91','created_at'=>now(),'updated_at'=>now()]);
        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Macleods Pharmaceuticals Ltd.','country_id'=>'102','state'=>'India','country_code'=>'+91','created_at'=>now(),'updated_at'=>now()]);
        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Novo Nordisk Kenya Ltd','country_id'=>'113', 'state'=>'Kenya','country_code'=>'+254','created_at'=>now(),'updated_at'=>now()]);
        DB::table('company_supplier_template')->insert(['user_id'=>10,'is_Approved_By_NMFA'=>1,'is_Registerd_company'=>'Registerd','trade_name'=>'Serum Institute of India Pvt.Ltd.','country_id'=>'102', 'state'=>'India','country_code'=>'+515','created_at'=>now(),'updated_at'=>now()]);

            
    

    }
}
