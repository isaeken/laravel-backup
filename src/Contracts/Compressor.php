<?php

namespace IsaEken\LaravelBackup\Contracts;

interface Compressor extends Runnable
{
    /**
     * Get source.
     *
     * @return string
     */
    public function getSource(): string;

    /**
     * Set the source.
     *
     * @param  string  $source
     * @return $this
     */
    public function setSource(string $source): static;

    /**
     * Set the destination.
     *
     * @param  string  $destination
     * @return $this
     */
    public function setDestination(string $destination): static;

    /**
     * Get destination.
     *
     * @return string
     */
    public function getDestination(): string;
}
