<?php

namespace IsaEken\LaravelBackup\Storages;

use IsaEken\LaravelBackup\Contracts\BackupManager;

abstract class BackupStorage implements \IsaEken\LaravelBackup\Contracts\BackupStorage
{
    protected string $name;

    protected BackupManager $backupManager;

    public function __construct(BackupManager $backupManager)
    {
        $this->setBackupManager($backupManager);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getBackupManager(): BackupManager
    {
        return $this->backupManager;
    }

    /**
     * @inheritDoc
     */
    public function setBackupManager(BackupManager $backupManager): static
    {
        $this->backupManager = $backupManager;
        return $this;
    }
}
