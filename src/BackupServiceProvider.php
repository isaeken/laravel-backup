<?php

namespace IsaEken\LaravelBackup;

use IsaEken\LaravelBackup\Commands\BackupCommand;
use IsaEken\LaravelBackup\Commands\ListCommand;
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
}
