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
     * Store a resource to storage.
     *
     * @param string $filepath
     * @param string $directory
     * @return bool
     */
    public function save(string $filepath, string $directory): bool;
}
