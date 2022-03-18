<?php

namespace IsaEken\LaravelBackup\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Filesystem $filesystem
 * @property string $driver
 * @property string $filename
 * @property int $size
 * @property Carbon $date
 */
class Backup extends Model
{
    protected $fillable = [
        'driver',
        'filename',
        'size',
        'date',
    ];

    protected $casts = [
        'driver' => 'string',
        'filename' => 'string',
        'size' => 'int',
        'date' => 'datetime',
    ];

    public function setFilesystem(Filesystem $filesystem): static
    {
        $this->setAttribute('filesystem', $filesystem);

        return $this;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->getAttribute('filesystem');
    }

    public function setDriver(string $driver): static
    {
        $this->setAttribute('driver', $driver);

        return $this;
    }

    public function getDriver(): string
    {
        return $this->getAttribute('driver');
    }

    public function setFilename(string $filename): static
    {
        $this->setAttribute('filename', $filename);

        return $this;
    }

    public function getFilename(): string
    {
        return $this->getAttribute('filename');
    }

    public function setSize(int $size): static
    {
        $this->setAttribute('size', $size);

        return $this;
    }

    public function getSize(): int
    {
        return $this->getAttribute('size');
    }

    public function setDate(Carbon $date): static
    {
        $this->setAttribute('date', $date);

        return $this;
    }

    public function getDate(): Carbon
    {
        return $this->getAttribute('date');
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        $this->mergeAttributesFromCachedCasts();
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // @todo save

        $this->finishSave($options);

        return true;
    }
}
