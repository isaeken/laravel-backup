<?php

namespace IsaEken\LaravelBackup;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BackupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-backup')
            ->hasConfigFile();
//            ->hasTranslations()
//            ->hasCommands([
//
//            ]);
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
