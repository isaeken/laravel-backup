<?php

namespace IsaEken\LaravelBackup\Storages;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\BackupStorage;
use Spatie\Dropbox\Client;

class DropboxStorage implements BackupStorage
{
    private static string $token = '';

    private Client|null $client = null;

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client(static::$token);
        }

        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'dropbox';
    }

    /**
     * @inheritDoc
     */
    public function save(string $filepath, string $directory): bool
    {
        $path = '/' . Str::slug(config('app.name'), '_') . '/' . Str::slug($directory, '_') . '/' . basename($filepath);
        $response = $this->getClient()->upload($path, File::get($filepath));
        return @($response['size'] === File::size($filepath));
    }
}
