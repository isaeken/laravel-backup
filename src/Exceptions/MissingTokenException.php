<?php

namespace IsaEken\LaravelBackup\Exceptions;

use Exception;

class MissingTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct("The token is not provided.");
    }
}
