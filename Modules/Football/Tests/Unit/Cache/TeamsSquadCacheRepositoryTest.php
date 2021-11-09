<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\Factories\TeamFactory;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Factories\PlayerFactory;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Cache\TeamSquadCacheRepository;
use Module\Football\Cache\Exceptions\CannotCacheEmptyTeamSquadException;

class TeamsSquadCacheRepositoryTest extends TestCase
{
    private TeamSquadCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TeamSquadCacheRepository(Cache::store());
    }

    /**
     * @test
     */
    public function cannot_cache_empty_team_squad(): void
    {
        $this->expectException(CannotCacheEmptyTeamSquadException::class);

        $this->repository->cache(TeamFactory::new()->toDto()->getId(), new PlayersCollection([]), TimeToLive::seconds(2));
    }

    /**
     * @test
     */
    public function returns_true_when_team_id_exists(): void
    {
        $this->repository->cache($teamId = TeamFactory::new()->toDto()->getId(), PlayerFactory::new()->count(22)->toCollection(), TimeToLive::minutes(1));

        $this->assertTrue($this->repository->has($teamId));
    }

    /**
     * @test
     */
    public function returns_false_when_team_id_does_not_exists(): void
    {
        $this->assertFalse($this->repository->has(TeamFactory::new()->toDto()->getId()));
    }

    /**
     * @test
     */
    public function throws_exception_when_team_id_does_not_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $this->repository->getTeamSquad(TeamFactory::new()->toDto()->getId());
    }
}
