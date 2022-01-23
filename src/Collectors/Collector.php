<?php

namespace IsaEken\LaravelBackup\Collectors;

use Illuminate\Support\Collection;

abstract class Collector implements \IsaEken\LaravelBackup\Contracts\Collector
{
    private array $items = [];

    public function setItems(array $items): static
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function collect(): Collection
    {
        return collect($this->toArray());
    }
}
