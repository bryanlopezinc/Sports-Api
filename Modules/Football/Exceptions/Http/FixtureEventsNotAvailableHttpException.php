<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when a fixture events up is not yet available
 */
final class FixtureEventsNotAvailableHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(204, 'EventsNotReady');
    }
}
