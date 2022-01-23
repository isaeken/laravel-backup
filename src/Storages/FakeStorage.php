<?php

namespace IsaEken\LaravelBackup\Storages;

use IsaEken\LaravelBackup\Contracts\BackupStorage;

class FakeStorage implements BackupStorage
{
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
        return true;
    }
}
