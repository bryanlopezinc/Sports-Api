<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Services;

use Tests\TestCase;
use Module\Football\DTO\Team;
use Module\Football\Factories\TeamFactory;
use Module\Football\Services\FetchTeamService;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Module\Football\Contracts\Repositories\FetchTeamRepositoryInterface;

class FindTeamServiceTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function getMocks()
    {
        return [
            $this->getMockBuilder(TeamsCacheInterface::class)->getMock(),
            $this->getMockBuilder(FetchTeamRepositoryInterface::class)->getMock()
        ];
    }

    public function test_will_not_query_repository_when_all_items_exists_in_cache(): void
    {
        [$cache, $repository] = $this->getMocks();

        $repository->expects($this->never())->method('findManyById');

        $cache->expects($this->once())->method('getMany')->willReturn(TeamFactory::new()->count(5)->toCollection());

        $this->getServiceInstance($cache, $repository)->findManyById(TeamFactory::new()->count(5)->toCollection()->pluckIds());
    }

    public function test_will_query_repository_for_only_missing_ids(): void
    {
        $teams = TeamFactory::new()->count(5)->toCollection();

        /** @var Team */
        $lastTeam = $teams->toLaravelCollection()->last();

        [$cache, $repository] = $this->getMocks();

        $cache->expects($this->once())
            ->method('getMany')
            ->willReturn(new TeamsCollection($teams->toLaravelCollection()->take(4)->all()));

        $repository
            ->expects($this->once())
            ->method('findManyById')
            ->willReturnCallback(function (TeamIdsCollection $arg) use ($lastTeam, $teams) {
                $this->assertTrue($lastTeam->getId()->equals($arg->toLaravelCollection()->sole()));

                return new TeamsCollection([$teams->toLaravelCollection()->last()]);
            });

        $this->getServiceInstance($cache, $repository)->findManyById($teams->pluckIds());
    }

    private function getServiceInstance($cache, $repository): FetchTeamService
    {
        $this->instance(TeamsCacheInterface::class, $cache);
        $this->instance(FetchTeamRepositoryInterface::class, $repository);

        return app(FetchTeamService::class);
    }
}
