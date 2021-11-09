<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\ValueObjects\Season;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Cache\LeaguesCacheRepository;
use Module\Football\Cache\LeaguesSeasonsCacheRepository;

class LeaguesCacheRepositoryTest extends TestCase
{
    private LeaguesCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new LeaguesCacheRepository(
            Cache::store(),
            new LeaguesSeasonsCacheRepository(Cache::store())
        );
    }

    /**
     * @test
     */
    public function throws_exception_when_league_season_does_not_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $league = LeagueFactory::new()->toDto();

        $this->repository->cache($league, TimeToLive::minutes(1));

        $this->repository->findByIdAndSeason(
            LeagueFactory::new()->toDto()->getId(),
            Season::make($league->getSeason()->getSeason()->toInt() - 1)
        );
    }

    /**
     * @test
     */
    public function throws_exception_when_league_does_not_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $league = LeagueFactory::new()->toDto();

        $this->repository->findById($league->getId());
    }

    /**
     * @test
     */
    public function returns_true_when_league_exists(): void
    {
        $league = LeagueFactory::new()->toDto();

        $this->repository->cache($league, TimeToLive::minutes(1));

        $this->assertTrue($this->repository->has($league->getId()));
    }

    /**
     * @test
     */
    public function returns_false_when_league_does_not_exists(): void
    {
        $league = LeagueFactory::new()->toDto();

        $this->assertFalse($this->repository->has($league->getId()));
    }
}
