<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use IsaEken\LaravelBackup\Contracts\BackupService;

class ConfigReader
{
    public static function findService(string $name): BackupService|null
    {
        /** @var BackupService $service */
        foreach (config('backup.services', []) as $service) {
            if ($service instanceof $name || (new $service())->getName()) {
                return new $service();
            }
        }

        return null;
    }

    /**
     * @return array<BackupService>
     */
    public static function getServices(): array
    {
        $services = [];

        if (func_num_args() === 0 || (func_num_args() === 1 && func_get_args()[0] === '*')) {
            /** @var BackupService $service */
            foreach (config('backup.services', []) as $service) {
                $services[] = new $service();
            }

            return $services;
        }

        foreach (func_get_args() as $service) {
            if (is_array($service)) {
                foreach ($service as $item) {
                    $services[] = static::findService($item);
                }
            } else {
                $services[] = static::findService($service);
            }
        }

        return collect($services)->filter()->values()->toArray();
    }

    /**
     * @return array<Filesystem>
     */
    public static function getStorages(): array
    {
        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = app('filesystem');
        $storages = [];

        if (func_num_args() === 0 || (func_num_args() === 1 && func_get_args()[0] === '*')) {
            foreach (config('backup.storages', []) as $storage) {
                $storages[] = $filesystemManager->drive($storage);
            }

            return $storages;
        }

        foreach (func_get_args() as $driver) {
            if (is_array($driver)) {
                foreach ($driver as $item) {
                    $storages[] = $filesystemManager->drive($item);
                }
            } else {
                $storages[] = $filesystemManager->drive($driver);
            }
        }

        return $storages;
    }
}
