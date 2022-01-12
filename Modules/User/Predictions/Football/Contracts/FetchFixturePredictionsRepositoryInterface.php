<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Contracts;

use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsResult;
use Module\User\ValueObjects\UserId;

interface FetchFixturePredictionsRepositoryInterface
{
    public function fetchPredictionsResultFor(FixtureId $fixtureId): FixturePredictionsResult;

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool;
}
