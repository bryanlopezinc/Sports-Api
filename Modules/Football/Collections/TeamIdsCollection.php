<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\ValueObjects\TeamId;

final class TeamIdsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof TeamId;
    }

    /**
     * @return array<int>
     */
    public function toIntegerArray(): array
    {
        return $this->collection->map(fn (TeamId $teamId): int => $teamId->toInt())->all();
    }

    public function has(TeamId $teamId): bool
    {
        return $this->collection
            ->filter(fn (TeamId $id): bool => $id->equals($teamId))
            ->isNotEmpty();
    }

    public function except(TeamIdsCollection $ids): TeamIdsCollection
    {
        $ids = $ids->toIntegerArray();

        return $this->collection
            ->reject(fn (TeamId $teamId): bool => inArray($teamId->toInt(), $ids))
            ->pipe(fn (Collection $collection) => new TeamIdsCollection($collection->all()));
    }

    public function unique(): TeamIdsCollection
    {
        return $this->collection
            ->uniqueStrict(fn (TeamId $id) => $id->toInt())
            ->pipe(fn (Collection $collection) => new TeamIdsCollection($collection->all()));
    }
}
