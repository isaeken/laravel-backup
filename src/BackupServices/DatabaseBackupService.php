<?php

namespace IsaEken\LaravelBackup\BackupServices;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\BackupServices\BackupService as BaseBackupService;

class DatabaseBackupService extends BaseBackupService implements \IsaEken\LaravelBackup\Contracts\BackupService
{
    protected string $name = 'database';

    public string|null $connection = null;

    public function setConnection(string $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

    public function getConnection(): array
    {
        $connection = $this->connection ?? config('database.default');
        return config('database.connections')[$connection];
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $driver = $this->getConnection()['driver'];

        if ($driver === 'sqlite') {
            $this->sqlite();
        }
    }

    private function sqlite(): void
    {
        $databasePath = $this->getConnection()['database'];
        $databaseName = Str::beforeLast(basename($databasePath), '.');
        $filename = $databaseName.'_'.now()->format('Y-m-d-H-i-s').'.sqlite';
        $filepath = $this->temporaryDirectory->path($filename);
        @File::copy($databasePath, $filepath);

        $this
            ->getCompressor()
            ->setSource($databasePath)
            ->setDestination($filepath);

        if ($this->getCompressor()->run()) {
            $this->outputFile = $this->getCompressor()->getDestination();
            $this->success = true;
            $this->success('Backup generated: '.$this->outputFile);
        } else {
            $this->error('Compression failed!');
        }
    }
}
