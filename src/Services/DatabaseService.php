<?php

namespace IsaEken\LaravelBackup\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts;
use IsaEken\LaravelBackup\Traits;

class DatabaseService extends Service implements Contracts\Backup\Service, Contracts\HasLogger, Contracts\UsesTemporaryDirectory
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
        $databaseName = Str::of(basename($databasePath))->beforeLast('.')->slug('')->value();
        $filename = $databaseName.'_'.now()->format('Y-m-d-H-i-s').'.sqlite';
        $filepath = $this->getTemporaryDirectory('sqlite')->path($filename);

        if ($databaseName == 'memory') {
            $this->error('Unsupported database driver: '.$databaseName);

            return;
        }

        @File::copy($databasePath, $filepath);

        $this
            ->setOutputFile($filepath)
            ->setSuccessStatus(true);

        $this->success('Backup generated: '.$this->getOutputFile());
    }
}
