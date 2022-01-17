<?php

declare(strict_types=1);

namespace Module\Football;

use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;

final class DetermineFixtureTimeToLiveInCache
{
    public function for(Fixture $fixture): TimeToLive
    {
        $status = $fixture->status();

        return match (true) {
            $status->isFinished()                    => $this->determineTimeToLiveWhenFixtureIsFinished($fixture),
            $status->isInProgress()                  => TimeToLive::seconds(60),
            $status->isNotStarted()                  => $this->determineTimeToLiveWhenFixtureIsNotStarted($fixture),
            $status->timeIsYetToBeDefined()          => TimeToLive::seconds(600),
            $status->didNotStartForVariousReasons()  => $this->determineTimeToLiveWhenFixtureIsCancelledForVariousReasons($fixture),
            $status->isSuspended()                   => TimeToLive::seconds(600),
            $status->isAbandoned()                   => TimeToLive::seconds(600),
        };
    }

    private function determineTimeToLiveWhenFixtureIsFinished(Fixture $fixture): TimeToLive
    {
        $fixtureIsOlderThanTwoWeeks = now()->diffInWeeks($fixture->date()->toCarbon()) >= 2;

        if ($fixtureIsOlderThanTwoWeeks) {
            return TimeToLive::hours(1);
        }

        return TimeToLive::days(3);
    }

    private function determineTimeToLiveWhenFixtureIsCancelledForVariousReasons(Fixture $fixture): TimeToLive
    {
        $status = $fixture->status();

        $fixtureWasToBePlayedToday = $fixture->date()->toCarbon()->isToday();

        $timeToLive = match (true) {
            $status->isPostponed()     => 10,
            $status->isCancelled()     => 10,
        };

        $seconds = $fixtureWasToBePlayedToday ? minutesUntilTommorow() : $timeToLive;

        return TimeToLive::seconds($seconds);
    }

    private function determineTimeToLiveWhenFixtureIsNotStarted(Fixture $fixture): TimeToLive
    {
        $hoursUntilFixtureKickOff = now()->diffInHours($fixture->date()->toCarbon());

        //if fixture starts within the our cache fixture untill kickOff time
        if ($hoursUntilFixtureKickOff < 1) {
            return TimeToLive::seconds(now()->diffInSeconds($fixture->date()->toCarbon()));
        }

        return TimeToLive::hours(1);
    }
}
