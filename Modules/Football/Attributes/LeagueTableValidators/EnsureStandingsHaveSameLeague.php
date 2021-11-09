<?php

declare(strict_types=1);

namespace Module\Football\Attributes\LeagueTableValidators;

use Attribute;
use Module\Football\DTO\LeagueStanding;
use Module\Football\Collections\LeagueTable;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureStandingsHaveSameLeague implements AfterMakingValidatorInterface
{
    /**
     * @param LeagueTable $leagueTable
     */
    public function validate(Object $leagueTable): void
    {
        $uniqueLeagueIdsCount = $leagueTable
            ->toLaravelCollection()
            ->map(fn (LeagueStanding $standing): int => $standing->getLeague()->getId()->toInt())
            ->unique()
            ->count();

        if ($uniqueLeagueIdsCount !== 1) {
            throw new \InvalidArgumentException('league standing must have same league ids');
        }
    }
}
