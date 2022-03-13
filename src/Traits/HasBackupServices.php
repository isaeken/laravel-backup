<?php

namespace IsaEken\LaravelBackup\Traits;

use IsaEken\LaravelBackup\Contracts\Backup\Service;

trait HasBackupServices
{
    private array $backupServices = [];

    /**
     * Add a backup service.
     *
     * @param  Service  $service
     * @return $this
     */
    public function addBackupService(Service $service): static
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
