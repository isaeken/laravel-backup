<?php

use Illuminate\Notifications\Notifiable;
use IsaEken\LaravelBackup\Notifications\BackupCreatedNotification;
use IsaEken\LaravelBackup\Services;

return [

    /*
    |--------------------------------------------------------------------------
    | Backup file prefix
    |--------------------------------------------------------------------------
    */

    'prefix' => 'backup_',

    /*
    |--------------------------------------------------------------------------
    | Services (eg: database, storage, image etc...)
    |--------------------------------------------------------------------------
    */

    'services' => [
        Services\DatabaseService::class,
        Services\StorageService::class,
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
            BackupCreatedNotification::class => ['mail'],
        ],

        'notifiable' => Notifiable::class,

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
