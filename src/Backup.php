<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Contracts\Filesystem\Filesystem;
use IsaEken\LaravelBackup\Contracts\Backup\Manager;
use IsaEken\LaravelBackup\Contracts\Backup\Service;
use IsaEken\LaravelBackup\Contracts\HasBackupServices;
use IsaEken\LaravelBackup\Contracts\HasBackupStorages;
use IsaEken\LaravelBackup\Contracts\HasLogger;
use IsaEken\LaravelBackup\Contracts\HasNotifications;
use IsaEken\LaravelBackup\Contracts\HasPassword;

class Backup implements Manager, HasLogger, HasBackupServices, HasBackupStorages, HasPassword, HasNotifications
{
    use Traits\HasBackupServices;
    use Traits\HasBackupStorages;
    use Traits\HasPassword;
    use Traits\HasLogger;
    use Traits\HasNotifications;

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $this->info('Backup is started...');
        $backups = [];

        /** @var Service $service */
        foreach ($this->getBackupServices() as $service) {
            $this->debug('Running backup service: '.$service->getName());

            if ($service instanceof HasLogger) {
                $this->debug('Setting service logger: '.$this->getOutput()::class);
                $service->setOutput($this->getOutput());
            }

            if ($service instanceof HasPassword) {
                $this->debug('Setting service password: '.$this->getPassword());
                $service->setPassword($this->getPassword());
            }

            $this->debug('Backup generating...');
            $service->run();

            if ($service->isSuccessful()) {
                $this->debug('Backup generated.');

                if ($service->getOutputFile() !== null) {
                    $this->debug('Output file: '.$service->getOutputFile());
                    $backups[] = $service;
                }
            } else {
                $this->error('Backup is cannot be created!');
            }
        }

        $this->info('Saving backups to storages...');

        foreach ($backups as $backup) {
            /** @var Filesystem $storage */
            foreach ($this->getBackupStorages() as $storage) {
                $storageClass = $storage::class;

                $this->info("Saving backup '{$backup->getName()}' with using driver: '$storageClass'");
                if ($storage->put(
                    config('backup.prefix').basename($backup->getOutputFile()),
                    file_get_contents($backup->getOutputFile())
                )) {
                    $this->debug('Backup saved successfully.');
                } else {
                    $this->error('Backup cannot be saved with using driver: '.$storageClass);
                }
            }
        }

        $this->success('Backup completed.');
    }
}
