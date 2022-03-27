<?php

namespace IsaEken\LaravelBackup\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use IsaEken\LaravelBackup\Backup;

class BackupCommand extends Command
{
    protected $signature = 'backup:run {--services=*} {--storages=*} {--disable-notifications} {--timeout=}';

    protected $description = 'Run the backups.';

    public function handle(): int
    {
        $notifications = !$this->option('disable-notifications');
        $services = $this->explodeOption($this->option('services'));
        $storages = $this->explodeOption($this->option('storages'));

        $backup = new Backup();

        $this->comment('Starting backup...');

        if ($this->option('timeout') && is_numeric($this->option('timeout'))) {
            set_time_limit((int) $this->option('timeout'));
        }

        foreach ($services as $service) {
            $backup->addBackupService($service);
        }

        foreach ($storages as $storage) {
            $backup->addBackupStorage($storage);
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
