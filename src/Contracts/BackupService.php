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
     * Set the backup provider name.
     *
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name): static;

    /**
     * Check if backup is successfully generated.
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Set success status.
     *
     * @param  bool  $success
     * @return $this
     */
    public function setSuccessStatus(bool $success): static;

    /**
     * Get the generated backup file.
     *
     * @return string|null
     */
    public function getOutputFile(): string|null;

    /**
     * Set the output file path.
     *
     * @param  string|null  $path
     * @return $this
     */
    public function setOutputFile(string|null $path): static;

    /**
     * Generate a new backup.
     *
     * @return void
     */
    public function run(): void;
}
