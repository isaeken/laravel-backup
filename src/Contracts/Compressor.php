<?php

namespace IsaEken\LaravelBackup\Contracts;

interface Compressor
{
    /**
     * Get the service instance.
     *
     * @return BackupService
     */
    public function getBackupService(): BackupService;

    /**
     * Set the service instance.
     *
     * @param BackupService $service
     * @return $this
     */
    public function setBackupService(BackupService $service): static;

    /**
     * Get source.
     *
     * @return string
     */
    public function getSource(): string;

    /**
     * Get destination.
     *
     * @return string
     */
    public function getDestination(): string;

    /**
     * Set the source.
     *
     * @param string $source
     * @return $this
     */
    public function setSource(string $source): static;

    /**
     * Set the destination.
     *
     * @param string $destination
     * @return $this
     */
    public function setDestination(string $destination): static;

    /**
     * Compress given source to destination.
     *
     * @return bool
     */
    public function run(): bool;
}
