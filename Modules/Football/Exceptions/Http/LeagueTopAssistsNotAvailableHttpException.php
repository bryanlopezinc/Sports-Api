<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when league top assists is not yet available
 */
final class LeagueTopAssistsNotAvailableHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(204, 'LeagueTopAssistsNotAvailable');
    }
}
