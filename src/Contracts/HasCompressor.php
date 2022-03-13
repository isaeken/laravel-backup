<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasCompressor
{
    /**
     * Set compressor instance.
     *
     * @param  Compressor  $compressor
     * @return $this
     */
    public function setCompressor(Compressor $compressor): static;

    /**
     * Get compressor instance.
     *
     * @return Compressor
     */
    public function getCompressor(): Compressor;
}
