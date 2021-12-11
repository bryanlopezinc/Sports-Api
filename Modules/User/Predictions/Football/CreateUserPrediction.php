<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Module\User\ValueObjects\UserId;
use App\Exceptions\Http\HttpException;
use Module\User\Dto\Builders\UserBuilder;
use Module\Football\ValueObjects\FixtureId;
use Symfony\Component\HttpFoundation\Response;
use Module\User\Exceptions\DuplicatePredictionEntryException;
use Module\User\Predictions\Football\FixturePredictionsCacheRepository;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;

final class CreateUserPrediction
{
    public function __construct(
        private FixturePredictionsCacheRepository $cache,
        private StoreUserPredictionRepositoryInterface $repository,
    ) {
    }

    /**
     * @throws DuplicatePredictionEntryException
     */
    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool
    {
        $result = $this->repository->create($fixtureId, $userId, $prediction);

        //Remove predictions in cache to avoid stale data
        if ($this->cache->has($fixtureId)) {
            $this->cache->forgetPredictionFor($fixtureId);
        }

        return $result;
    }

    public function FromRequest(PredictFixtureRequest $request): bool
    {
        try {
            return $this->create(
                FixtureId::fromRequest($request, 'fixture_id'),
                UserBuilder::fromAuthUser()->build()->getId(),
                $this->getPredictionFromRequest($request)
            );
        } catch (DuplicatePredictionEntryException) {
            throw new HttpException(Response::HTTP_CONFLICT, 'User can only predict a fixture once');
        }
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
