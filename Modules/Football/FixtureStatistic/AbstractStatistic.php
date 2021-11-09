<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

use App\ValueObjects\NonNegativeNumber;
use Module\Football\Contracts\FixtureStatisticInterface;

abstract class AbstractStatistic implements FixtureStatisticInterface
{
    public function __construct(protected int $value)
    {
        NonNegativeNumber::check($value);

        $this->validate();
    }

    public function value(): int
    {
        return $this->value;
    }

    protected function validate(): void
    {
        //
    }
}
