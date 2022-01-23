<?php

namespace IsaEken\LaravelBackup\BackupServices;

use Illuminate\Support\Facades\File;
use IsaEken\LaravelBackup\BackupServices\BackupService as BaseBackupService;
use IsaEken\LaravelBackup\Collectors\DirectoryCollector;
use IsaEken\LaravelBackup\Contracts\BackupService;
use IsaEken\LaravelBackup\Exceptions\CompressorNotProvidedException;

class StorageBackupService extends BaseBackupService implements BackupService
{
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
        $this->info('Collecting files...', true);

        $collection = new DirectoryCollector($this->source_path());
        $collection->run();

        $this->info('Making directories...', true);

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (is_array($value)) {
                @File::makeDirectory($this->temporary_path($path), recursive: true);
            }
        }

        $this->info('Copying files...', true);

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (!is_array($value)) {
                File::copy($this->source_path($path), $this->temporary_path($path));
            }
        }

        $this->info('Compressing...', true);
        throw_if($this->getCompressor() === null, CompressorNotProvidedException::class);

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
