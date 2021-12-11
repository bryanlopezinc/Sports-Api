<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use App\ValueObjects\NonNegativeNumber;

final class FixturePredictionsTotals
{
    public function __construct(private int $homeWins, private int $awayWins, private int $draws, private int $total)
    {
        NonNegativeNumber::check(func_get_args());

        $expectedTotal = $homeWins + $awayWins + $draws;

        if ($expectedTotal !== $total) {
            throw new \LogicException(
                sprintf('The given fixture predictions is invalid. Expected %s as total got %s', $expectedTotal, $total),
                988
            );
        }
    }

    public function homeWins(): int
    {
        return $this->homeWins;
    }

    public function awayWins(): int
    {
        return $this->awayWins;
    }

    public function draws(): int
    {
        return $this->draws;
    }

    public function total(): int
    {
        return $this->total;
    }
}
