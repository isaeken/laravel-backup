<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;

trait HasOutput
{
    private OutputStyle|null $output = null;

    private function log(string $method, string $message)
    {
        Log::$method($message);

        if ($this->getOutput() === null) {
            return;
        }

        $this->getOutput()->$method($message);
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

    public function info(string $message): static
    {
        $this->log('info', $message);
        return $this;
    }

    public function success(string $message): static
    {
        $this->log('success', $message);
        return $this;
    }

    public function error(string $message): static
    {
        $this->log('error', $message);
        return $this;
    }
}
