<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Predictions\Football;

use Tests\TestCase;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Cache\UsersPredictionsCacheRepository;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\User\Predictions\Football\Prediction;
use Module\User\ValueObjects\UserId;

class UsersPredictionsCacheRepositoryTest extends TestCase
{
    public function test_fetchUserPredictions(): void
    {
        $repositoryMock = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        [$home, $away, $draw] = [
            Prediction::HOME_WIN,
            Prediction::AWAY_WIN,
            Prediction::DRAW,
        ];

        $repositoryMock->expects($this->exactly(4))->method('fetchUserPrediction')->willReturn($away, $away, $draw, $home);

        $cache = $this->getRepositoryInstance($repositoryMock);

        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(33)), $away);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(220)), $away);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(122), new UserId(33)), $draw);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(44), new UserId(20)), $home);

        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(33)), $away);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(33)), $away);

        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(122), new UserId(33)), $draw);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(122), new UserId(33)), $draw);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(122), new UserId(33)), $draw);

        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(44), new UserId(20)), $home);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(44), new UserId(20)), $home);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(44), new UserId(20)), $home);

        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(220)), $away);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(220)), $away);
        $this->assertEquals($cache->fetchUserPrediction(new FixtureId(22), new UserId(220)), $away);
    }

    private function getRepositoryInstance($baseRepository)
    {
        return new UsersPredictionsCacheRepository($baseRepository, app('cache')->store());
    }
}
