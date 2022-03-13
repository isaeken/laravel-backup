<?php

namespace IsaEken\LaravelBackup\Services;

use Illuminate\Support\Facades\File;
use IsaEken\LaravelBackup\Collectors\DirectoryCollector;
use IsaEken\LaravelBackup\Compressors\ZipCompressor;
use IsaEken\LaravelBackup\Contracts;
use IsaEken\LaravelBackup\Traits;

class StorageService extends Service implements Contracts\Backup\Service, Contracts\HasLogger, Contracts\HasCompressor, Contracts\HasPassword, Contracts\UsesTemporaryDirectory
{
    use Traits\HasLogger;
    use Traits\HasCompressor;
    use Traits\HasPassword;
    use Traits\UsesTemporaryDirectory;

    protected string $name = 'storage';

    public function __construct()
    {
        $this->setCompressor(new ZipCompressor());
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        if ($this->getCompressor() instanceof Contracts\HasPassword) {
            $this->getCompressor()->setPassword($this->getPassword());
        }

        $this->makeTemporaryDirectory('storage');

        $this->info('Collecting files...');

        $collection = new DirectoryCollector(storage_path());
        $collection->run();

        $this->debug('Making directories...');

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (is_array($value)) {
                @File::makeDirectory($this->getTemporaryDirectory('storage')->path($path), recursive: true);
            }
        }

        $this->debug('Copying files...');

        /**
         * @var string $path
         * @var string|array $value
         */
        foreach ($collection->collect() as $path => $value) {
            if (!is_array($value)) {
                @File::copy(storage_path($path), $this->getTemporaryDirectory('storage')->path($path));
            }
        }

        $this->debug('Compressing...');

        $this
            ->getCompressor()
            ->setSource($this->getTemporaryDirectory('storage')->path())
            ->setDestination($this->getTemporaryDirectory('storage')->path());

        if ($this->getCompressor()->run()) {
            $this
                ->setOutputFile($this->getCompressor()->getDestination())
                ->setSuccessStatus(true);

            $this->success('Backup generated: '.$this->getOutputFile());
        } else {
            $this->error('Compression failed!');
        }
    }
}
