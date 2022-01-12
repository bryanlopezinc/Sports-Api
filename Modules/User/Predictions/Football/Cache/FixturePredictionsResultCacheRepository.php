<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Cache;

use App\Utils\TimeToLive;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsResult;

final class FixturePredictionsResultCacheRepository
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->buildkeyFor($fixtureId));
    }

    public function get(FixtureId $fixtureId): FixturePredictionsResult
    {
        return $this->repository->get($this->buildkeyFor($fixtureId), fn () => throw new ItemNotInCacheException());
    }

    public function forgetPredictionFor(FixtureId $fixtureId): bool
    {
        return $this->repository->forget($this->buildkeyFor($fixtureId));
    }

    public function put(FixtureId $fixtureId, FixturePredictionsResult $predictions, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->buildkeyFor($fixtureId), $predictions, $ttl->ttl());
    }

    private function buildkeyFor(FixtureId $fixtureId): string
    {
        return 'fxPredictions:' . $fixtureId->toInt();
    }
}
