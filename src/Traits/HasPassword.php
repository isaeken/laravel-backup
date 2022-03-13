<?php

namespace IsaEken\LaravelBackup\Traits;

trait HasPassword
{
    private string $password = '';

    /**
     * Get password available.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set password available.
     *
     * @param  string  $password
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
}
