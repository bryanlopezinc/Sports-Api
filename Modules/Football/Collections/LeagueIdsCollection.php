<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\ValueObjects\LeagueId;

final class LeagueIdsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof LeagueId;
    }

    /**
     * @return array<int>
     */
    public function toIntegerArray(): array
    {
        return $this->collection->map(fn (LeagueId $id): int => $id->toInt())->all();
    }

    public function except(LeagueIdsCollection $ids): LeagueIdsCollection
    {
        $ids = $ids->toIntegerArray();

        return $this->collection
            ->reject(fn (LeagueId $leagueId): bool => inArray($leagueId->toInt(), $ids))
            ->pipe(fn (Collection $collection) => new LeagueIdsCollection($collection->all()));
    }

    public function unique(): LeagueIdsCollection
    {
        return $this->collection
            ->uniqueStrict(fn (LeagueId $id) => $id->toInt())
            ->pipe(fn (Collection $collection) => new LeagueIdsCollection($collection->all()));
    }
}
