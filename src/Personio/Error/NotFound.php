<?php

declare(strict_types=1);

namespace Kreait\Personio\Error;

use Kreait\Personio\Error;

class NotFound extends \DomainException implements Error
{
    public static function because(string $reason, \Throwable $previous = null)
    {
        return new self($reason, $previous ? $previous->getCode() : 0, $previous);
    }
}
