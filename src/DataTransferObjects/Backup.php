<?php

namespace IsaEken\LaravelBackup\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Backup implements \IsaEken\LaravelBackup\Contracts\Backup\Backup
{
    private Filesystem|null $storage = null;

    private string|null $driver = null;

    private string|null $filename = null;

    private Carbon|null $date = null;

    public function __construct(Filesystem $storage, string $driver, string $filename, Carbon $date)
    {
        $this
            ->setStorage($storage)
            ->setDriver($driver)
            ->setFilename($filename)
            ->setDate($date);
    }

    /**
     * @inheritDoc
     */
    public function getStorage(): Filesystem
    {
        return $this->storage;
    }

    /**
     * @inheritDoc
     */
    public function setStorage(Filesystem $storage): static
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @inheritDoc
     */
    public function setDriver(string|null $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @inheritDoc
     */
    public function setFilename(string $filename): static
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function setDate(Carbon $date): static
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape([
        'storage' => "\Illuminate\Contracts\Filesystem\Filesystem", 'filename' => "string", 'date' => "\Carbon\Carbon"
    ])]
    public function toArray(): array
    {
        return [
            'storage' => $this->getStorage(),
            'filename' => $this->getFilename(),
            'date' => $this->getDate(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): bool|string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
