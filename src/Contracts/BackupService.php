<?php

namespace IsaEken\LaravelBackup\Contracts;

interface BackupService
{
    /**
     * Get the backup provider name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Check if backup is successfully generated.
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Get the generated backup file.
     *
     * @return string|null
     */
    public function getOutputFile(): string|null;

    /**
     * Generate a new backup.
     *
     * @return void
     */
    public function run(): void;
}
