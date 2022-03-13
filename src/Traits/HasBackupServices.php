<?php

namespace IsaEken\LaravelBackup\Traits;

use IsaEken\LaravelBackup\Contracts\BackupService;

trait HasBackupServices
{
    private array $backupServices = [];

    /**
     * Add a backup service.
     *
     * @param  BackupService  $service
     * @return $this
     */
    public function addBackupService(BackupService $service): static
    {
        $this->backupServices[] = $service;
        return $this;
    }

    /**
     * Get all backup services.
     *
     * @return array
     */
    public function getBackupServices(): array
    {
        return $this->backupServices;
    }
}
