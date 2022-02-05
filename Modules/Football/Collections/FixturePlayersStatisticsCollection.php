<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Illuminate\Support\Collection;
use Module\Football\DTO\PlayerStatistics;
use Module\Football\ValueObjects\TeamId;

final class FixturePlayersStatisticsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof PlayerStatistics;
    }

    protected function validateItems(): void
    {
        parent::validateItems();

        (new Validators\ValidateFixturePlayersStatistics($this));
    }

    public function forTeam(TeamId $teamId): FixturePlayersStatisticsCollection
    {
        return $this->collection
            ->filter(fn (PlayerStatistics $playerStatistics): bool => $playerStatistics->team()->getId()->equals($teamId))
            ->pipe(fn (Collection $collection) => new FixturePlayersStatisticsCollection($collection->all()));
    }
}
