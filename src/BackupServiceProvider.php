<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\FilesystemManager;
use IsaEken\LaravelBackup\Commands\BackupCommand;
use IsaEken\LaravelBackup\Commands\ListCommand;
use IsaEken\LaravelBackup\Contracts\Backup\Service;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BackupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-backup')
            ->hasConfigFile()
//            ->hasTranslations()
            ->hasCommands([
                BackupCommand::class,
                ListCommand::class,
            ]);
    }

    public function packageBooted()
    {
        // ...
    }

    public function packageRegistered()
    {
        // ...
    }

    /**
     * @param  string  $name
     * @return Service|null
     */
    public function getService(string $name): Service|null
    {
        $services = config('backup.services', []) ?? [];

        /** @var Service $service */
        foreach ($services as $service) {
            if ($service instanceof $name) {
                return new $service();
            }

            if (($service = new $service())->getName() == $name) {
                return $service;
            }
        }

        return null;
    }

    /**
     * @return array<string, Service>
     */
    public function getServices(): array
    {
        $services = collect();
        $makeServiceCollection = function ($array) {
            return collect($array)->mapWithKeys(function ($service) {
                /** @var Service $service */
                $service = new $service();

                return [$service->getName() => $service];
            });
        };

        if (func_num_args() === 0 || (func_num_args() === 1 && func_get_arg(0) === '*')) {
            return $makeServiceCollection(config('backup.services', []) ?? [])->toArray();
        }

        foreach (func_get_args() as $service) {
            if (is_array($service) || $service instanceof Arrayable) {
                $services = $services->merge($makeServiceCollection($service));
            } else {
                $services = $services->merge([$makeServiceCollection($service)]);
            }
        }

        return $services->toArray();
    }

    /**
     * @param  string  $name
     * @return Filesystem
     */
    public function getStorage(string $name): Filesystem
    {
        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = $this->app->get('filesystem');

        return $filesystemManager->drive($name);
    }

    /**
     * @return array<string, Filesystem>
     */
    public function getStorages(): array
    {
        $storages = collect();
        $makeStorageCollection = function ($array) {
            return collect($array)->mapWithKeys(function ($storage) {
                return [$storage => $this->getStorage($storage)];
            });
        };

        if (func_num_args() === 0 || (func_num_args() === 1 && func_get_arg(0) === '*')) {
            return $makeStorageCollection(config('backup.storages', []) ?? [])->toArray();
        }

        foreach (func_get_args() as $driver) {
            if (is_array($driver) || $driver instanceof Arrayable) {
                $storages = $storages->merge($makeStorageCollection($driver));
            } else {
                $storages = $storages->merge([$makeStorageCollection($driver)]);
            }
        }

        return $storages->toArray();
    }
}
