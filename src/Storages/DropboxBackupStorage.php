<?php

namespace IsaEken\LaravelBackup\Storages;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\BackupStorage;
use IsaEken\LaravelBackup\Exceptions\MissingTokenException;
use IsaEken\LaravelBackup\Storages\BackupStorage as BaseStorage;
use Spatie\Dropbox\Client;

class DropboxBackupStorage extends BaseStorage implements BackupStorage
{
    protected string $name = 'dropbox';

    public string|null $token = null;

    private Client|null $client = null;

    public function getToken(): string
    {
        throw_if($this->token === null, MissingTokenException::class);
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client($this->getToken());
        }

        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function save(string $filepath, string $directory): bool
    {
        $path = '/' . Str::slug(config('backup.name'), '_') . '/' . Str::slug($directory, '_') . '/' . basename($filepath);
        $response = $this->getClient()->upload($path, File::get($filepath));
        return @($response['size'] === File::size($filepath));
    }
}
