<?php

namespace IsaEken\LaravelBackup\Traits;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

trait HasAttributes
{
    public function hasAttribute(string $key): bool
    {
        return property_exists($this, $key);
    }

    #[Pure]
    public function getAttribute(string $key, mixed $default = null): mixed
    {
        if ($this->hasAttribute($key)) {
            return $this->$key;
        }

        return $default;
    }

    public function setAttribute(string $key, mixed $value): static
    {
        if ($this->hasAttribute($key)) {
            $this->$key = $value;
        }

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        $name = Str::of($name);
        if ($name->startsWith(['is', 'get', 'has', 'set'])) {
            $variable = ($name->startsWith('is') ? $name->after('is') : $name->substr(strlen('xxx')))->camel();

            if ($name->startsWith('is')) {
                return $this->getAttribute($variable, ...$arguments) === true;
            } elseif ($name->startsWith('get')) {
                return $this->getAttribute($variable, ...$arguments);
            } elseif ($name->startsWith('has')) {
                return $this->hasAttribute($variable);
            } elseif ($name->startsWith('set')) {
                return $this->setAttribute($variable, ...$arguments);
            }
        }

        return $this->{$name->__toString()}(...$arguments);
    }
}
