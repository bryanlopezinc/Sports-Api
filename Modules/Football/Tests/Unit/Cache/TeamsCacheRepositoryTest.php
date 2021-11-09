<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Module\Football\DTO\Team;
use Illuminate\Support\Facades\Cache;
use Module\Football\Factories\TeamFactory;
use Module\Football\Cache\TeamsCacheRepository;

class TeamsCacheRepositoryTest extends TestCase
{
    private TeamsCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TeamsCacheRepository(Cache::store());
    }

    /**
     * @test
     */
    public function returns_empty_collection_when_team_does_not_exists(): void
    {
        $teams = TeamFactory::new()->count(2)->toCollection();

        $this->assertTrue($this->repository->getMany($teams->pluckIds())->isEmpty());
    }

    /**
     * @test
     */
    public function returns_collection_when_teams_exists(): void
    {
        $teams = TeamFactory::new()->count(2)->toCollection();

        $teams->toLaravelCollection()->each(fn(Team $team) => $this->repository->cache($team, TimeToLive::minutes(1)));

        $this->assertCount(2, $this->repository->getMany($teams->pluckIds()));
    }

    /**
     * @test
     */
    public function returns_true_when_team_exists(): void
    {
        $team = TeamFactory::new()->toDto();

        $this->repository->cache($team, TimeToLive::minutes(1));

        $this->assertTrue($this->repository->has($team->getId()));
    }

    /**
     * @test
     */
    public function returns_false_when_team_does_not_exists(): void
    {
        $this->assertFalse($this->repository->has(TeamFactory::new()->toDto()->getId()));
    }
}
