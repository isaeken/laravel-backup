<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;

trait HasBackupStorages
{
    private array $backupStorages = [];

    /**
     * Add a backup storage.
     *
     * @param  Filesystem  $filesystem
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem): static
    {
        $this->backupStorages[] = $filesystem;
        return $this;
    }

    /**
     * Get all backup storage instances.
     *
     * @return array
     */
    public function getBackupStorages(): array
    {
        return $this->backupStorages;
    }
}
