<?php

use IsaEken\LaravelBackup\BackupServices;

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

    'services' => [
        BackupServices\DatabaseBackupService::class,
        BackupServices\StorageBackupService::class,
    ],

    'storages' => [
        'local',
    ],

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
            FakeBackupStorage::class,
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
            FakeBackupStorage::class,
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
