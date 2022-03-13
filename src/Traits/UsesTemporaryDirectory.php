<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Spatie\TemporaryDirectory\TemporaryDirectory;

trait UsesTemporaryDirectory
{
    private array $temporaryDirectories = [];

    /**
     * Make temporary directory.
     *
     * @param  string|null  $key
     * @return string
     */
    public function makeTemporaryDirectory(string|null $key = null): string
    {
        $directory = (new TemporaryDirectory())
            ->name(Str::slug('laravel-backup-'.config('app.name').'-'.rand(0, 10000).time().rand(0, 10000), '_'))
            ->force()
            ->create()
            ->empty();

        $this->temporaryDirectories[$key] = $directory;

        return $directory->path();
    }

    /**
     * Get temporary directory instance.
     *
     * @param  string  $key
     * @return TemporaryDirectory|null
     */
    #[Pure]
    public function getTemporaryDirectory(string $key): TemporaryDirectory|null
    {
        return $this->getTemporaryDirectories()[$key] ?? null;
    }

    /**
     * Get all temporary directories in this instance.
     *
     * @return array<TemporaryDirectory>
     */
    public function getTemporaryDirectories(): array
    {
        return $this->temporaryDirectories;
    }

    /**
     * Destroy all temporary directories in this instance.
     *
     * @return $this
     */
    public function flushTemporaryDirectories(): static
    {
        foreach ($this->getTemporaryDirectories() as $temporaryDirectory) {
            $temporaryDirectory
                ->empty()
                ->delete();
        }

        return $this;
    }
}
