<?php

namespace IsaEken\LaravelBackup\Tasks;

use Illuminate\Filesystem\FilesystemManager;
use IsaEken\LaravelBackup\Backup;
use IsaEken\LaravelBackup\BackupServices\DatabaseBackupService;
use IsaEken\LaravelBackup\BackupServices\StorageBackupService;

class FakeBackup extends Backup
{
    public function __construct()
    {
        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = app('filesystem');

        $this->addBackupService(new DatabaseBackupService());
        $this->addBackupService(new StorageBackupService());
        $this->addBackupStorage($filesystemManager->drive($filesystemManager->getDefaultDriver()));
    }
}
