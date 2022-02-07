<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\PositiveNumber;

final class Cards
{
    public function __construct(private int $redCards, private int $yellowCards)
    {
        PositiveNumber::check(func_get_args());

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->redCards > 1) {
            throw new \InvalidArgumentException('Player cannot receive more than one red card', 234);
        }

        if ($this->yellowCards > 2) {
            throw new \InvalidArgumentException('Player cannot receive more than two yellow cards', 235);
        }
    }

    public function reds(): int
    {
        return $this->redCards;
    }

    public function yellows(): int
    {
        return $this->yellowCards;
    }

    public function total(): int
    {
        return $this->redCards + $this->yellowCards;
    }
}
