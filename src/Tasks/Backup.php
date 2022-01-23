<?php

namespace IsaEken\LaravelBackup\Tasks;

use IsaEken\LaravelBackup\Abstracts\HasOutputWithLogger;
use IsaEken\LaravelBackup\Compressors\ZipCompressor;
use IsaEken\LaravelBackup\Console\Output;
use IsaEken\LaravelBackup\Contracts\BackupService;
use IsaEken\LaravelBackup\Traits\HasAttributes;

/**
 * @method bool isBackupSource()
 * @method bool isBackupPublic()
 * @method bool isBackupStorage()
 * @method bool isBackupDatabase()
 * @method self setBackupSource(bool $value)
 * @method self setBackupPublic(bool $value)
 * @method self setBackupStorage(bool $value)
 * @method self setBackupDatabase(bool $value)
 */
class Backup extends HasOutputWithLogger
{
    use HasAttributes;

    private array $services = [];

    public function addBackupService(BackupService|string $service): static
    {
        if ($service instanceof BackupService) {
            $service = $service::class;
        }

        $this->services[] = $service;
        return $this;
    }

    public function run(): void
    {
        $this->info('Backup is started...');
        $backups = [];

        foreach ($this->services as $service) {
            /** @var BackupService $backup */
            $backup = new $service($this);

            if ($this->getOutput()?->isVerbose()) {
                $this->info('Running backup service: ' . $backup->getName());
            }

            $backup->setCompressor(ZipCompressor::class)->run();

            if ($backup->isSuccessful()) {
                if ($backup->getOutputFile() !== null) {
                    $backups[] = $backup->getOutputFile();
                }
            }
        }

        // @todo

        $this->info('Backup completed.');
    }
}
