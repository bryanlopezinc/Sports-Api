<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

final class CoverageNotSupportedHttpException extends HttpException
{
    public function __construct(string $message, \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }
}
