<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

interface Collector extends Arrayable
{
    /**
     * Collect the items.
     *
     * @return $this
     */
    public function run(): static;

    /**
     * Get the instance as collection.
     *
     * @return Collection
     */
    public function collect(): Collection;
}
