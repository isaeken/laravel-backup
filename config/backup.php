<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup database configuration (backup times, health status etc.)
    |--------------------------------------------------------------------------
    */

    'database' => [

        /*
        |--------------------------------------------------------------------------
        | Database driver
        |--------------------------------------------------------------------------
        */
        'driver' => IsaEken\LaravelBackup\Databases\LocalDatabase::class,

        /*
        |--------------------------------------------------------------------------
        | Database path
        |--------------------------------------------------------------------------
        */

        'path' => storage_path('backups.json'),

        /*
        |--------------------------------------------------------------------------
        | Database model
        |--------------------------------------------------------------------------
        */

        'model' => IsaEken\LaravelBackup\Models\Backup::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Backup filename pattern
    |--------------------------------------------------------------------------
    */

    'filename_pattern' => 'backup_:app_name_:service_name_:datetime',

    /*
    |--------------------------------------------------------------------------
    | Services (eg: database, storage, image etc...)
    |--------------------------------------------------------------------------
    */

    'services' => [
        IsaEken\LaravelBackup\Services\DatabaseService::class,
        IsaEken\LaravelBackup\Services\StorageService::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Storages (eg: google drive, local, s3 etc...)
    |--------------------------------------------------------------------------
    */

    'storages' => [
        'local',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Channels (eg: email, slack etc...)
    |--------------------------------------------------------------------------
    */

    'notifications' => [
        'notifications' => [
            IsaEken\LaravelBackup\Notifications\BackupCreatedNotification::class => ['mail'],
        ],

        'notifiable' => Illuminate\Notifications\Notifiable::class,

        'mail' => [
            'to' => 'hello@example.com',
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',
            'channel' => null,
            'username' => null,
            'icon' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Password
    |--------------------------------------------------------------------------
    */

    'password' => env('BACKUP_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Library roots for ignore
    |--------------------------------------------------------------------------
    */

    'library_roots' => [
        'node_modules',
        'vendor',
    ],

];
