<?php

use IsaEken\LaravelBackup\BackupServices\StorageBackupService;
use IsaEken\LaravelBackup\Storages\FakeStorage;

// @todo
return [

    /*
    |--------------------------------------------------------------------------
    | Application name for backup categories
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Backup configuration
    |--------------------------------------------------------------------------
    */

    'backup' => [

        /*
         * Backup services (eg: database service, storage service etc...)
         */

        'services' => [
            StorageBackupService::class,
        ],


        /*
         * Backup storages (eg: google drive, local storage etc...)
         */

        'storages' => [
            FakeStorage::class,
        ],


        /*
         * Backup notification channels (eg: email, slack, discord etc...)
         */

        'notifications' => [
            // ...
        ],


        /*
         * Backup encryption password
         */

        'password' => null,


        /*
         * Backup encryption algorithm
         */

        'encryption' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup clean configuration
    |--------------------------------------------------------------------------
    */

    'cleanup' => [

        /*
         * Clean storages
         */

        'storages' => [
            FakeStorage::class,
        ],

        // ...
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup notification service configurations
    |--------------------------------------------------------------------------
    */

    'notifications' => [
        // ...
    ],

    /*
    |--------------------------------------------------------------------------
    | Library roots for ignore
    |--------------------------------------------------------------------------
    */

    'library_roots' => [
        'node_modules',
        'vendor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Temporary directory path for backup generate. (null for system temp dir.)
    |--------------------------------------------------------------------------
    */

    'temporary_directory' => null,

];
