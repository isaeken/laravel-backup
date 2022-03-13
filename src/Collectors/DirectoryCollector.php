<?php

namespace IsaEken\LaravelBackup\Collectors;

use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Collectors\Collector as BaseCollector;
use IsaEken\LaravelBackup\Contracts\Collector;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DirectoryCollector extends BaseCollector implements Collector
{
    /**
     * @param  string  $directory
     * @param  bool  $ignoreLibraryRoots
     * @param  bool  $ignoreDotFiles
     * @param  bool  $followSymlinks
     * @param  bool  $ignoreVcs
     */
    public function __construct(
        public string $directory,
        public bool $ignoreLibraryRoots = true,
        public bool $ignoreDotFiles = false,
        public bool $followSymlinks = false,
        public bool $ignoreVcs = false,
    ) {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function run(): static
    {
        $finder = new Finder();
        $finder
            ->in($this->directory)
            ->ignoreDotFiles($this->ignoreDotFiles)
            ->ignoreVCSIgnored($this->ignoreVcs);

        if ($this->ignoreLibraryRoots) {
            $finder->exclude(config('backup.library_roots', [
                'node_modules',
                'vendor',
            ]));
        }

        $iterator = iterator_to_array($finder);
        $items = [];

        /**
         * @var string $path
         * @var SplFileInfo $item
         */
        foreach ($iterator as $path => $item) {
            $key = Str::of($path)->after($this->directory);
            if ($key->startsWith(['\\', '/'])) {
                $key = $key->substr(1);
            }

            if ($item->isFile()) {
                $items[$key->__toString()] = basename($path);
            } elseif ($item->isDir()) {
                $items[$key->__toString()] = [];
            }
        }

        return $this->setItems($items);
    }
}
