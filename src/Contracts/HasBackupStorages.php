<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;

interface HasBackupStorages
{
    /**
     * Add a backup storage.
     *
     * @param  Filesystem  $filesystem
     * @param  string|null  $driver
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem, string|null $driver = null): static;

    /**
     * Get all backup storage instances.
     *
     * @return array<Filesystem>
     */
    public function getBackupStorages(): array;
}
