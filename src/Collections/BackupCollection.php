<?php

namespace IsaEken\LaravelBackup\Collections;

use Illuminate\Support\Collection;
use IsaEken\LaravelBackup\Contracts\Backup\Backup;

class BackupCollection extends Collection implements \IsaEken\LaravelBackup\Contracts\Backup\BackupCollection
{
    /**
     * @inheritDoc
     */
    public function sortByNewest(): static
    {
        return $this->sortBy(function ($a, $b) {
            /** @var Backup $a */
            /** @var Backup $b */
            return $a->getDate()->timestamp < $b->getDate()->timestamp;
        });
    }

    /**
     * @inheritDoc
     */
    public function sortByNewestDesc(): static
    {
        return $this->sortByNewest()->reverse();
    }

    /**
     * @inheritDoc
     */
    public function sortByOldest(): static
    {
        return $this->sortByNewestDesc();
    }

    /**
     * @inheritDoc
     */
    public function sortByOldestDesc(): static
    {
        return $this->sortByNewest();
    }

    /**
     * @inheritDoc
     */
    public function sortBySize(): static
    {
        return $this->sortBy(function ($a, $b) {
            /** @var Backup $a */
            /** @var Backup $b */
            return $a->getSize() < $b->getSize();
        });
    }

    /**
     * @inheritDoc
     */
    public function sortBySizeDesc(): static
    {
        return $this->sortBySize()->reverse();
    }
}
