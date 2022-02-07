<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Services;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\Cache\TeamsHeadToHeadCacheRepository;
use Tests\TestCase;
use Module\Football\DTO\Team;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\FixtureFactory;
use Module\Football\ValueObjects\TeamsHeadToHead;
use Module\Football\Services\FetchTeamsHeadToHeadService;
use Module\Football\Contracts\Repositories\FetchTeamHeadToHeadRepositoryInterface;

class FetchTeamsHeadToHeadServiceTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function getMocks()
    {
        return [
            $this->getMockBuilder(FetchTeamHeadToHeadRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(Repository::class)->getMock(),
        ];
    }

    public function test_will_not_query_repository_on_subsequent_requests_to_same_teams_head_to_head(): void
    {
        [$repository, $cache] = $this->getMocks();

        /** @var array<Team> */
        $teams = TeamFactory::new()->count(2)->toCollection()->toLaravelCollection()->all();

        $fixtures = FixtureFactory::new()
            ->count(5)
            ->homeTeam($teams[0])
            ->awayTeam($teams[1])
            ->winner($teams[0])
            ->toCollection();

        $headToHead = new TeamsHeadToHead($teams[0]->getId(), $teams[1]->getId(), $fixtures);

        $cache->expects($this->exactly(2))->method('has')->willReturn(false, true);
        $cache->expects($this->once())->method('get')->willReturn($headToHead);
        $cache->expects($this->once())->method('put')->willReturn(true);

        $repository->expects($this->once())->method('getHeadToHead')->willReturn($headToHead);

        $this->getServiceInstance($repository, $cache)->fetch($teams[0]->getId(), $teams[1]->getId());
        $this->getServiceInstance($repository, $cache)->fetch($teams[0]->getId(), $teams[1]->getId());
    }

    private function getServiceInstance($repository, $cache): FetchTeamsHeadToHeadService
    {
        $this->instance(FetchTeamHeadToHeadRepositoryInterface::class, $repository);
        $this->instance(TeamsHeadToHeadCacheRepository::class, new TeamsHeadToHeadCacheRepository($cache));

        return app(FetchTeamsHeadToHeadService::class);
    }
}
