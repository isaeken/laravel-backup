<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;

trait HasOutput
{
    private OutputStyle|null $output = null;

    private function formatToLogMessage(string $message): string
    {
        $now = now()->toDateTimeString();
        return "[$now] $message";
    }

    private function canWriteToOutput(bool $verbose = false): bool
    {
        if ($this->getOutput() === null) {
            return false;
        }

        if ($verbose === false) {
            return true;
        }

        return $this->getOutput()->isVerbose();
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

    public function info(string $message, bool $verbose = false): static
    {
        $message = $this->formatToLogMessage($message);
        Log::info($message);

        if ($this->canWriteToOutput($verbose)) {
            $this->getOutput()->info($message);
        }

        return $this;
    }

    public function success(string $message): static
    {
        $message = $this->formatToLogMessage($message);
        Log::info($message);

        if ($this->canWriteToOutput()) {
            $this->getOutput()->info($message);
        }

        return $this;
    }

    public function error(string $message): static
    {
        $message = $this->formatToLogMessage($message);
        Log::error($message);

        if ($this->canWriteToOutput()) {
            $this->getOutput()->error($message);
        }

        return $this;
    }
}
