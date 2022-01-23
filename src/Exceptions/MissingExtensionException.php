<?php

namespace IsaEken\LaravelBackup\Exceptions;

use Exception;

class MissingExtensionException extends Exception
{
    public function __construct(string $extension)
    {
        parent::__construct("The extension ${$extension} is not installed or loaded in your environment.");
    }
}
