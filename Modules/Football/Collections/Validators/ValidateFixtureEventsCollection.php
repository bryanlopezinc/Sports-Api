<?php

declare(strict_types=1);

namespace Module\Football\Collections\Validators;

use Module\Football\Collections\FixtureEventsCollection;
use Module\Football\FixtureEvents\CardEvent;
use Module\Football\FixtureEvents\SubstitutionEvent;

final class ValidateFixtureEventsCollection
{
    public function __construct(FixtureEventsCollection $events)
    {
        $this->validate($events);
    }

    private function validate(FixtureEventsCollection $collection): void
    {
        $this->ensureContainsOneOrTwoTeams($collection);
        $this->ensureCardsDataIsValid($collection);
        $this->ensureSubstitutionDataIsValid($collection);
    }

    private function ensureContainsOneOrTwoTeams(FixtureEventsCollection $events): void
    {
        if ($events->teams()->count() > 2) {
            throw new \LogicException('Fixture events can contain only one or two teams');
        }
    }

    private function ensureCardsDataIsValid(FixtureEventsCollection $events): void
    {
        $cardEvents = $events->toLaravelCollection()->whereInstanceOf(CardEvent::class);

        //Ensure Players Not Red Carded more than once.
        $cardEvents
            ->filter(fn (CardEvent $event): bool => $event->isRedCard())
            ->map(fn (CardEvent $event): int => $event->player()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new \LogicException('A player cannot be red carded more than once'));
    }

    private function ensureSubstitutionDataIsValid(FixtureEventsCollection $collection): void
    {
        $substitions = $collection->toLaravelCollection()->whereInstanceOf(SubstitutionEvent::class);

        //Ensure players are not subbed off more than once
        $substitions
            ->map(fn (SubstitutionEvent $event): int => $event->playerOut()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new \LogicException('A player cannot be substituted off more than once', 422));

        //Ensure players are not subbed on more than once
        $substitions
            ->map(fn (SubstitutionEvent $event): int => $event->playerIn()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new \LogicException('A player cannot be substituted on more than once', 423));
    }
}
