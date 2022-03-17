<?php

namespace IsaEken\LaravelBackup\Contracts\Backup;

interface DatabaseDriver
{
    /**
     * Get backup collection.
     *
     * @return BackupCollection
     */
    public function backups(): BackupCollection;

    /**
     * Get size summary for backups.
     *
     * @return float
     */
    public function size(): float;

    /**
     * Load databases.
     *
     * @return $this
     */
    public function load(): static;

    /**
     * Save databases.
     *
     * @return $this
     */
    public function save(): static;
}
