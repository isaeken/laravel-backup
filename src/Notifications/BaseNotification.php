<?php

namespace IsaEken\LaravelBackup\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use IsaEken\LaravelBackup\DataTransferObjects\Backup;

class BaseNotification extends Notification
{
    public function __construct(public Backup $backup)
    {
        // ...
    }

    public function via(): array
    {
        return array_filter(config('backup.notifications.notifications.'.static::class, []));
    }

    public function applicationName(): string
    {
        $name = config('app.name') ?? config('app.url') ?? 'Laravel';
        $env = app()->environment();
        $version = app()->version();

        return "$name ($env - $version)";
    }

    public function properties(): Collection
    {
        return collect([
            'service' => '-',
            'filename' => $this->backup->getFilename(),
            'driver' => $this->backup->getDriver(),
            'size' => humanReadableFileSize($this->backup->getStorage()->size($this->backup->getFilename())),
            'date' => $this->backup->getDate(),
        ]);
    }
}
