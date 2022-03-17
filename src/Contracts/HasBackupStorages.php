<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;

interface HasBackupStorages
{
    /**
     * Add a backup storage.
     *
     * @param  Filesystem  $filesystem
     * @param  string  $driver
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem, string $driver): static;

    /**
     * Get all backup storage instances.
     *
     * @return array<string, Filesystem>
     */
    public function getBackupStorages(): array;
}
