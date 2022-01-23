<?php

namespace IsaEken\LaravelBackup\Contracts;

interface BackupStorage
{
    /**
     * Get the name of storage instance.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the backup manager.
     *
     * @return BackupManager
     */
    public function getBackupManager(): BackupManager;

    /**
     * Set the backup manager.
     *
     * @param BackupManager $backupManager
     * @return $this
     */
    public function setBackupManager(BackupManager $backupManager): static;

    /**
     * Store a resource to storage.
     *
     * @param string $filepath
     * @param string $directory
     * @return bool
     */
    public function save(string $filepath, string $directory): bool;
}
