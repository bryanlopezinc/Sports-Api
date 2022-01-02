<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Contracts;

use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsTotals;
use Module\User\ValueObjects\UserId;

interface FetchFixturePredictionsRepositoryInterface
{
    public function fetchPredictionsTotalsFor(FixtureId $fixtureId): FixturePredictionsTotals;

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool;
}
