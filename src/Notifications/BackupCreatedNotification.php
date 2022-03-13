<?php

namespace IsaEken\LaravelBackup\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use JetBrains\PhpStorm\ArrayShape;

class BackupCreatedNotification extends BaseNotification
{
    #[ArrayShape(['driver' => "string", 'filename' => "string", 'date' => "\Carbon\Carbon", 'size' => "int"])]
    public function toArray(): array
    {
        return [
            'driver' => $this->backup->getDriver(),
            'filename' => $this->backup->getFilename(),
            'date' => $this->backup->getDate(),
            'size' => $this->backup->getStorage()->size($this->backup->getFilename()),
        ];
    }

    public function toMail(): MailMessage
    {
        $mail = (new MailMessage())
            ->from(
                config('backup.notifications.mail.from.address', config('mail.from.address')),
                config('backup.notifications.mail.from.name', config('mail.from.name')),
            )
            ->subject('New Backup Generated Successfully.')
            ->line('Backup created to disk: '.$this->backup->getDriver().' for application: '.config('app.name'));

        $this->properties()->map(function ($value, $name) use ($mail) {
            $mail->line("$name: $value");
        });

        return $mail;
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage())
            ->success()
            ->from(config('backup.notifications.slack.username'), config('backup.notifications.slack.icon'))
            ->to(config('backup.notifications.slack.channel'))
            ->content('Backup created to disk: '.$this->backup->getDriver().' for application: '.config('app.name'))
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields($this->properties()->toArray());
            });
    }
}
