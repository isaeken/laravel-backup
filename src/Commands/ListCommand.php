<?php

namespace IsaEken\LaravelBackup\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use IsaEken\LaravelBackup\Console\Table\Column;
use IsaEken\LaravelBackup\Console\Table\Table;
use IsaEken\LaravelBackup\Models\Backup;

class ListCommand extends Command
{
    protected $signature = 'backup:list {--disk=*} {--check=false}';

    protected $description = 'Display a list of all backups.';

    public function handle(): int
    {
        $disks = collect($this->option('disk'));

        /** @var \IsaEken\LaravelBackup\Contracts\Backup\Backup $model */
        $model = config('backup.database.model', Backup::class);
        $models = $model::all();
        $backups = collect();

        if ($disks->count() > 0) {
            foreach ($disks as $disk) {
                $backups = $backups->merge($models->where('disk', $disk));
            }
        } else {
            $backups = $models;
        }

        $this->displayOverview($backups);

        return 0;
    }

    protected function displayOverview(Collection $backups): static
    {
        if ($backups->count() < 1) {
            $this->alert('!!  '.trans('NO ANY BACKUPS EXISTS').'  !!');
            return $this;
        }

        $totalUsedStorage = 0;
        $table = new Table();

        $table->addColumn(new Column('id', '#', false));
        $table->addColumn(new Column('filename', trans('Filename'), false));
        $table->addColumn(new Column('disk', trans('Disk / Storage'), false));
        $table->addColumn(new Column('size', trans('Size'), true));
        $table->addColumn(new Column('created_at', trans('Created Date'), false));

        /** @var Backup $backup */
        foreach ($backups as $backup) {
            $table->addRow([
                'id' => $backup->getId(),
                'filename' => $backup->getFilename(),
                'disk' => $backup->getDisk(),
                'size' => $this->formatFileSizeColumn($backup->getSize()),
                'created_at' => $this->formatDateColumn($backup->getCreatedAt()),
            ]);

            $totalUsedStorage += $backup->getSize();
        }

        $table->render($this);
        $this->alert(trans('Total Used Storage: :size', [
            'size' => humanReadableFileSize($totalUsedStorage),
        ]));

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
}
