<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\FixtureEvents\EventInterface;

final class FixtureEventsCollection extends BaseCollection
{
    public function isValid(mixed $event): bool
    {
        return $event instanceof EventInterface;
    }

    protected function validateItems(): void
    {
        parent::validateItems();

        (new Validators\ValidateFixtureEventsCollection($this));
    }

    public function teams(): TeamsCollection
    {
        return $this->collection
            ->map(fn (EventInterface $event): Team => $event->team())
            ->unique()
            ->pipe(fn (Collection $collection) => new TeamsCollection($collection->all()));
    }
}
