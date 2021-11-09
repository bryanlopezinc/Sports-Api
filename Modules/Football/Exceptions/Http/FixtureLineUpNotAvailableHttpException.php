<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when a fixture line up is not yet available
 */
final class FixtureLineUpNotAvailableHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(204, 'FixtureLineupNotReady');
    }
}
