<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Exceptions\Cache\CannotCacheEmptyTeamSquadException;

interface TeamsSquadsCacheInterface
{
    public function has(TeamId $teamId): bool;

    /**
     * @throws ItemNotInCacheException
     */
    public function getTeamSquad(TeamId $teamId): PlayersCollection;

    /**
     * @throws CannotCacheEmptyTeamSquadException
     */
    public function cache(TeamId $teamId, PlayersCollection $playersCollection, TimeToLive $ttlInSeconds): void;
}
