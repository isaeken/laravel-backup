<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;

interface HasBackupStorages
{
    /**
     * Add a backup storage.
     *
     * @param  Filesystem  $filesystem
     * @return $this
     */
    public function addBackupStorage(Filesystem $filesystem): static;
}
