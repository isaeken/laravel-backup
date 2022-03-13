<?php

namespace IsaEken\LaravelBackup\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use IsaEken\LaravelBackup\ConfigReader;
use IsaEken\LaravelBackup\DataTransferObjects\Backup;
use IsaEken\LaravelBackup\Finder;
use Symfony\Component\Console\Helper\TableStyle;

class ListCommand extends Command
{
    protected $signature = 'backup:list {--storages=*}';

    protected $description = 'Display a list of all backups.';

    public function handle(): int
    {
        $finder = new Finder();
        $storages = ConfigReader::getStorages($this->explodeOption('storages'));

        /** @var Filesystem $storage */
        foreach ($storages as $driver => $storage) {
            $finder->addBackupStorage($storage, $driver);
        }

        $this->displayOverview($finder->run());

        return 0;
    }

    protected function displayOverview(Collection $backups): static
    {
        $rightAlignedCell = new class () extends TableStyle {
            public function __construct()
            {
                $this->setPadType(STR_PAD_LEFT);
            }
        };

        $usedStorage = 0;
        $headers = ['#', 'Name', 'Disk', 'Date', 'Size'];
        $rows = [];

        /** @var Backup $backup */
        foreach ($backups as $index => $backup) {
            $fileSize = $backup->getStorage()->size($backup->getFilename());
            $usedStorage += $fileSize;
            $rows[] = [
                $index + 1,
                $backup->getFilename(),
                $backup->getDriver(),
                $this->formatDateColumn($backup->getDate()),
                $this->formatFileSizeColumn($fileSize),
            ];
        }

        $this->table($headers, $rows, 'default', [
            3 => $rightAlignedCell,
            4 => $rightAlignedCell,
        ]);

        $this->alert('Totally Used Storage: '.humanReadableFileSize($usedStorage));

        return $this;
    }

    private function formatFileSizeColumn($bytes): string
    {
        $size = humanReadableFileSize($bytes);

        return isLargeFileSize($bytes) ? "<error>$size</error>" : $size;
    }

    private function formatDateColumn(Carbon $date): string
    {
        $diff = now()->diff($date);
        if ($diff->invert && $diff->days > 7) {
            return "<error>$date</error>";
        }

        return $date;
    }

    private function explodeOption(string $key): array|string
    {
        $option = $this->option($key) ?? '*';
        $option = (is_array($option) && count($option) < 1) || (is_string($option) && strlen($option) < 1) ? '*' : $option;

        return $option === '*' ? $option : explode(',', $option);
    }
}
