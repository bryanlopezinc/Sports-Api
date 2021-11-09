<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureEventsValidators;

use Attribute;
use LogicException;
use Module\Football\FixtureEvents\CardEvent;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\Collections\FixtureEventsCollection;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsurePlayersAreNotCardedMoreThanExcpected implements AfterMakingValidatorInterface
{
    /**
     * @param FixtureEventsCollection $collection
     */
    public function validate(Object $collection): void
    {
        //Note !
        //using FixtureEventsCollection to get events that are card events Causes Infinite loop
        //dont move method to collection class.
        $cardEvents = $collection->toLaravelCollection()->whereInstanceOf(CardEvent::class);

        //Ensure Players Not Red Carded more than once.
        $cardEvents
            ->filter(fn (CardEvent $event): bool => $event->isRedCard())
            ->map(fn (CardEvent $event): int => $event->player()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new LogicException('A player cannot be red carded more than once'));
    }
}
