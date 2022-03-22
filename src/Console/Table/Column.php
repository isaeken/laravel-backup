<?php

namespace IsaEken\LaravelBackup\Console\Table;

class Column
{
    public function __construct(
        public string $key,
        public string $header,
        public bool $rightAligned = false,
    ) {
        // ...
    }
}
