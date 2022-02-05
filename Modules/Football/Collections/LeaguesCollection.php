<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\LeagueId;

final class LeaguesCollection extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof League;
    }

    public function pluckIds(): LeagueIdsCollection
    {
        return $this->collection
            ->map(fn (League $league): LeagueId => $league->getId())
            ->pipe(fn (Collection $collection) => new LeagueIdsCollection($collection->all()));
    }

    public function merge(mixed $values): self
    {
        return new self(collect($values)->merge($this->collection->all()));
    }

    /**
     * @throws \Illuminate\Support\ItemNotFoundException
     * @throws \Illuminate\Support\MultipleItemsFoundException
     */
    public function sole(): League
    {
        return $this->soleItem();
    }
}
