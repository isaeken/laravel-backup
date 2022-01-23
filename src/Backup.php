<?php

namespace IsaEken\LaravelBackup;

use IsaEken\LaravelBackup\Compressors\ZipCompressor;
use IsaEken\LaravelBackup\Contracts\BackupManager;
use IsaEken\LaravelBackup\Contracts\BackupService;
use IsaEken\LaravelBackup\Contracts\BackupStorage;
use IsaEken\LaravelBackup\Traits\HasOutput;

class Backup implements BackupManager
{
    use HasOutput;

    private array $services = [];

    private array $storages = [];

    /**
     * @inheritDoc
     */
    public function addBackupService(BackupService|string $service): static
    {
        if ($service instanceof BackupService) {
            $service = $service::class;
        }

        $this->services[] = $service;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addBackupStorage(BackupStorage|string $storage): static
    {
        if ($storage instanceof BackupStorage) {
            $storage = $storage::class;
        }

        $this->storages[] = $storage;
        return $this;
    }

    /**
     * @inheritDoc
     */
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
                    $backups[] = $backup;
                }
            }
        }

        $this->info('Saving backups to storages...');

        foreach ($backups as $backup) {
            foreach ($this->storages as $storage) {
                /** @var BackupStorage $storage */
                $storage = new $storage($this);

                if ($this->getOutput()?->isVerbose()) {
                    $this->info("Saving backup '{$backup->getName()}' to storage '{$storage->getName()}'");
                }

                if ($storage->save($backup->getOutputFile(), $backup->getName())) {
                    if ($this->getOutput()?->isVerbose()) {
                        $this->success("Backup saved successfully.");
                    }
                } else {
                    $this->error("Backup cannot be saved to storage {$backup->getName()} -> {$storage->getName()}");
                }
            }
        }

        $this->success('Backup completed.');
    }
}
