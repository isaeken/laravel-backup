<?php

namespace IsaEken\LaravelBackup\Contracts\Backup;

interface Manager
{
    /**
     * Run the backup services.
     *
     * @return void
     */
    public function run(): void;
}
