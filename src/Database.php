<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Support\Collection;

class Database
{
    private Collection $backups;

    public function __construct()
    {
        $this->backups = new Collection();
    }

    /**
     * Get backups.
     *
     * @return Collection<\IsaEken\LaravelBackup\Models\Backup>
     */
    public function backups(): Collection
    {
        return $this->backups;
    }

    /**
     * Load database.
     *
     * @return $this
     */
    public function load(): static
    {
        $backups = @json_decode(@file_get_contents(config('backup.database')) ?? []);
        foreach ($backups as $backup) {
            $this
                ->backups()
                ->add(
                    new \IsaEken\LaravelBackup\Models\Backup((array) $backup)
                );
        }

        return $this;
    }

    /**
     * Save database.
     *
     * @return $this
     */
    public function save(): static
    {
        @file_put_contents(config('backup.database'), $this->backups()->toJson());

        return $this;
    }
}
