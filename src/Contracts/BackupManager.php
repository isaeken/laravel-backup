<?php

namespace IsaEken\LaravelBackup\Contracts;

interface BackupManager
{
    /**
     * Add a backup service to instance.
     *
     * @param BackupService|string $service
     * @return $this
     */
    public function addBackupService(BackupService|string $service): static;

    /**
     * Add a backup storage to instance.
     *
     * @param BackupStorage|string $storage
     * @return $this
     */
    public function addBackupStorage(BackupStorage|string $storage): static;

    /**
     * Run the backup services.
     *
     * @return void
     */
    public function run(): void;
}
