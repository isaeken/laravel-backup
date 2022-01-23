<?php

namespace IsaEken\LaravelBackup\Tasks;

use Illuminate\Console\OutputStyle;
use IsaEken\LaravelBackup\Contracts\BackupManager;

abstract class Backup
{
    public BackupManager $backupManager;

    public array $services = [];

    public array $storages = [];

    public array $notifications = [];

    public string $password = '';

    public OutputStyle|null $output = null;

    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;
        return $this;
    }

    public function run(): void
    {
        $this->backupManager = new \IsaEken\LaravelBackup\Backup();

        if ($this->output !== null) {
            $this->backupManager->setOutput($this->output);
        }

        foreach ($this->services as $service) {
            $this->backupManager->addBackupService($service);
        }

        foreach ($this->storages as $storage) {
            $this->backupManager->addBackupStorage($storage);
        }

        // @todo
        // foreach ($this->notifications as $notification) {
        //     $this->backupManager->addNotificationChannel($notification);
        // }

        $this->backupManager
            ->setPassword($this->password)
            ->run();
    }
}
