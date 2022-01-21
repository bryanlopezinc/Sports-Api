<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Contracts;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Prediction;

interface StoreUserPredictionRepositoryInterface
{
    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool;
}
