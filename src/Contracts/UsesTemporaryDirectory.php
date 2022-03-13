<?php

namespace IsaEken\LaravelBackup\Contracts;

use Spatie\TemporaryDirectory\TemporaryDirectory;

interface UsesTemporaryDirectory
{
    /**
     * Make temporary directory.
     *
     * @param  string|null  $key
     * @return string
     */
    public function makeTemporaryDirectory(string|null $key = null): string;

    /**
     * Get temporary directory instance.
     *
     * @param  string  $key
     * @return TemporaryDirectory|null
     */
    public function getTemporaryDirectory(string $key): TemporaryDirectory|null;

    /**
     * Get all temporary directories in this instance.
     *
     * @return array
     */
    public function getTemporaryDirectories(): array;

    /**
     * Destroy all temporary directories in this instance.
     *
     * @return $this
     */
    public function flushTemporaryDirectories(): static;
}
