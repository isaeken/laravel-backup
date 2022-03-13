<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Console\OutputStyle;

interface HasLogger
{
    /**
     * Get the output.
     *
     * @return OutputStyle|null
     */
    public function getOutput(): OutputStyle|null;

    /**
     * Set the output.
     *
     * @param  OutputStyle  $output
     * @return $this
     */
    public function setOutput(OutputStyle $output): static;

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
