<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasLogger
{
    /**
     * Log a debug message.
     *
     * @param  string  $message
     * @return $this
     */
    public function debug(string $message): static;

    /**
     * Log a info message.
     *
     * @param  string  $message
     * @return $this
     */
    public function info(string $message): static;

    /**
     * Log a success message.
     *
     * @param  string  $message
     * @return $this
     */
    public function success(string $message): static;

    /**
     * Log a error message.
     *
     * @param  string  $message
     * @return $this
     */
    public function error(string $message): static;
}
