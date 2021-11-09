<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\Contracts\FixtureStatisticInterface;

/**
 * @template T of FixtureStatisticInterface
 */
final class FixtureStatisticsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof FixtureStatisticInterface;
    }

    public function hasBallPossesion(): bool
    {
        return $this->collection
            ->filter(fn (FixtureStatisticInterface $stat) => $stat->name() === FixtureStatisticInterface::BALL_POSSESION)
            ->isNotEmpty();
    }

    public function ballPossesion(): FixtureStatisticInterface
    {
        return $this->collection
            ->filter(fn (FixtureStatisticInterface $stat) => $stat->name() === FixtureStatisticInterface::BALL_POSSESION)
            ->sole();
    }
}
