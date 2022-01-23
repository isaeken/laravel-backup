<?php

namespace IsaEken\LaravelBackup\Storages;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\BackupStorage;

class FakeStorage implements BackupStorage
{
    private static array $filesystem = [];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'fake-storage';
    }

    /**
     * @inheritDoc
     */
    public function save(string $filepath, string $directory): bool
    {
        $path = '/' . Str::slug(config('backup.name'), '_') . '/' . Str::slug($directory, '_') . '/' . basename($filepath);
        if (File::exists($filepath)) {
            static::$filesystem[$path] = File::get($filepath);
        } else {
            static::$filesystem[$path] = '';
        }

        return true;
    }
}
