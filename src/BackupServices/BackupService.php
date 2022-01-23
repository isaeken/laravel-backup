<?php

namespace IsaEken\LaravelBackup\BackupServices;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Abstracts\HasOutputWithLogger;
use IsaEken\LaravelBackup\Contracts\Compressor;
use Spatie\TemporaryDirectory\TemporaryDirectory;

abstract class BackupService extends HasOutputWithLogger implements \IsaEken\LaravelBackup\Contracts\BackupService
{
    protected string $name;

    protected string|null $outputFile = null;

    protected bool $success = false;

    protected Compressor|null $compressor = null;

    protected OutputStyle|null $output = null;

    protected TemporaryDirectory $temporaryDirectory;

    public function __construct($container)
    {
        if ($container?->getOutput() instanceof OutputStyle) {
            $this->setOutput($container->getOutput());
        }

        $this->temporaryDirectory = (new TemporaryDirectory())
            ->name(Str::slug('backup-' . config('app.name') . '-' . $this->getName(), '_'))
            ->force()
            ->create()
            ->empty();
    }

    public function __destruct()
    {
        // $this->temporaryDirectory->delete();
    }

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
    public function getCompressor(): Compressor|null
    {
        return $this->compressor;
    }

    /**
     * @inheritDoc
     */
    public function setCompressor(string|Compressor $compressor): static
    {
        $this->compressor = $compressor instanceof Compressor ? $compressor : new $compressor($this);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return $this->outputFile !== null && $this->success;
    }

    /**
     * @inheritDoc
     */
    public function getOutputFile(): string|null
    {
        return $this->outputFile;
    }
}
