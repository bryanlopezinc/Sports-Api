<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use Module\Football\Prediction\Prediction;
use Module\Football\ValueObjects\FixtureId;

final class UserPrediction
{
    public function __construct(
        public readonly FixtureId $fixtureId,
        public readonly Prediction $prediction,
        public readonly PredictionOutcome $outCome
    ) {
    }
}
