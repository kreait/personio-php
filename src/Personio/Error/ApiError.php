<?php

declare(strict_types=1);

namespace Kreait\Personio\Error;

use Kreait\Personio\Error;

class ApiError extends \RuntimeException implements Error
{
    public static function because(string $reason = null, \Throwable $previous = null)
    {
        $code = 0;

        if ($previous) {
            $reason = $reason ?? $previous->getMessage();
            $code = $previous->getCode();
        }

        $reason = $reason ?? 'Unknown API error';

        return new self($reason, $code, $previous);
    }
}
