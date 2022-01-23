<?php

namespace IsaEken\LaravelBackup\Exceptions;

use Exception;

class CompressorNotProvidedException extends Exception
{
    public function __construct()
    {
        parent::__construct("This backup is required a compressor!");
    }
}
