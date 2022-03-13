<?php

namespace IsaEken\LaravelBackup\Contracts;

use IsaEken\LaravelBackup\Contracts\Backup\Service;

interface HasBackupServices
{
    /**
     * Add a backup service.
     *
     * @param  Service  $service
     * @return $this
     */
    public function addBackupService(Service $service): static;

    /**
     * Get all backup services.
     *
     * @return array
     */
    public function getBackupServices(): array;
}
