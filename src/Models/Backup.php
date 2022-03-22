<?php

namespace IsaEken\LaravelBackup\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

/**
 * @property string $id
 * @property string $filename
 * @property string $disk
 * @property Carbon $date
 * @property int $size
 */
class Backup implements \IsaEken\LaravelBackup\Contracts\Backup\Backup
{
    private static array|null $cache = null;

    public array $attributes = [
        'id' => null,
        'filename' => null,
        'disk' => null,
        'created_at' => null,
        'size' => null,
    ];

    public array $casts = [
        'id' => 'int',
        'filename' => 'string',
        'disk' => 'string',
        'created_at' => 'datetime',
        'size' => 'int',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function getAttributes(array $columns = ['*']): array
    {
        $attributes = [];

        if ($columns == ['*']) {
            $columns = array_keys($this->attributes);
        }

        foreach ($columns as $column) {
            $attributes[$column] = $this->getAttribute($column);
        }

        return $attributes;
    }

    public function hasAttribute(string $attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

    public function getAttribute(string $attribute, mixed $default = null): mixed
    {
        if (! array_key_exists($attribute, $this->attributes) || $this->attributes[$attribute] === null) {
            return $default;
        }

        if (array_key_exists($attribute, $this->casts)) {
            switch ($this->casts[$attribute]) {
                case 'int':
                case 'integer':
                    return (int) $this->attributes[$attribute];

                case 'datetime':
                    return Carbon::make($this->attributes[$attribute]);
            }
        }

        return $this->attributes[$attribute];
    }

    public function setAttribute(string $attribute, mixed $value): self
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritDoc
     */
    public function setId(int $id): self
    {
        return $this->setAttribute('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function getFilename(): string
    {
        return $this->getAttribute('filename');
    }

    /**
     * @inheritDoc
     */
    public function setFilename(string $filename): self
    {
        return $this->setAttribute('filename', $filename);
    }

    /**
     * @inheritDoc
     */
    public function getDisk(): string
    {
        return $this->getAttribute('disk');
    }

    /**
     * @inheritDoc
     */
    public function setDisk(string $disk): self
    {
        return $this->setAttribute('disk', $disk);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): Carbon
    {
        return $this->getAttribute('created_at');
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(Carbon $date): self
    {
        return $this->setAttribute('created_at', $date);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int
    {
        return $this->getAttribute('size');
    }

    /**
     * @inheritDoc
     */
    public function setSize(int $size): self
    {
        return $this->setAttribute('size', $size);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->getAttributes();
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function offsetExists(mixed $offset): bool
    {
        return $this->hasAttribute($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getAttribute($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->setAttribute($offset, null);
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): bool|string
    {
        return json_encode($this->getAttributes(), $options);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function getFilePath(): string
    {
        return config('backup.database.path', storage_path('backups.json'));
    }

    public static function all(): Collection
    {
        if (! is_null(static::$cache)) {
            return collect(static::$cache);
        }

        static::$cache = [];

        $backups = @json_decode(@file_get_contents(static::getFilePath()));
        foreach ($backups ?? [] as $backup) {
            $backup = new static((array) $backup);
            static::$cache[] = $backup;
        }

        return static::all();
    }

    public static function create(array $attributes): static
    {
        if (! array_key_exists('id', $attributes)) {
            $attributes['id'] = time().rand(0, 999999);
        }

        if (! array_key_exists('created_at', $attributes)) {
            $attributes['created_at'] = now();
        }

        static::$cache[] = $model = new static($attributes);
        file_put_contents(
            static::getFilePath(),
            static::all()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );

        return $model;
    }
}
