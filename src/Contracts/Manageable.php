<?php

namespace IsaEken\LaravelBackup\Contracts;

interface Manageable
{
    /**
     * Get the backup manager.
     *
     * @return BackupManager
     */
    public function getBackupManager(): BackupManager;

    /**
     * Set the backup manager.
     *
     * @param  BackupManager  $backupManager
     * @return $this
     */
    public function setBackupManager(BackupManager $backupManager): static;
}
