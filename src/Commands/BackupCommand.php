<?php

namespace IsaEken\LaravelBackup\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use IsaEken\LaravelBackup\Backup;
use IsaEken\LaravelBackup\Contracts\Backup\Service;

class BackupCommand extends Command
{
    protected $signature = 'backup:run {--services=*} {--storages=*} {--disable-notifications} {--timeout=}';

    protected $description = 'Run the backups.';

    public function handle(): int
    {
        $notifications = ! $this->option('disable-notifications');
        $services = $this->explodeOption($this->option('services'));
        $storages = $this->explodeOption($this->option('storages'));

        if (count($services) < 1) {
            $services = getBackupServiceProvider()->getServices();
        }

        if (count($storages) < 1) {
            $storages = getBackupServiceProvider()->getStorages();
        }

        $backup = new Backup();

        $this->comment('Starting backup...');

        if ($this->option('timeout') && is_numeric($this->option('timeout'))) {
            set_time_limit((int) $this->option('timeout'));
        }

        foreach ($services as $name => $service) {
            if (! $service instanceof Service) {
                $service = getBackupServiceProvider()->getService($service);

                if (is_null($service)) {
                    $this->error("Service \"$service\" is not exists.");

                    return 1;
                }
            }

            $backup->addBackupService($service);
        }

        foreach ($storages as $name => $storage) {
            if ($storage instanceof Filesystem) {
                $backup->addBackupStorage($storage, $name);
            } else {
                $backup->addBackupStorage(getBackupServiceProvider()->getStorage($storage), $storage);
            }
        }

        $backup
            ->setPassword(config('backup.password', '') ?? '')
            ->setOutput($this->getOutput())
            ->notifications($notifications);

        try {
            $backup->run();

            return 0;
        } catch (Exception $exception) {
            $this->error($exception);
            Log::emergency($exception);

            return 1;
        }
    }

    private function explodeOption(array $option): array
    {
        $values = [];

        foreach ($option as $item) {
            if (is_string($item)) {
                $values[] = str($item)->explode(',')->toArray();
            } else {
                $values[] = $item;
            }
        }

        return collect($values)->collapse()->toArray();
    }
}
