<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\Factories\TeamFactory;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Factories\FixtureFactory;
use Module\Football\ValueObjects\TeamsHeadToHead;
use Module\Football\Cache\TeamsHeadToHeadCacheRepository;

class TeamsHeadToHeadCacheRepositoryTest extends TestCase
{
    private TeamsHeadToHeadCacheRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TeamsHeadToHeadCacheRepository(Cache::store());
    }

    private function headToHead(): TeamsHeadToHead
    {
        [$team1, $team2] = [TeamFactory::new()->toDto(), TeamFactory::new()->toDto()];

        $fixtures = FixtureFactory::new()
            ->count(4)
            ->homeTeam($team1)
            ->awayTeam($team2)
            ->winnerId($team1->getId())
            ->toCollection();

        return new TeamsHeadToHead($team1->getId(), $team2->getId(), $fixtures);
    }

    /**
     * @test
     */
    public function Has_will_return_false_regardless_of_the_order_the_team_ids_are_placed(): void
    {
        $headToHead = $this->headToHead();

        $this->assertFalse($this->repository->has($headToHead->getTeamOneId(), $headToHead->getTeamTwoId()));
        $this->assertFalse($this->repository->has($headToHead->getTeamTwoId(), $headToHead->getTeamOneId()));
    }

    /**
     * @test
     */
    public function Has_will_return_true_regardless_of_the_order_the_team_ids_are_placed(): void
    {
        $headToHead = $this->headToHead();

        $this->repository->put($headToHead, TimeToLive::minutes(1));

        $this->assertTrue($this->repository->has($headToHead->getTeamOneId(), $headToHead->getTeamTwoId()));
        $this->assertTrue($this->repository->has($headToHead->getTeamTwoId(), $headToHead->getTeamOneId()));
    }

    /**
     * @test
     */
    public function will_throw_exception_when_teamIds_dont_exists(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $headToHead = $this->headToHead();

        $this->repository->get($headToHead->getTeamOneId(), $headToHead->getTeamTwoId());
    }

    /**
     * @test
     */
    public function GET_will_return_same_value_regardless_of_the_order_the_team_ids_are_placed(): void
    {
        $headToHead = $this->headToHead();

        $this->repository->put($headToHead, TimeToLive::minutes(1));

        $this->assertEquals($this->repository->get($headToHead->getTeamOneId(), $headToHead->getTeamTwoId()), $headToHead);
        $this->assertEquals($this->repository->get($headToHead->getTeamTwoId(), $headToHead->getTeamOneId()), $headToHead);
    }
}
