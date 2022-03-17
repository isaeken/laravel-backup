<?php

namespace IsaEken\LaravelBackup\Databases;

use IsaEken\LaravelBackup\Contracts\Backup\BackupCollection;
use IsaEken\LaravelBackup\Contracts\Backup\DatabaseDriver;
use IsaEken\LaravelBackup\Models\Backup;

class LocalDatabase implements DatabaseDriver
{
    protected ?\IsaEken\LaravelBackup\Collections\BackupCollection $backups = null;

    protected ?float $sizeCache = null;

    public function __construct()
    {
        $this->backups = new \IsaEken\LaravelBackup\Collections\BackupCollection();
    }

    /**
     * @inheritDoc
     */
    public function backups(): BackupCollection
    {
        return $this->backups;
    }

    /**
     * @inheritDoc
     */
    public function size(): float
    {
        if (!is_null($this->sizeCache)) {
            return $this->sizeCache;
        }

        return $this->sizeCache = $this->backups()->sum();
    }

    /**
     * @inheritDoc
     */
    public function load(): static
    {
        $backups = @json_decode(@file_get_contents(config('backup.database.path', storage_path('backups.json'))));
        foreach ($backups as $backup) {
            $backup = new (config('backup.database.model', Backup::class))((array) $backup);
            $this->backups()->add($backup);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function save(): static
    {
        @file_put_contents(config('backup.database.path'), $this->backups()->toJson());

        return $this;
    }
}
