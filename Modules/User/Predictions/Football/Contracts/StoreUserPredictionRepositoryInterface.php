<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Contracts;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Prediction;

interface StoreUserPredictionRepositoryInterface
{
    /**
     * @throws \Module\User\Exceptions\DuplicatePredictionEntryException
     */
    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool;
}
