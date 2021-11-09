<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when a fixture statistics is not supported for a league season
 */
final class FixtureStatisticsNotSupportedForLeagueSeasonHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'FixtureStatisticsNotSupported');
    }
}
