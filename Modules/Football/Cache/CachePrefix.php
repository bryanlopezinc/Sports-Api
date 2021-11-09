<?php

declare(strict_types=1);

namespace Module\Football\Cache;

final class CachePrefix
{
    public function __construct(private Object|string $repository)
    {
    }

    private function prefix(): string
    {
        $repository = is_string($this->repository) ? $this->repository : $this->repository::class;

        return match ($repository) {
            FixtureEventsCacheRepository::class         => 's:F-E:',
            FixturesByDateCacheRepository::class        => 's:F-BD:',
            FixturesCacheRepository::class              => 's:F:',
            FixturesLineUpCacheRepository::class        => 's:F-LP:',
            FixturesStatisticsCacheRepository::class    => 's:F-ST:',
            LeaguesFixturesByDateCacheRepository::class => 's:L-FD:',
            LeaguesCacheRepository::class               => 's:L:',
            LeaguesSeasonsCacheRepository::class        => 's:L-S:',
            LeagueStandingCacheRepository::class        => 's:L-SD:',
            TeamsCacheRepository::class                 => 's:T:',
            TeamSquadCacheRepository::class             => 's:T-SQ:',
            TeamsHeadToHeadCacheRepository::class       => 's:T-H2H:',
        };
    }

    public function __toString()
    {
        return $this->prefix();
    }
}
