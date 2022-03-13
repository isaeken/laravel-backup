<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Notifications\Notification;

interface HasNotifications
{
    /**
     * Enable notifications.
     *
     * @return $this
     */
    public function enableNotifications(): static;

    /**
     * Disable notifications.
     *
     * @return $this
     */
    public function disableNotifications(): static;

    /**
     * Check is notifications are enabled.
     *
     * @param  bool|null  $notifications
     * @return bool
     */
    public function notifications(bool|null $notifications = null): bool;

    /**
     * Send notification.
     *
     * @param  Notification  $notification
     * @return $this
     */
    public function notify(Notification $notification): static;
}
