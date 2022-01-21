<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use App\ValueObjects\NonNegativeNumber;

final class FixturePredictionsResult
{
    public function __construct(
        public readonly int $homeWins,
        public readonly int $awayWins,
        public readonly int $draws,
        public readonly int $total
    ) {
        NonNegativeNumber::check(func_get_args());

        $expectedTotal = $homeWins + $awayWins + $draws;

        if ($expectedTotal !== $total) {
            throw new \LogicException(
                sprintf('The given fixture predictions is invalid. Expected %s as total got %s', $expectedTotal, $total),
                988
            );
        }
    }
}
