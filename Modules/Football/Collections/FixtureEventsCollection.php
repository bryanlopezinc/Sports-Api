<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\FixtureEvents\TeamEventInterface;
use Module\Football\Attributes\FixtureEventsValidators\EnsureEventsContainsOneOrTwoTeams;
use Module\Football\Attributes\FixtureEventsValidators\EnsurePlayersAreSubstitutedOnce;
use Module\Football\Attributes\FixtureEventsValidators\EnsurePlayersAreNotCardedMoreThanExcpected;

/**
 * @template T of TeamEventInterface
 */
#[EnsureEventsContainsOneOrTwoTeams]
#[EnsurePlayersAreSubstitutedOnce]
#[EnsurePlayersAreNotCardedMoreThanExcpected]
final class FixtureEventsCollection extends BaseCollection
{
    public function isValid(mixed $event): bool
    {
        return $event instanceof TeamEventInterface;
    }

    public function teams(): TeamsCollection
    {
        return $this->collection
            ->map(fn (TeamEventInterface $event): Team => $event->team())
            ->unique()
            ->pipe(fn (Collection $collection) => new TeamsCollection($collection->all()));
    }
}
