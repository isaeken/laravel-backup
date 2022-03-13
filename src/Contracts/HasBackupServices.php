<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasBackupServices
{
    /**
     * Add a backup service.
     *
     * @param  BackupService  $service
     * @return $this
     */
    public function addBackupService(BackupService $service): static;
}
