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
        
        'ktp' => [
            'driver' => 'local',
            'root' => storage_path('app/public/files/ktp'),
            'url' => env('APP_URL').'/document/ktp',
        ],
        
        'file_photo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/files/file_photo'),
            'url' => env('APP_URL').'/document/file_photo',
        ],
        
        'kandidat' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/kandidat'),
            'url' => env('APP_URL').'/images/kandidat',
        ],
        
        'photo_vote_final' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images/photo_vote_final'),
            'url' => env('APP_URL').'/images/photo_vote_final',
        ],
        
        'ktp_vote_final' => [
            'driver' => 'local',
            'root' => storage_path('app/public/files/ktp_vote_final'),
            'url' => env('APP_URL').'/document/ktp_vote_final',
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
        // public_path('storage') => storage_path('app/public'),
        public_path('files') => storage_path('app/public/files'),
        public_path('images') => storage_path('app/public/images'),
        public_path('assets') => storage_path('app/public/assets')
    ],

];
