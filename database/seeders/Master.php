<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Master extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->call([
        CountrySeeder::class,
        Users::class,
        DatabaseSeeder::class,
        dosageforms::class,
        fasttrackapplication::class,
        apis::class,
        medicinesSeeder::class,
        route_administrations::class,
        payment_configuration::class,
        CreateAdminUserSeeder::class,
        PermissionTableSeeder::class,
        company_supplier_template::class,
        local_agent_seed::class,
        DocumentTypeSeeder::class,
        DossierStatusLookUpSeeder::class,
        TemplateSeeder::class,
        AppSettingsSeeder::class,
        Forgot_password_questions::class,
        //Runperu::class,

         // api_manufacturers::class,
        // company_suppliers::class,
       // contacts::class,


    ]);
    }
}
