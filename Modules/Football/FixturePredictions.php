<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\Fixture;
use Module\User\Predictions\Football\FixturePredictionsResult as Predictions;

final class FixturePredictions
{
    public function __construct(private Fixture $fixture, private Predictions $predictions)
    {
    }

    public function predictions(): Predictions
    {
        return $this->predictions;
    }

    public function fixture(): Fixture
    {
        return $this->fixture;
    }
}
