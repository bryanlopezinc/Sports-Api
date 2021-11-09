<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when fixture line up is not supported for a league season
 */
final class FixtureLineUpNotSupportedHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'FixtureLineUpNotSupported');
    }
}
