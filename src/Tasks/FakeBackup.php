<?php

namespace IsaEken\LaravelBackup\Tasks;

use IsaEken\LaravelBackup\BackupServices\DatabaseBackupService;
use IsaEken\LaravelBackup\BackupServices\StorageBackupService;
use IsaEken\LaravelBackup\Storages\FakeBackupStorage;

class FakeBackup extends Backup
{
    public array $services = [
        DatabaseBackupService::class,
        StorageBackupService::class,
    ];

    public array $storages = [
        FakeBackupStorage::class,
    ];
}
