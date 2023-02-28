<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            
        ],

        'dossier' => [
            'driver' => 'local',
            'root' => public_path('dossiers'),

            
            'url' => env('APP_URL').'/dossiers',
        ],

        'documents' => [
            'driver' => 'local',
            'root' => public_path('documents/uploads'),
            'url' => env('APP_URL').'/documents',
        ],




        'PreliminaryScreening' => [
            'driver' => 'local',
            'root' => public_path('storage/PreliminaryScreening/System_Generated_Documents'),
            'url' => env('APP_URL').'/storage/PreliminaryScreening',
        ],


        'Invoice' => [
            'driver' => 'local',
            'root' => public_path('storage/invoices/'),
            'url' => env('APP_URL').'/storage/invoices',
        ],


        'Acknowledgement_Letter' => [
            'driver' => 'local',
            'root' => public_path('storage/Acknowledgement_Letter/System_Generated_Documents'),
            'url' => env('APP_URL').'/storage/Acknowledgement_Letter/System_Generated_Documents',
        ],


        'Acknowledgement_Receipt_of_PSUR' => [
            'driver' => 'local',
            'root' => public_path('storage/Acknowledgement_Receipt_of_PSUR/saved_before_sealed'),
            'url' => env('APP_URL').'/storage/Acknowledgement_Receipt_of_PSUR/saved_before_sealed',
        ],




        'Acknowledgement_Receipt_of_Registration_Application' => [
            'driver' => 'local',
            'root' => public_path('storage/Acknowledgement_Receipt_of_Registration_Application/saved_before_sealed/'),
            'url' => env('APP_URL').'/storage/Acknowledgement_Receipt_of_Registration_Applicationsaved_before_sealed/',
        ],


        //Financial_Notification

        'Financial_Notification' => [
            'driver' => 'local',
            'root' => public_path('storage/Financial_Notification/Financial_client_side_un_sealed/'),
            'url' => env('APP_URL').'/storage/Financial_Notification/Financial_client_side_un_sealed/',
        ],






        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
       public_path('dossiers') => storage_path('app/public/dossiers'),
       public_path('documents') => storage_path('app/public/documents'),

 
        //Latter Revised from Dr Yemane Tedla
        // public_path('dossiers') => storage_path('dossiers'),
        // public_path('documents') => storage_path('documents'),

    ],

];
