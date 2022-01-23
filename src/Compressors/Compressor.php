<?php

namespace IsaEken\LaravelBackup\Compressors;

abstract class Compressor implements \IsaEken\LaravelBackup\Contracts\Compressor
{
    protected string $source;

    protected string $destination;

    /**
     * @inheritDoc
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @inheritDoc
     */
    public function setSource(string $source): static
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDestination(string $destination): static
    {
        $this->destination = $destination;
        return $this;
    }
}
