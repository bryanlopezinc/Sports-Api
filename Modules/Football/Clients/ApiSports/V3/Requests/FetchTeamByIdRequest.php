<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Requests;

use Module\Football\ValueObjects\TeamId;
use Module\Football\Clients\ApiSports\V3\Request;

final class FetchTeamByIdRequest extends Request
{
    public function __construct(TeamId $teamId)
    {
        parent::__construct('teams', ['id' => $teamId->toInt()]);
    }
}
