<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use IsaEken\LaravelBackup\Traits\HasBackupStorages;

class Finder implements Contracts\HasBackupStorages, Contracts\Runnable
{
    use HasBackupStorages;

    /**
     * @inheritDoc
     */
    public function run(): Collection
    {
        $backups = collect();

        /** @var Filesystem $storage */
        foreach ($this->getBackupStorages() as $driver => $storage) {
            foreach ($storage->files('.') as $filename) {
                if (str_starts_with($filename, config('backup.prefix', 'backup_'))) {
                    $date = explode('_', explode('.', $filename)[0]);
                    $date = $date[count($date) - 1];
                    $date = Carbon::createFromFormat('Y-m-d-H-i-s', $date);
                    $backups->add(new DataTransferObjects\Backup($storage, $driver, $filename, $date));
                }
            }
        }

        return $backups
            ->sortBy(function (DataTransferObjects\Backup $backup) {
                return $backup->getStorage()->size($backup->getFilename());
            })
            ->sortBy(function (DataTransferObjects\Backup $backup) {
                return $backup->getDate();
            });
    }
}
