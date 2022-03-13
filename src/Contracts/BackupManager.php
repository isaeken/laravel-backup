<?php

namespace IsaEken\LaravelBackup\Contracts;

interface BackupManager
{
    /**
     * Run the backup services.
     *
     * @return void
     */
    public function run(): void;
}
