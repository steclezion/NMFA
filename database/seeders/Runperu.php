<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class Runperu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $path1 = public_path();

        system(' echo Removing existing symbolic links in public directory...');
        echo system('rm -r ' .$path1.'/storage '.$path1.'/documents ' .$path1.'/dossiers');
        system('echo Task Completed.');


        $Array_of_directories =
            [
                'documents',
                'dossiers',
                'Acknowledgement_Letter',
                'Acknowledgement_Receipt_of_Registration_Application',
                'Collected_Receipt_PDFS',
                'DeclarationNote',
                'Acknowledgement_Receipt_of_PSUR',
                'Financial_Notification',
                'Generated_Invoice_PDFS',
                'invoices',
                'PreliminaryScreening',
                'Upload_swift_payments',
                'UploadCv',
                'Uploadpsur',
                'Document_Uploaded_To_Applicant',
                'Upload_Nmfa_Director_Alert_File'

            ];

        //$Delete_Directory = Storage::deleteDirectory('public/*');

        system(' echo Deleting Existing Work Directories ...');
      foreach ($Array_of_directories as $directories) {

            $Delete_Directory = Storage::deleteDirectory('public/' . $directories);

        }


        system(' echo Deleting New Work Directories ...');
        foreach ($Array_of_directories as $directories) {

            $create_directory = Storage::makeDirectory('public/' . $directories);


            if ($directories == 'Acknowledgement_Letter') {
                Storage::makeDirectory('public/' . $directories . '/' . 'Document_Uploaded_To_Applicant');

                Storage::makeDirectory('public/' . $directories . '/' . 'System_Generated_Documents');

            }


            if ($directories == 'Acknowledgement_Receipt_of_Registration_Application') {
                Storage::makeDirectory('public/' . $directories . '/' . 'saved_before_sealed');
                Storage::makeDirectory('public/' . $directories . '/' . 'uploaded_to_applicant');
            }

            if ($directories == 'Acknowledgement_Receipt_of_PSUR') {
                Storage::makeDirectory('public/' . $directories . '/' . 'saved_before_sealed');
                Storage::makeDirectory('public/' . $directories . '/' . 'uploaded_to_applicant');
            }


            if ($directories == 'documents') {
                Storage::makeDirectory('public/' . $directories . '/' . 'uploads');
            }


            if ($directories == 'Financial_Notification') {
                Storage::makeDirectory('public/' . $directories . '/' . 'Financial_applicant_side_uploaded_sealed');
                Storage::makeDirectory('public/' . $directories . '/' . 'Financial_client_side_un_sealed');
            }


            if ($directories == 'invoices') {
                Storage::makeDirectory('public/' . $directories . '/' . 'Uploaded_to_applicant');
            }


            if ($directories == 'PreliminaryScreening') {
                Storage::makeDirectory('public/' . $directories . '/' . 'Document_Uploaded_To_Applicant');
                Storage::makeDirectory('public/' . $directories . '/' . 'Document_Uploaded_To_Assessor');
                Storage::makeDirectory('public/' . $directories . '/' . 'System_Generated_Documents');
            }


        }

        $path = storage_path('app/public/');

        system('echo Creating App Files, Directories, and Permissions ...');
        system('echo Please Enter Server Password');
        system('sudo chmod -R 0777 ' . $path);
        system('echo Task Completed.');

        system('echo optimize:clear...');
        Artisan::call('optimize:clear');
        system('echo Task Completed.');

        system('echo migrate:fresh...');
        Artisan::call('migrate:fresh');
        system('echo Task Completed.');

        system('echo db:seed --class=Master ...');
        Artisan::call('db:seed --class=Master');
        system('echo Task Completed.');

        system('echo storage:link...');
        Artisan::call('storage:link');
        system('echo Task Completed.');

        system('serve --host=172.18.10.253  --port=9090');
        Artisan::call('serve --port=9090');
        system('echo Task Completed.');

       system('echo websockets:serve...');
        Artisan::call('websockets:serve');
        system('echo Task Completed.');


    }
}
