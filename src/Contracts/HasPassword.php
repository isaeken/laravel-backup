<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasPassword
{
    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Set password.
     *
     * @param  string  $password
     * @return $this
     */
    public function setPassword(string $password): static;
}
