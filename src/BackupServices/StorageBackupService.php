<?php

namespace IsaEken\LaravelBackup\BackupServices;

use Illuminate\Support\Facades\File;
use IsaEken\LaravelBackup\Collectors\DirectoryCollector;

class StorageBackupService extends BackupService
{
    public const FILENAME_FORMAT = 'Y-m-d-H-i-s.\z\i\p';

    protected string $name = 'storage';

    public string|null $storage_path = null;

    private function source_path(string|null $path = null): false|string
    {
        if ($path !== null) {
            return realpath($this->storage_path . DIRECTORY_SEPARATOR . trim($path));
        }

        return $this->storage_path;
    }

    private function temporary_path(string|null $path = null): string
    {
        if ($path !== null) {
            return $this->temporaryDirectory->path('backup') . DIRECTORY_SEPARATOR . trim($path);
        }

        return $this->temporaryDirectory->path('backup');
    }

    public function __construct($container)
    {
        parent::__construct($container);
        $this->storage_path = storage_path();
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        if ($this->getOutput()?->isVerbose()) {
            $this->info('Collecting files...');
        }

        $collection = new DirectoryCollector($this->source_path());
        $collection->run();

        if ($this->getOutput()?->isVerbose()) {
            $this->info('Making directories...');
        }

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (is_array($value)) {
                @File::makeDirectory($this->temporary_path($path), recursive: true);
            }
        }

        if ($this->getOutput()?->isVerbose()) {
            $this->info('Copying files...');
        }

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (!is_array($value)) {
                File::copy($this->source_path($path), $this->temporary_path($path));
            }
        }

        if ($this->getOutput()?->isVerbose()) {
            $this->info('Compressing...');
        }

        throw_if($this->getCompressor() === null, 'RuntimeException', 'This backup is required compressor!');

        $this
            ->getCompressor()
            ->setSource($this->temporary_path())
            ->setDestination($this->temporaryDirectory->path());

        if ($this->getCompressor()->run()) {
            $this->outputFile = $this->getCompressor()->getDestination();
            $this->success = true;
            $this->success('Backup generated: ' . $this->outputFile);
        } else {
            $this->error('Compression failed!');
        }
    }
}
