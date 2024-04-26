<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class RepositoryResourceFailedException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }
        parent::__construct($message, $code, $previous);
    }

    protected $message = 'Repository Resource Failed';
}
