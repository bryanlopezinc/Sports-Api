<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\ValueObjects\Season;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeagueTable;
use Module\Football\Factories\LeagueStandingFactory;
use Module\Football\Cache\LeagueStandingCacheRepository;
use Module\Football\Exceptions\Cache\CannotCacheEmptyLeagueTableException;

class LeaguesStandingsCacheRepositoryTest extends TestCase
{
    private LeagueStandingCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new LeagueStandingCacheRepository(Cache::store());
    }

    /**
     * @test
     */
    public function cannot_cache_empty_league_table(): void
    {
        $this->expectException(CannotCacheEmptyLeagueTableException::class);

        $this->repository->cache(new LeagueTable([]), Season::make(2019), TimeToLive::seconds(2));
    }

    /**
     * @test
     */
    public function throws_exception_when_league_table_does_not_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $table = new LeagueTable([LeagueStandingFactory::new()->toDto()]);
        $season = $table->getLeague()->getSeason()->getSeason();

        $this->repository->get($table->getLeague()->getId(), $season);
    }

    /**
     * @test
     */
    public function returns_true_when_league_table_exists(): void
    {
        $table = new LeagueTable([LeagueStandingFactory::new()->toDto()]);
        $season = $table->getLeague()->getSeason()->getSeason();

        $this->repository->cache($table, $season, TimeToLive::seconds(20));

        $this->assertTrue($this->repository->has($table->getLeague()->getId(), $season));
    }

    /**
     * @test
     */
    public function returns_false_when_league_table_does_not_exists(): void
    {
        $table = new LeagueTable([LeagueStandingFactory::new()->toDto()]);
        $season = $table->getLeague()->getSeason()->getSeason();

        $this->assertFalse($this->repository->has($table->getLeague()->getId(), $season));
    }
}
