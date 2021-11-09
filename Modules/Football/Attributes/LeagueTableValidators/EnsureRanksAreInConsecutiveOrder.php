<?php

declare(strict_types=1);

namespace Module\Football\Attributes\LeagueTableValidators;

use Attribute;
use Module\Football\DTO\LeagueStanding;
use Module\Football\Collections\LeagueTable;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureRanksAreInConsecutiveOrder implements AfterMakingValidatorInterface
{
    /**
     * @param LeagueTable $leagueTable
     */
    public function validate(Object $leagueTable): void
    {
        $ranks = $leagueTable
            ->toLaravelCollection()
            ->map(fn (LeagueStanding $standing): int => $standing->getRank())
            ->sort()
            ->all();

        if (range(1, $leagueTable->count()) !== $ranks) {
            throw new \LogicException('league ranks must be in consecutive order');
        }
    }
}
