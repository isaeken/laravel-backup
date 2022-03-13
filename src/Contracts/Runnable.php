<?php

namespace IsaEken\LaravelBackup\Contracts;

interface Runnable
{
    /**
     * Handle instance.
     *
     * @return bool|int|void
     */
    public function run();
}
