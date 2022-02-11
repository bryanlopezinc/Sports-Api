<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Prediction;

use Tests\TestCase;
use Module\User\ValueObjects\UserId;
use Module\User\Factories\UserFactory;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Prediction;
use Module\Football\Prediction\PredictionsRepository;
use Module\Football\Prediction\Models\Prediction as PredictionModel;

class PredictionsRepositoryTest extends TestCase
{
    private PredictionsRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PredictionsRepository;
    }

    public function test_store_prediction(): void
    {
        $user = UserFactory::new()->create();

        $this->assertTrue($this->repository->create(new FixtureId(23), new UserId($user->id), Prediction::AWAY_WIN));

        $this->assertDatabaseHas(PredictionModel::class, [
            'fixture_id' => 23,
            'user_id'    => $user->id
        ]);
    }

    public function test_user_has_predicted_fixture(): void
    {
        $user = UserFactory::new()->create();

        $this->repository->create(new FixtureId(215662), new UserId($user->id), Prediction::AWAY_WIN);

        $this->assertTrue($this->repository->userHasPredictedFixture(new UserId($user->id), new FixtureId(215662)));
    }

    public function test_fetch_user_prediction(): void
    {
        $userId = new UserId(UserFactory::new()->create()->id);

        $this->repository->create(new FixtureId(12), $userId, Prediction::HOME_WIN);
        $this->repository->create(new FixtureId(13), $userId, Prediction::AWAY_WIN);
        $this->repository->create(new FixtureId(14), $userId, Prediction::DRAW);

        [$homeToWin, $awayToWin, $draw] = [
            $this->repository->fetchUserPrediction(new FixtureId(12), $userId),
            $this->repository->fetchUserPrediction(new FixtureId(13), $userId),
            $this->repository->fetchUserPrediction(new FixtureId(14), $userId)
        ];

        $this->assertTrue($homeToWin->isHomeToWin());
        $this->assertTrue($awayToWin->isAwayToWin());
        $this->assertTrue($draw->isDraw());
    }

    public function test_will_return_correct_predictions_data(): void
    {
        PredictionModel::truncate();

        $repository = new PredictionsRepository;
        $usersCount = 30;
        $users = UserFactory::new()->count($usersCount)->create();
        $fixtureId = new FixtureId(40);

        foreach ($users->slice(0, 20) as $key => $user) {
            $repository->create(
                $fixtureId,
                new UserId($user->id),
                $key % 2 === 0 ? Prediction::AWAY_WIN : Prediction::HOME_WIN
            );
        }

        foreach ($users->slice(20, 10) as $user) {
            $repository->create($fixtureId, new UserId($user->id), Prediction::DRAW);
        }

        $predictions = $repository->fetchPredictionsResultFor($fixtureId);

        $this->assertEquals($usersCount, $predictions->total);
        $this->assertEquals(10, $predictions->awayWins);
        $this->assertEquals(10, $predictions->homeWins);
        $this->assertEquals(10, $predictions->draws);
    }

    public function test_will_return_correct_predictions_data_when_empty(): void
    {
        PredictionModel::truncate();

        $repository = new PredictionsRepository;
        $fixtureId = new FixtureId(40);

        $predictions = $repository->fetchPredictionsResultFor($fixtureId);

        $this->assertEquals(0, $predictions->total);
        $this->assertEquals(0, $predictions->awayWins);
        $this->assertEquals(0, $predictions->homeWins);
        $this->assertEquals(0, $predictions->draws);
    }
}
