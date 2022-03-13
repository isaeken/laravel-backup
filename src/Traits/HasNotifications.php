<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Notifications\Notification;

trait HasNotifications
{
    private bool $notifications = true;

    /**
     * Enable notifications.
     *
     * @return $this
     */
    public function enableNotifications(): static
    {
        $this->notifications = true;

        return $this;
    }

    /**
     * Disable notifications.
     *
     * @return $this
     */
    public function disableNotifications(): static
    {
        $this->notifications = false;

        return $this;
    }

    /**
     * Check is notifications are enabled.
     *
     * @param  bool|null  $notifications
     * @return bool
     */
    public function notifications(bool|null $notifications = null): bool
    {
        if ($notifications !== null) {
            $this->notifications = $notifications;
        }

        return $this->notifications;
    }

    /**
     * Send notification.
     *
     * @param  Notification  $notification
     * @return $this
     */
    public function notify(Notification $notification): static
    {
        rescue(
            fn () => event($notification),
            function () {
                if ($this instanceof \IsaEken\LaravelBackup\Contracts\HasLogger) {
                    $this->error('Sending notification failed.');
                }
            }
        );

        return $this;
    }
}
