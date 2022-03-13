<?php

namespace IsaEken\LaravelBackup\Tasks;

use Illuminate\Filesystem\FilesystemManager;
use IsaEken\LaravelBackup\Backup;
use IsaEken\LaravelBackup\Services\DatabaseService;
use IsaEken\LaravelBackup\Services\StorageService;

class FakeBackup extends Backup
{
    public function __construct()
    {
        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = app('filesystem');

        $this->addBackupService(new DatabaseService());
        $this->addBackupService(new StorageService());
        $this->addBackupStorage($filesystemManager->drive($filesystemManager->getDefaultDriver()));
    }
}
