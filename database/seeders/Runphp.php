<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class Runphp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $Delete_Directory = Storage::deleteDirectory('public/dossiers');
        $create_directry = Storage::makeDirectory('public/dossiers');
      //  $Delete_Directory = Storage::deleteDirectory('dossiers');
      //  $create_directry = Storage::makeDirectory('dossiers');
       $Run_Storage_link = Artisan::call('storage:link');

       // Artisan::call('serve');
       // Artisan::call('websockets:serve');
    
        
    }
}
