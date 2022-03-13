<?php

namespace IsaEken\LaravelBackup\Contracts\Backup;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Stringable;

interface Backup extends Arrayable, Stringable, Jsonable
{
    /**
     * Get the storage.
     *
     * @return Filesystem
     */
    public function getStorage(): Filesystem;

    /**
     * Set the storage.
     *
     * @param  Filesystem  $storage
     * @return $this
     */
    public function setStorage(Filesystem $storage): static;

    /**
     * Get the driver.
     *
     * @return string
     */
    public function getDriver(): string;

    /**
     * Set the driver.
     *
     * @param  string  $driver
     * @return $this
     */
    public function setDriver(string $driver): static;

    /**
     * Get the filename.
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Set the filename.
     *
     * @param  string  $filename
     * @return $this
     */
    public function setFilename(string $filename): static;

    /**
     * Get the date.
     *
     * @return Carbon
     */
    public function getDate(): Carbon;

    /**
     * Set the date.
     *
     * @param  Carbon  $date
     * @return $this
     */
    public function setDate(Carbon $date): static;
}
