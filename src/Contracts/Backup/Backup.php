<?php

namespace IsaEken\LaravelBackup\Contracts\Backup;

use ArrayAccess;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use JsonSerializable;

interface Backup extends Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    /**
     * Get the unique ID.
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Set unique ID.
     *
     * @param  int  $id
     * @return $this
     */
    public function setId(int $id): self;

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Set filename.
     *
     * @param  string  $filename
     * @return $this
     */
    public function setFilename(string $filename): self;

    /**
     * Get disk name.
     *
     * @return string
     */
    public function getDisk(): string;

    /**
     * Set disk name.
     *
     * @param  string  $disk
     * @return $this
     */
    public function setDisk(string $disk): self;

    /**
     * Get creation date.
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;

    /**
     * Set creation date.
     *
     * @param  Carbon  $date
     * @return $this
     */
    public function setCreatedAt(Carbon $date): self;

    /**
     * Get size in bytes.
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Set size in bytes.
     *
     * @param  int  $size
     * @return $this
     */
    public function setSize(int $size): self;

    /**
     * Get all records.
     *
     * @return Collection
     */
    public static function all(): Collection;

    /**
     * Create new record.
     *
     * @param  array  $attributes
     * @return self
     */
    public static function create(array $attributes): self;
}
