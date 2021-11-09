<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Services;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\PlayerFactory;
use Module\Football\Services\FetchTeamSquadService;
use Module\Football\Contracts\Cache\TeamsSquadsCacheInterface;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;

class FetchTeamSquadServiceTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function getMocks()
    {
        return [
            $this->getMockBuilder(FetchTeamSquadRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(TeamsSquadsCacheInterface::class)->getMock(),
        ];
    }

    public function test_will_not_query_repository_on_subsequent_requests_to_same_team_id(): void
    {
        [$repository, $cache] = $this->getMocks();

        $team = TeamFactory::new()->toDto();
        $players = PlayerFactory::new()->count(22)->toCollection();

        $cache->expects($this->exactly(2))->method('has')->willReturn(false, true);
        $cache->expects($this->once())->method('getTeamSquad')->willReturn($players);
        $cache->expects($this->once())->method('cache');

        $repository->expects($this->once())->method('teamSquad')->willReturn($players);

        $this->getServiceInstance($repository, $cache)->fetch($team->getId());
        $this->getServiceInstance($repository, $cache)->fetch($team->getId());
    }

    private function getServiceInstance($repository, $cache): FetchTeamSquadService
    {
        $this->instance(FetchTeamSquadRepositoryInterface::class, $repository);
        $this->instance(TeamsSquadsCacheInterface::class, $cache);

        return app(FetchTeamSquadService::class);
    }
}
