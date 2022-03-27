<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Support\Stringable;
use IsaEken\LaravelBackup\Contracts\Backup\Service;

class Filename
{
    public bool $camelCase = false;

    public bool $addExtension = true;

    public function __construct(
        public string $filename,
        public string $extension,
        public Service $service,
        public string $disk
    ) {
        // ...
    }

    public function getPattern(): string
    {
        return config('backup.filename_pattern', 'backup_:app.name:_:service.name:_:datetime:');
    }

    /**
     * @return array<string, string>
     */
    public function getReplaces(): array
    {
        return [
            'filename' => $this->filename,
            'extension' => $this->extension,
            'service.name' => $this->service->getName(),
            'disk' => $this->disk,
            'datetime' => date('Y-m-d-H-i-s'),
            'date' => date('Y-m-d'),
            'time' => date('H-i-s'),
        ];
    }

    public function make(): Stringable
    {
        return str($this->getPattern())
            ->pipe(function (Stringable $string) {
                foreach ($this->getReplaces() as $key => $value) {
                    $string = $string->replace(":$key:", $value);
                }

                return $string;
            })
            ->replaceMatches("/\:([a-z\.\_A-Z])+\:/", function ($match) {
                if (! isset($match[0])) {
                    return '';
                }

                return config(str($match[0])->between(':', ':')->value());
            })
            ->pipe(function (Stringable $string) {
                return $string->lower()->slug('_');
            })
            ->pipe(function (Stringable $string) {
                if ($this->camelCase) {
                    return $string->camel();
                }

                return $string->snake();
            });
    }

    public function value(): string
    {
        $value = $this->make();

        if ($this->addExtension) {
            $value = $value->append('.', $this->extension);
        }

        return $value->value();
    }
}
