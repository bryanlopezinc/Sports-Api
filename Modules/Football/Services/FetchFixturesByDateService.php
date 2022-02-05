<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\Cache\FixturesByDateCacheRepository;
use Module\Football\LeagueFixturesGroup;
use Module\Football\Contracts\Repositories\FetchFixturesByDateRepositoryInterface;

final class FetchFixturesByDateService
{
    public function __construct(
        private FetchFixturesByDateRepositoryInterface $repository,
        private FixturesByDateCacheRepository $cache,
    ) {
    }

    /**
     * @return array<LeagueFixturesGroup>
     */
    public function date(Date $date): array
    {
        if ($this->cache->has($date)) {
            return $this->cache->get($date);
        }

        $this->cache->put($date, $fixtures = $this->repository->asGroup($date), $this->determineTimeToLiveFrom($date));

        return $fixtures;
    }

    private function determineTimeToLiveFrom(Date $date): TimeToLive
    {
        return $date->toCarbon()->isSameWeek() ? TimeToLive::days(1) : TimeToLive::minutes(10);
    }
}
