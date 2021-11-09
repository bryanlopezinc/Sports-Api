<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureEventsValidators;

use Attribute;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\Collections\FixtureEventsCollection;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureEventsContainsOneOrTwoTeams implements AfterMakingValidatorInterface
{
    /**
     * @param FixtureEventsCollection $fixtureEventsCollection
     */
    public function validate(Object $fixtureEventsCollection): void
    {
        if ($fixtureEventsCollection->teams()->count() > 2) {
            throw new \LogicException('Fixture events can contain only one or two teams');
        }
    }
}
