<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CveIdNotFoundException extends Exception
{
    protected $message = 'CVE-ID %cveId% not found';
    protected $code = 404;

    public function __construct(
        protected string $cveId,
        ?Throwable $previous = null
    ) {
        $message = str_replace('%cveId%', $cveId, $this->message);
        parent::__construct($message, $this->code, $previous);
    }
}
