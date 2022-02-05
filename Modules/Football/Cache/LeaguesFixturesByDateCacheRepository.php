<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\FixturesCollection;

final class LeaguesFixturesByDateCacheRepository
{
    public function __construct(private Repository $respository)
    {
    }

    public function has(LeagueId $leagueId, Season $season, Date $date): bool
    {
        return $this->respository->has($this->prepareKey($leagueId, $season, $date));
    }

    public function put(LeagueId $leagueId, Season $season, Date $date, FixturesCollection $fixturesCollection, TimeToLive $ttl): bool
    {
        return $this->respository->put(
            $this->prepareKey($leagueId, $season, $date),
            $fixturesCollection,
            $ttl->ttl()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(LeagueId $leagueId, Season $season, Date $date): FixturesCollection
    {
        return $this->respository->get(
            $this->prepareKey($leagueId, $season, $date),
            fn () => throw new ItemNotInCacheException()
        );
    }

    private function prepareKey(LeagueId $leagueId, Season $season, Date $date): string
    {
        return new CachePrefix($this) . $leagueId->toInt() . ':' . $season->toInt() . ':' . $date->toCarbon()->toDateString();
    }
}
