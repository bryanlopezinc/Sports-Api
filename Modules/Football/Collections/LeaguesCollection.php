<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\League;
use App\Collections\DtoCollection;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\LeagueId;

/**
 * @template T of League
 */
final class LeaguesCollection extends DtoCollection
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
     * @throws \OutOfBoundsException
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     */
    public function sole(): League
    {
        return $this->soleItem();
    }
}
