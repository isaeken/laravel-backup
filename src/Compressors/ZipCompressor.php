<?php

namespace IsaEken\LaravelBackup\Compressors;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\Compressor;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

class ZipCompressor implements Compressor
{
    public const FILENAME_FORMAT = 'Y-m-d-H-i-s.\z\i\p';

    protected ZipArchive $zipArchive;

    protected string $source;

    protected string $destination;

    public function __construct()
    {
        if (!extension_loaded('zip')) {
            throw new RuntimeException('The compressor is cannot be continue because zip extension is not loaded in your environment.');
        }

        $this->zipArchive = new ZipArchive;
    }

    /**
     * @inheritDoc
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @inheritDoc
     */
    public function setSource(string $source): static
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDestination(string $destination): static
    {
        $this->destination = $destination . DIRECTORY_SEPARATOR . now()->format(static::FILENAME_FORMAT);
        return $this;
    }

    private function zippedPath(string $path): string
    {
        return Str::of($path)->after($this->getSource() . DIRECTORY_SEPARATOR);
    }

    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        throw_unless(is_dir($this->source), FileNotFoundException::class, $this->source);

        if (!$this->zipArchive->open($this->destination, ZipArchive::CREATE)) {
            return false;
        }

        $source = realpath($this->source);
        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                if (in_array(substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1), ['.', '..'])) {
                    continue;
                }

                $file = realpath($file);
                if (is_dir($file) === true) {
                    $this->zipArchive->addEmptyDir($this->zippedPath($file));
                } elseif (is_file($file) === true) {
                    $this->zipArchive->addFromString($this->zippedPath($file), file_get_contents($file));
                }
            }
        } elseif (is_file($source) === true) {
            $this->zipArchive->addFromString(basename($source), file_get_contents($source));
        }

        return $this->zipArchive->close();
    }
}
