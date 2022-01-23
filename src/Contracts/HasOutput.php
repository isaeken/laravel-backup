<?php

namespace IsaEken\LaravelBackup\Contracts;

use Illuminate\Console\OutputStyle;

interface HasOutput
{
    /**
     * Get the output.
     *
     * @return OutputStyle|null
     */
    public function getOutput(): OutputStyle|null;

    /**
     * Set the output
     *
     * @param OutputStyle $output
     * @return $this
     */
    public function setOutput(OutputStyle $output): static;

    /**
     * Formats a command title.
     *
     * @param array|string $message
     * @return $this
     */
    public function title(array|string $message): static;

    /**
     * Formats a command comment.
     *
     * @param array|string $message
     * @return $this
     */
    public function comment(array|string $message): static;

    /**
     * Formats a success result bar.
     *
     * @param array|string $message
     * @return $this
     */
    public function success(array|string $message): static;

    /**
     * Formats an error result bar.
     *
     * @param array|string $message
     * @return $this
     */
    public function error(array|string $message): static;

    /**
     * Formats an warning result bar.
     *
     * @param array|string $message
     * @return $this
     */
    public function warning(array|string $message): static;

    /**
     * Formats a note admonition.
     *
     * @param array|string $message
     * @return $this
     */
    public function note(array|string $message): static;

    /**
     * Formats an info message.
     *
     * @param array|string $message
     * @return $this
     */
    public function info(array|string $message): static;
}
