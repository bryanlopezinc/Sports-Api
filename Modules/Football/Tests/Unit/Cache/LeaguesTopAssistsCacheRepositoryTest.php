<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Factories\PlayerFactory;
use Module\Football\ValueObjects\LeagueTopAssist;
use Module\Football\Cache\LeaguesTopAsisstsCacheRepository;
use Module\Football\Collections\LeagueTopAssistsCollection;
use Module\Football\Exceptions\Cache\CannotCacheEmptyTopAssistsException;

class LeaguesTopAssistsCacheRepositoryTest extends TestCase
{
    private LeaguesTopAsisstsCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new LeaguesTopAsisstsCacheRepository(Cache::store());
    }

    public function test_cannot_cache_empty_top_assists(): void
    {
        $this->expectException(CannotCacheEmptyTopAssistsException::class);

        $this->repository->cache(new LeagueId(10), Season::make(2019), new LeagueTopAssistsCollection([]), TimeToLive::seconds(2));
    }

    public function test_throws_exception_when_top_assists_does_not_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $this->repository->get(new LeagueId(11), Season::make(2010));
    }

    public function test_has_top_assits(): void
    {
        $this->assertFalse($this->repository->has(new LeagueId(11), Season::make(2010)));

        $assists = new LeagueTopAssistsCollection([new LeagueTopAssist(PlayerFactory::new()->toDto(), 12)]);

        $this->repository->cache(new LeagueId(11), Season::make(2010), $assists, TimeToLive::seconds(2));

        $this->assertTrue($this->repository->has(new LeagueId(11), Season::make(2010)));
    }
}
