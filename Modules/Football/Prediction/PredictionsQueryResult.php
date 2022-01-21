<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use Module\Football\DTO\Fixture;
use Module\Football\Prediction\FixturePredictionsResult;

final class PredictionsQueryResult
{
    public function __construct(public readonly Fixture $fixture, public readonly FixturePredictionsResult $predictions)
    {
    }
}
