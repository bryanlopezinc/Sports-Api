<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Prediction;

use Tests\TestCase;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Cache\FixturePredictionsCacheRepository;
use Module\Football\Prediction\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\User\ValueObjects\UserId;

class FetchFixturePredictionsCacheRepositoryTest extends TestCase
{
    public function test_user_has_predicted_fixture(): void
    {
        $repositoryMock = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repositoryMock->expects($this->exactly(4))->method('userHasPredictedFixture')->willReturn(false, false, false, true);

        $cache = $this->getRepositoryInstance($repositoryMock);

        $this->assertFalse($cache->userHasPredictedFixture(new UserId(33), new FixtureId(22)));
        $this->assertFalse($cache->userHasPredictedFixture(new UserId(32), new FixtureId(21)));
        $this->assertFalse($cache->userHasPredictedFixture(new UserId(31), new FixtureId(20)));

        // expect no call to base repo
        $this->assertFalse($cache->userHasPredictedFixture(new UserId(31), new FixtureId(20)));
        $this->assertFalse($cache->userHasPredictedFixture(new UserId(32), new FixtureId(21)));
        $this->assertFalse($cache->userHasPredictedFixture(new UserId(33), new FixtureId(22)));

        $cache->forget(new UserId(33), new FixtureId(22));

        $this->assertTrue($cache->userHasPredictedFixture(new UserId(33), new FixtureId(22)));
        $this->assertTrue($cache->userHasPredictedFixture(new UserId(33), new FixtureId(22)));
    }

    private function getRepositoryInstance($baseRepository)
    {
        return new FixturePredictionsCacheRepository($baseRepository, app('cache')->store());
    }
}
