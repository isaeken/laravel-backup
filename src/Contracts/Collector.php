<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

interface Collector extends Arrayable, Runnable
{
    /**
     * Get the instance as collection.
     *
     * @return Collection
     */
    public function collect(): Collection;
}
