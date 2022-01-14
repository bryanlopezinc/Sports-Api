<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Cache;

use Module\User\ValueObjects\UserId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Prediction;
use Module\User\Predictions\Football\FixturePredictionsResult;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface as PredictionsRepositoryInterface;

final class UsersPredictionsCacheRepository implements PredictionsRepositoryInterface
{
    public function __construct(private PredictionsRepositoryInterface $predictionsRepository, private Repository $repository)
    {
    }

    public function fetchUserPrediction(FixtureId $fixtureId, UserId $userId): Prediction
    {
        /**
         * Array of fixtures ids that have been predicted by different users and their corresponding users predictions.
         * Each value in the array is represented as
         * [
         *      fixtureId (int) => [
         *          userId (int)        => prediction
         *          AnotherUserId (int) => prediction
         *          AnotherUserId (int) => prediction
         *          ...etc
         *      ],
         *      ...etc
         * ]
         *
         * @var array<int,array<int,prediction>>
         */
        $storage = $this->repository->get($key = 'Fixture:users:predictions', []);

        $prediction = $storage[$fixtureId->toInt()][$userId->toInt()] ?? null;

        if ($prediction !== null) {
            return $prediction;
        }

        $storage[$fixtureId->toInt()][$userId->toInt()] = $userPrediction = $this->predictionsRepository->fetchUserPrediction($fixtureId, $userId);

        $this->repository->put($key, $storage, now()->addDay());

        return $userPrediction;
    }

    public function fetchPredictionsResultFor(FixtureId $fixtureId): FixturePredictionsResult
    {
        return $this->predictionsRepository->fetchPredictionsResultFor($fixtureId);
    }

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool
    {
        return $this->predictionsRepository->userHasPredictedFixture($userId, $fixtureId);
    }
}
