<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;

interface TeamsCacheInterface
{
    public function has(TeamId $teamId): bool;

    public function getMany(TeamIdsCollection $ids): TeamsCollection;

    public function cache(Team $team, TimeToLive $ttl): bool;
}
