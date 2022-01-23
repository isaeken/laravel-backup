<?php

namespace IsaEken\LaravelBackup\Abstracts;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;
use IsaEken\LaravelBackup\Contracts\HasOutput;

abstract class HasOutputWithLogger implements HasOutput
{
    private OutputStyle|null $output = null;

    private function logMessage(array|string $message): array|string
    {
        if (is_array($message)) {
            $temp = [];
            foreach ($message as $line) {
                $temp[] = now()->toDateTimeString() . ': ' . $line;
            }
            $message = $temp;
        } else {
            $message = now()->toDateTimeString() . ': ' . $message;
        }

        return $message;
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): OutputStyle|null
    {
        return $this->output;
    }

    /**
     * @inheritDoc
     */
    public function setOutput(OutputStyle $output): static
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function title(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->title($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function comment(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->comment($message);
        Log::info($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function success(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->success($message);
        Log::info($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function error(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->error($message);
        Log::error($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function warning(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->warning($message);
        Log::warning($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function note(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->note($message);
        Log::notice($message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function info(array|string $message): static
    {
        $message = $this->logMessage($message);
        $this->output?->info($message);
        Log::info($message);
        return $this;
    }
}
