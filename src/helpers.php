<?php

use Carbon\Carbon;
use IsaEken\LaravelBackup\BackupServiceProvider;
use IsaEken\LaravelBackup\Filename;

if (! function_exists('humanReadableFileSize')) {
    function humanReadableFileSize(float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes < 1) {
            return '0 '.$units[1];
        }

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

if (! function_exists('ageInDays')) {
    function ageInDays(Carbon $date): string
    {
        return number_format(round($date->diffInMinutes() / (24 * 60), 2), 2).' ('.$date->diffForHumans().')';
    }
}

if (! function_exists('isLargeFileSize')) {
    function isLargeFileSize(float $bytes, int $gigabytes = 4): bool
    {
        if ($bytes < 1024) {
            return false;
        }

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        if ($i == 3 && $bytes >= $gigabytes) {
            return true;
        }

        return $i > 3;
    }
}

if (! function_exists('getBackupServiceProvider')) {
    function getBackupServiceProvider(): BackupServiceProvider
    {
        return collect(app()->getProviders(BackupServiceProvider::class))->first();
    }
}

if (! function_exists('replacePathSeparators')) {
    function replacePathSeparators(string $path, array $separators = ['\\', '/']): string
    {
        return str($path)
            ->replace($separators, Filename::directorySeparator())
            ->replace($separators, Filename::directorySeparator())
            ->trim(Filename::directorySeparator())
            ->pipe(function ($value) {
                return str($value)
                    ->explode(Filename::directorySeparator())
                    ->filter(function ($value) {
                        $value = str($value);

                        return $value->trim()->isNotEmpty() && ! $value->is('.');
                    })
                    ->implode(Filename::directorySeparator());
            })
            ->pipe(function ($value) use ($path, $separators) {
                $value = str($value);

                if (str($path)->startsWith($separators)) {
                    return $value->prepend(Filename::directorySeparator());
                }

                return $value;
            })
            ->value();
    }
}

if (! function_exists('convertToZipPath')) {
    function convertToZipPath(string $path, string|null $basePath = null): string
    {
        $path = str(replacePathSeparators($path));

        if ($basePath !== null) {
            $path = $path->pipe(function ($value) use ($basePath) {
                $value = str($value);
                $basePath = str($basePath)->lower();

                if ($value->lower()->startsWith($basePath)) {
                    return $value->substr($basePath->length());
                }

                return $value;
            });
        }

        return str(replacePathSeparators($path))
            ->trim(Filename::directorySeparator())
            ->replace(['\\', '/'], '/')
            ->value();
    }
}
