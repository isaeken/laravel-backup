<?php

namespace IsaEken\LaravelBackup\Traits;

use Exception;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;

trait HasLogger
{
    private OutputStyle|null $output = null;

    private function logMessage(string $method, string $message): static
    {
        try {
            Log::$method($message);
        } catch (Exception $exception) {
            // ...
        }

        $this->getOutput()?->$method($message);

        return $this;
    }

    public function getOutput(): OutputStyle|null
    {
        return $this->output;
    }

    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Log a debug message.
     *
     * @param  string  $message
     * @return $this
     */
    public function debug(string $message): static
    {
        return $this->logMessage('debug', $message);
    }

    /**
     * Log a info message.
     *
     * @param  string  $message
     * @return $this
     */
    public function info(string $message): static
    {
        return $this->logMessage('info', $message);
    }

    /**
     * Log a success message.
     *
     * @param  string  $message
     * @return $this
     */
    public function success(string $message): static
    {
        return $this->logMessage('success', $message);
    }

    /**
     * Log a error message.
     *
     * @param  string  $message
     * @return $this
     */
    public function error(string $message): static
    {
        return $this->logMessage('error', $message);
    }
}
