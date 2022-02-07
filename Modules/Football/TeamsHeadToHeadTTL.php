<?php

declare(strict_types=1);

namespace Module\Football;

use App\Utils\TimeToLive;
use Module\Football\Collections\FixturesCollection;

final class TeamsHeadToHeadTTL
{
    public function __invoke(FixturesCollection $collection): TimeToLive
    {
        if ($collection->anyFixtureIsInProgress()) {
            return TimeToLive::seconds(120);
        }

        if ($collection->allFixturesArefinished()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        if (!$collection->hasUpcomingFixture()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        return TimeToLive::seconds(
            now()->diffInSeconds($collection->nextUpcomingFixture()->date()->toCarbon()->toDateTimeString())
        );
    }
}
