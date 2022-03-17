<?php

namespace IsaEken\LaravelBackup\Contracts\Backup;

use Illuminate\Support\Enumerable;

interface BackupCollection extends Enumerable
{
    /**
     * Sort collection as newest.
     *
     * @return BackupCollection
     */
    public function sortByNewest(): static;

    /**
     * Sort collection as newest desc.
     *
     * @return BackupCollection
     */
    public function sortByNewestDesc(): static;

    /**
     * Sort collection as oldest.
     *
     * @return BackupCollection
     */
    public function sortByOldest(): static;

    /**
     * Sort collection as oldest desc.
     *
     * @return BackupCollection
     */
    public function sortByOldestDesc(): static;

    /**
     * Sort collection as size.
     *
     * @return BackupCollection
     */
    public function sortBySize(): static;

    /**
     * Sort collection as size desc.
     *
     * @return BackupCollection
     */
    public function sortBySizeDesc(): static;
}
