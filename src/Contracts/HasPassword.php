<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasPassword
{
    /**
     * Get password available.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Set password available.
     *
     * @param  string  $password
     * @return $this
     */
    public function setPassword(string $password): static;
}
