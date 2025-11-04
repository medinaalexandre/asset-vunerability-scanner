<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidApiResponseException extends Exception
{
    protected $code = 500;

    public function __construct(
        public array|string $content = '',
        string $message = 'API response structure is invalid.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
