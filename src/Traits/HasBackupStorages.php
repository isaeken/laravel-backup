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
     * @param  string  $driver
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem, string $driver): static
    {
        $this->backupStorages[$driver] = $filesystem;

        return $this;
    }

    /**
     * Get all backup storage instances.
     *
     * @return array<string, Filesystem>
     */
    public function getBackupStorages(): array
    {
        return $this->backupStorages;
    }
}
