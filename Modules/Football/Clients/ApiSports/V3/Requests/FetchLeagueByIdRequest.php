<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Requests;

use Module\Football\ValueObjects\LeagueId;
use Module\Football\Clients\ApiSports\V3\Request;

final class FetchLeagueByIdRequest extends Request
{
    public function __construct(LeagueId $leagueId, array $query = [])
    {
        parent::__construct('leagues', array_merge(['id' => $leagueId->toInt()], $query));
    }
}
