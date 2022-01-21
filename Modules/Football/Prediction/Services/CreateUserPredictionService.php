<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Services;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Cache\FixturePredictionsResultCacheRepository;
use Module\Football\Prediction\FixturePredictedEvent;
use Module\Football\Prediction\PredictFixtureRequest;
use Module\Football\Prediction\Prediction;
use Module\Football\Prediction\Contracts\StoreUserPredictionRepositoryInterface;

final class CreateUserPredictionService
{
    public function __construct(
        private FixturePredictionsResultCacheRepository $cache,
        private StoreUserPredictionRepositoryInterface $repository,
    ) {
    }

    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool
    {
        $result = $this->repository->create($fixtureId, $userId, $prediction);

        FixturePredictedEvent::dispatch($fixtureId, $userId);

        return $result;
    }

    public function FromRequest(PredictFixtureRequest $request): bool
    {
        return $this->create(
            FixtureId::fromRequest($request, 'fixture_id'),
            UserId::fromAuthUser(),
            Prediction::fromRequest($request, 'prediction')
        );
    }
}
