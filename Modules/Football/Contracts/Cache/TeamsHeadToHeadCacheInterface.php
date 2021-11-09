<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TeamsHeadToHead;

interface TeamsHeadToHeadCacheInterface
{
    public function put(TeamsHeadToHead $teamsHeadToHead, TimeToLive $timeToLive): bool;

    /**
     * The order of the team ids are placed does not matter.
     * has(teamOne, teamTwo) will yield the same result as has(teamTwo, teamOne)
     */
    public function has(TeamId $teamOne, TeamId $teamTwo): bool;

    /**
     * The order of the team ids does not matter.
     * has(teamOne, teamTwo) will yield the same result as has(teamTwo, teamOne)
     *
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(TeamId $teamOne, TeamId $teamTwo): TeamsHeadToHead;
}
