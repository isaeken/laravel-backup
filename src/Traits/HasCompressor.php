<?php

namespace IsaEken\LaravelBackup\Traits;

use IsaEken\LaravelBackup\Contracts\Compressor;

trait HasCompressor
{
    private Compressor $compressor;

    /**
     * Set compressor instance.
     *
     * @param  Compressor  $compressor
     * @return $this
     */
    public function setCompressor(Compressor $compressor): static
    {
        $this->compressor = $compressor;
        return $this;
    }

    /**
     * Get compressor instance.
     *
     * @return Compressor
     */
    public function getCompressor(): Compressor
    {
        return $this->compressor;
    }
}
