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
     * Get the backup compressor.
     *
     * @return Compressor|null
     */
    public function getCompressor(): Compressor|null;

    /**
     * Set the backup compressor engine.
     *
     * @param Compressor|string $compressor
     * @return $this
     */
    public function setCompressor(Compressor|string $compressor): static;

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
