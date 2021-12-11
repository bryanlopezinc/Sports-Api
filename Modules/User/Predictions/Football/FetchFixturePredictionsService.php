<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsCacheRepository;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

final class FetchFixturePredictionsService
{
    public function __construct(
        private FetchFixturePredictionsRepositoryInterface $repository,
        private FixturePredictionsCacheRepository $cache
    ) {
    }

    public function for(FixtureId $fixtureId): FixturePredictionsTotals
    {
        if ($this->cache->has($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $this->cache->put($fixtureId, $predictions = $this->repository->fetchPredictionsTotalsFor($fixtureId), TimeToLive::hours(1));

        return $predictions;
    }
}
