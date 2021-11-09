<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureEventsValidators;

use Attribute;
use LogicException;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\FixtureEvents\SubstitutionEvent;
use Module\Football\Collections\FixtureEventsCollection;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsurePlayersAreSubstitutedOnce implements AfterMakingValidatorInterface
{
    /**
     * @param FixtureEventsCollection $collection
     */
    public function validate(Object $collection): void
    {
        //Note !
        //using FixtureEventsCollection to get events that are substitution events Causes Infinite loop
        //dont move to collection class
        $substitions = $collection->toLaravelCollection()->whereInstanceOf(SubstitutionEvent::class);

        //Ensure players are not subbed off more than once
        $substitions
            ->map(fn (SubstitutionEvent $event): int => $event->playerOut()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new LogicException('A player cannot be substituted off more than once', 422));

        //Ensure players are not subbed on more than once
        $substitions
            ->map(fn (SubstitutionEvent $event): int => $event->playerIn()->getId()->toInt())
            ->duplicates()
            ->whenNotEmpty(fn () => throw new LogicException('A player cannot be substituted on more than once', 423));
    }
}
