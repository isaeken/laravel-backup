<?php

namespace IsaEken\LaravelBackup\BackupServices;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts;
use IsaEken\LaravelBackup\Traits;

class DatabaseBackupService extends BackupService implements Contracts\BackupService, Contracts\HasLogger, Contracts\UsesTemporaryDirectory
{
    use Traits\HasLogger;
    use Traits\UsesTemporaryDirectory;

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
            $this->debug('Backup in progress for database with "sqlite" driver...');
            $this->sqlite();
        } else {
            $this->error('Unsupported database driver.');
        }
    }

    private function sqlite(): void
    {
        $this->makeTemporaryDirectory('sqlite');
        $databasePath = $this->getConnection()['database'];
        $databaseName = Str::beforeLast(basename($databasePath), '.');
        $filename = $databaseName.'_'.now()->format('Y-m-d-H-i-s').'.sqlite';
        $filepath = $this->getTemporaryDirectory('sqlite')->path($filename);
        @File::copy($databasePath, $filepath);

        $this
            ->setOutputFile($filepath)
            ->setSuccessStatus(true);

        $this->success('Backup generated: '.$this->getOutputFile());
    }
}
