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
     * @param  string|null  $driver
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem, string|null $driver = null): static
    {
        $this->backupStorages[$driver] = $filesystem;
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
