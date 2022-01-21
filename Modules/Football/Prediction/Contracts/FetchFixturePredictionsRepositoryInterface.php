<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Contracts;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\FixturePredictionsResult;
use Module\Football\Prediction\Prediction;
use Module\User\ValueObjects\UserId; 

interface FetchFixturePredictionsRepositoryInterface
{
    public function fetchPredictionsResultFor(FixtureId $fixtureId): FixturePredictionsResult;

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool;

    /**
     * Get user prediction for a particular fixture.
     */
    public function fetchUserPrediction(FixtureId $fixtureId, UserId $userId): Prediction;
}
