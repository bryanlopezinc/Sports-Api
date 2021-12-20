<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Predictions\Football;

use Tests\TestCase;
use Module\User\ValueObjects\UserId;
use Module\User\Factories\UserFactory;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Prediction;
use Module\User\Predictions\Football\PredictionsRepository;
use Module\User\Exceptions\DuplicatePredictionEntryException;
use Module\User\Predictions\Football\Models\Prediction as PredictionModel;

class PredictionsRepositoryTest extends TestCase
{
    public function test_store_prediction(): void
    {
        $repository = new PredictionsRepository;
        $user = UserFactory::new()->create();

        $this->assertTrue($repository->create(new FixtureId(23), new UserId($user->id), new Prediction(Prediction::AWAY_WIN)));
    }

    public function test_will_throw_exception_when_user_already_predicted_fixture(): void
    {
        $this->expectException(DuplicatePredictionEntryException::class);

        $repository = new PredictionsRepository;
        $user = UserFactory::new()->create();

        $repository->create(new FixtureId(215662), new UserId($user->id), new Prediction(Prediction::AWAY_WIN));
        $repository->create(new FixtureId(215662), new UserId($user->id), new Prediction(Prediction::HOME_WIN));
    }

    public function test_will_return_correct_predictions_data(): void
    {
        PredictionModel::truncate();

        $repository = new PredictionsRepository;
        $usersCount = 30;
        $users = UserFactory::new()->count($usersCount)->create();
        $fixtureId = new FixtureId(40);

        foreach ($users->slice(0, 20) as $key => $user) {
            $repository->create($fixtureId, new UserId($user->id), new Prediction(
                $key % 2 === 0 ? Prediction::AWAY_WIN : Prediction::HOME_WIN
            ));
        }

        foreach ($users->slice(20, 10) as $user) {
            $repository->create($fixtureId, new UserId($user->id), new Prediction(Prediction::DRAW));
        }

        $predictions = $repository->fetchPredictionsTotalsFor($fixtureId);

        $this->assertEquals($usersCount, $predictions->total());
        $this->assertEquals(10, $predictions->awayWins());
        $this->assertEquals(10, $predictions->homeWins());
        $this->assertEquals(10, $predictions->draws());
    }

    public function test_will_return_correct_predictions_data_when_empty(): void
    {
        PredictionModel::truncate();

        $repository = new PredictionsRepository;
        $fixtureId = new FixtureId(40);

        $predictions = $repository->fetchPredictionsTotalsFor($fixtureId);

        $this->assertEquals(0, $predictions->total());
        $this->assertEquals(0, $predictions->awayWins());
        $this->assertEquals(0, $predictions->homeWins());
        $this->assertEquals(0, $predictions->draws());
    }
}