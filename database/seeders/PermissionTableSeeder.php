<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // 'product-list',
            // 'product-create',
            // 'product-edit',
            // 'product-delete',
            'application-list',
            'application-status-list',
            'dossier_sample_status',
            // 'assessor-invoice-list',
            // 'assessor-receipt-list',
            'dossier-assignment',
            'inspection_roles',
            'qc_roles',
            'supervisor_roles',
            'assessor_roles',
            'perc_roles',
            'nmfa_director'

         ];
 
 
 
         foreach ($permissions as $permission) {
 
              Permission::create(['name' => $permission]);
 
         }
    }

}
