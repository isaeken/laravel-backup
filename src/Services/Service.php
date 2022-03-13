<?php

namespace IsaEken\LaravelBackup\Services;

use IsaEken\LaravelBackup\Contracts;

abstract class Service implements Contracts\Backup\Service
{
    protected string $name;

    private string|null $outputFile = null;

    private bool $success = false;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return $this->success;
    }

    /**
     * @inheritDoc
     */
    public function setSuccessStatus(bool $success): static
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOutputFile(): string|null
    {
        return $this->outputFile;
    }

    /**
     * @inheritDoc
     */
    public function setOutputFile(string|null $path): static
    {
        $this->outputFile = $path;
        return $this;
    }
}
