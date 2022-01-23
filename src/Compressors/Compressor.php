<?php

namespace IsaEken\LaravelBackup\Compressors;

use IsaEken\LaravelBackup\Contracts\BackupService;

abstract class Compressor implements \IsaEken\LaravelBackup\Contracts\Compressor
{
    protected string $source;

    protected string $destination;

    public function __construct(public BackupService $service)
    {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function getBackupService(): BackupService
    {
        return $this->service;
    }

    /**
     * @inheritDoc
     */
    public function setBackupService(BackupService $service): static
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @inheritDoc
     */
    public function setSource(string $source): static
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDestination(string $destination): static
    {
        $this->destination = $destination;
        return $this;
    }
}
