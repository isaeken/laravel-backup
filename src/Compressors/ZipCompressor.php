<?php

namespace IsaEken\LaravelBackup\Compressors;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\Compressor;
use IsaEken\LaravelBackup\Contracts\HasPassword;
use IsaEken\LaravelBackup\Exceptions\MissingExtensionException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ZipCompressor implements Compressor, HasPassword
{
    use \IsaEken\LaravelBackup\Traits\HasPassword;

    public const FILENAME_FORMAT = 'Y-m-d-H-i-s.\z\i\p';

    protected ZipArchive $zipArchive;

    private string $source = '';

    private string $destination = '';

    private function zippedPath(string $path): string
    {
        $source = Str::of($this->getSource().DIRECTORY_SEPARATOR);
        $source = $source->replace('\\', DIRECTORY_SEPARATOR);
        $source = $source->replace('/', DIRECTORY_SEPARATOR);

        return Str::of($path)->after($source)->ltrim('/')->ltrim('\\')->value();
    }

    public function __construct()
    {
        throw_unless(extension_loaded('zip'), MissingExtensionException::class, 'zip');
        $this->zipArchive = new ZipArchive();
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
    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
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
    public function setDestination(string $destination): static
    {
        $this->destination = $destination.DIRECTORY_SEPARATOR.now()->format(static::FILENAME_FORMAT);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        throw_unless(is_dir($this->getSource()), FileNotFoundException::class, $this->getSource());

        if (! $this->zipArchive->open($this->getDestination(), ZipArchive::CREATE)) {
            return false;
        }

        $source = realpath($this->getSource());

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                if (in_array(substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1), ['.', '..'])) {
                    continue;
                }

                $file = realpath($file);
                if (is_dir($file) === true) {
                    $this->zipArchive->addEmptyDir($this->zippedPath($file));
                } elseif (is_file($file) === true) {
                    $this->zipArchive->addFromString($this->zippedPath($file), file_get_contents($file));

                    if (mb_strlen($this->getPassword()) > 0) {
                        $this->zipArchive->setEncryptionName(
                            $this->zippedPath($file),
                            ZipArchive::EM_AES_256,
                            $this->getPassword(),
                        );
                    }
                }
            }
        } elseif (is_file($source) === true) {
            $this->zipArchive->addFromString(basename($source), file_get_contents($source));

            if (mb_strlen($this->getPassword()) > 0) {
                $this->zipArchive->setEncryptionName(
                    basename($source),
                    ZipArchive::EM_AES_256,
                    $this->getPassword(),
                );
            }
        }

        return $this->zipArchive->close();
    }
}
