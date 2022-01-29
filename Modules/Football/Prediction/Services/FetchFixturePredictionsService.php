<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Services;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Cache\FixturePredictionsResultCacheRepository;
use Module\Football\Prediction\FixturePredictionsResult;
use Module\Football\Prediction\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\Football\Prediction\Prediction;
use Module\User\ValueObjects\UserId;

final class FetchFixturePredictionsService
{
    public function __construct(
        private FetchFixturePredictionsRepositoryInterface $repository,
        private FixturePredictionsResultCacheRepository $cache
    ) {
    }

    public function for(FixtureId $fixtureId): FixturePredictionsResult
    {
        if ($this->cache->has($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $this->cache->put($fixtureId, $predictions = $this->repository->fetchPredictionsResultFor($fixtureId), TimeToLive::hours(1));

        return $predictions;
    }

    public function authUserHasPredictedFixture(FixtureId $fixtureId): bool
    {
        $auth = auth('api');

        if (!$auth->check()) {
            return false;
        }

        return  $this->repository->userHasPredictedFixture(new UserId($auth->id()), $fixtureId);
    }

    public function fetchAuthUserHasPrediction(FixtureId $fixtureId): Prediction
    {
        return  $this->repository->fetchUserPrediction($fixtureId, UserId::fromAuthUser());
    }
}
