<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Listeners;

use Module\User\Predictions\Football\FixturePredictedEvent;
use Module\User\Predictions\Football\Cache\FixturePredictionsCacheRepository;
use Module\User\Predictions\Football\Cache\FixturePredictionsResultCacheRepository;

final class RemoveUserPredictionRecord
{
    public bool $afterCommit = true;

    public function __construct(
        private FixturePredictionsCacheRepository $repository,
        private FixturePredictionsResultCacheRepository $fixturePredictionsCache
    ) {
    }

    public function handle(FixturePredictedEvent $event): void
    {
        $this->repository->forget($event->userId, $event->fixtureId);

        if ($this->fixturePredictionsCache->has($event->fixtureId)) {
            $this->fixturePredictionsCache->forgetPredictionFor($event->fixtureId);
        }
    }
}
