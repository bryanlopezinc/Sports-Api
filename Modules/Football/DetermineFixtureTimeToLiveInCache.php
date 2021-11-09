<?php

declare(strict_types=1);

namespace Module\Football;

use App\Utils\Config;
use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;

final class DetermineFixtureTimeToLiveInCache
{
    public function for(Fixture $fixture): TimeToLive
    {
        $status = $fixture->status();

        return match (true) {
            $status->isFinished()                    => $this->determineTimeToLiveWhenFixtureIsFinished($fixture),
            $status->isInProgress()                  => TimeToLive::seconds(Config::get('football.cache.fixtures.ttl.inProgress')),
            $status->isNotStarted()                  => $this->determineTimeToLiveWhenFixtureIsNotStarted($fixture),
            $status->timeIsYetToBeDefined()          => TimeToLive::seconds(Config::get('football.cache.fixtures.ttl.whenIsTBD')),
            $status->didNotStartForVariousReasons()  => $this->determineTimeToLiveWhenFixtureIsCancelledForVariousReasons($fixture),
            $status->isSuspended()                   => TimeToLive::seconds(Config::get('football.cache.fixtures.ttl.suspended')),
            $status->isAbandoned()                   => TimeToLive::seconds(Config::get('football.cache.fixtures.ttl.abandoned')),
        };
    }

    private function determineTimeToLiveWhenFixtureIsFinished(Fixture $fixture): TimeToLive
    {
        $fixtureIsOlderThanTwoWeeks = now()->diffInWeeks($fixture->date()->toCarbon()) >= 2;

        if ($fixtureIsOlderThanTwoWeeks) {
            return TimeToLive::hours(1);
        }

        return TimeToLive::days(Config::get('football.cache.fixtures.ttl.finished'));
    }

    private function determineTimeToLiveWhenFixtureIsCancelledForVariousReasons(Fixture $fixture): TimeToLive
    {
        $status = $fixture->status();

        $fixtureWasToBePlayedToday = $fixture->date()->toCarbon()->isToday();

        $timeToLive = match (true) {
            $status->isPostponed()     => Config::get('football.cache.fixtures.ttl.postponed'),
            $status->isCancelled()     => Config::get('football.cache.fixtures.ttl.cancelled'),
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
