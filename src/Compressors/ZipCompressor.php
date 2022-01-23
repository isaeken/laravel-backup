<?php

namespace IsaEken\LaravelBackup\Compressors;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Compressors\Compressor as BaseCompressor;
use IsaEken\LaravelBackup\Contracts\Compressor;
use IsaEken\LaravelBackup\Exceptions\MissingExtensionException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ZipCompressor extends BaseCompressor implements Compressor
{
    public const FILENAME_FORMAT = 'Y-m-d-H-i-s.\z\i\p';

    protected ZipArchive $zipArchive;

    private function zippedPath(string $path): string
    {
        return Str::of($path)->after($this->getSource() . DIRECTORY_SEPARATOR);
    }

    public function __construct()
    {
        throw_unless(extension_loaded('zip'), MissingExtensionException::class, 'zip');
        $this->zipArchive = new ZipArchive;
    }

    /**
     * @inheritDoc
     */
    public function setDestination(string $destination): static
    {
        $this->destination = $destination . DIRECTORY_SEPARATOR . now()->format(static::FILENAME_FORMAT);
        return $this;
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
