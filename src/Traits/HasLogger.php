<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;

trait HasLogger
{
    private OutputStyle|null $output = null;

    private function logMessage(string $method, string $message): static
    {
        if ($method === 'debug') {
            Log::debug($message);

            if ($this->getOutput()?->isVerbose()) {
                $this->getOutput()->writeln($message);
            }
        } elseif ($method === 'info') {
            Log::info($message);
            $this->getOutput()?->info($message);
        } elseif ($method === 'success') {
            Log::info($message);
            $this->getOutput()?->success($message);
        } elseif ($method === 'error') {
            Log::error($message);
            $this->getOutput()?->error($message);
        }

        return $this;
    }

    /**
     * Get the output.
     *
     * @return OutputStyle|null
     */
    public function getOutput(): OutputStyle|null
    {
        return $this->output;
    }

    /**
     * Set the output.
     *
     * @param  OutputStyle  $output
     * @return $this
     */
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
