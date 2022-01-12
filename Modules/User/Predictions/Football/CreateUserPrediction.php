<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Module\User\ValueObjects\UserId;
use Module\User\Dto\Builders\UserBuilder;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Cache\FixturePredictionsResultCacheRepository;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;

final class CreateUserPrediction
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
            UserBuilder::fromAuthUser()->build()->getId(),
            $this->getPredictionFromRequest($request)
        );
    }

    private function getPredictionFromRequest(PredictFixtureRequest $request): Prediction
    {
        $prediction = match ($request->input('prediction')) {
            $request::VALID_PREDICTIONS['1W'] => Prediction::HOME_WIN,
            $request::VALID_PREDICTIONS['2W'] => Prediction::AWAY_WIN,
            $request::VALID_PREDICTIONS['D']  => Prediction::DRAW,
        };

        return new Prediction($prediction);
    }
}
