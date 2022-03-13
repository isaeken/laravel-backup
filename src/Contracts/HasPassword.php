<?php

namespace IsaEken\LaravelBackup\Contracts;

interface HasPassword
{
    /**
     * Get compression password if available.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Set compression password if available.
     *
     * @param  string  $password
     * @return $this
     */
    public function setPassword(string $password): static;
}
