<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\ValueObjects\FixtureId;

final class FixtureIdsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof FixtureId;
    }

    public function except(FixtureIdsCollection $ids): FixtureIdsCollection
    {
        $ids = array_map(fn (FixtureId $fixtureId) => $fixtureId->toInt(), $ids->toArray());

        return $this->collection
            ->reject(fn (FixtureId $fixtureId): bool => inArray($fixtureId->toInt(), $ids))
            ->pipe(fn (Collection $collection) => new FixtureIdsCollection($collection->all()));
    }

    public function unique(): FixtureIdsCollection
    {
        return $this->collection
            ->uniqueStrict(fn (FixtureId $id) => $id->toInt())
            ->pipe(fn (Collection $collection) => new FixtureIdsCollection($collection->all()));
    }
}
