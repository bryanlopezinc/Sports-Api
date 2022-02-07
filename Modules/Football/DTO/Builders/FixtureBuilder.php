<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use Module\Football\DTO\Fixture;
use Module\Football\FixtureReferee as Referee;
use Module\Football\FixturePeriodGoals;
use Module\Football\Venue;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TimeZone;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\ValueObjects\MatchGoals;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\ValueObjects\FixtureStartTime;
use App\DTO\Builder;

final class FixtureBuilder extends Builder
{
    public static function fromFixture(Fixture $fixture): static
    {
        return new static($fixture->toArray());
    }

    public function setLeague(League $league): self
    {
        return $this->set('league', $league);
    }

    public function setAwayTeam(Team $team): self
    {
        return $this->set('away_team', $team);
    }

    public function setHomeTeam(Team $team): self
    {
        return $this->set('home_team', $team);
    }

    public function setVenue(Venue $venue): self
    {
        return $this->set('venue', $venue);
    }

    public function setPenaltyScore(?int $goalsHome, ?int $goalsAway): self
    {
        return $this->setFixturePeriodScore($goalsHome, $goalsAway, 'penalty_score');
    }

    public function setExtraTimeScore(?int $goalsHome, ?int $goalsAway): self
    {
        return $this->setFixturePeriodScore($goalsHome, $goalsAway, 'extra_time_score');
    }

    public function setFullTimeScore(?int $goalsHome, ?int $goalsAway): self
    {
        return $this->setFixturePeriodScore($goalsHome, $goalsAway, 'full_time_score');
    }

    public function setHalfTimeScore(?int $goalsHome, ?int $goalsAway): self
    {
        return $this->setFixturePeriodScore($goalsHome, $goalsAway, 'half_time_score');
    }

    private function setFixturePeriodScore(?int $goalsHome, ?int $goalsAway, string $key): self
    {
        if ($goalsAway === null && $goalsHome === null) {
            return $this->set($key, new FixturePeriodGoals(null, null));
        }

        return $this->set($key, new FixturePeriodGoals(new MatchGoals($goalsHome), new MatchGoals($goalsAway)));
    }

    public function setGoals(?int $goalsHome, ?int $goalsAway): self
    {
        if ($goalsAway === null && $goalsAway === null) {
            return $this->set('score_available', false);
        }

        return $this
            ->set('goals_home', new MatchGoals($goalsHome))
            ->set('goals_away', new MatchGoals($goalsAway))
            ->set('score_available', true);
    }

    public function setFixtureStatus(int $code): self
    {
        return $this->set('status', new FixtureStatus($code));
    }

    public function setWinner(?Team $team): self
    {
        return !is_null($team) ? $this->set('winner', $team)->set('has_winner', true) : $this->set('has_winner', false);
    }

    public function setReferee(?string $name): self
    {
        if ($name === null) {
            return $this->set('referee', new Referee(Referee::NOT_KNOWN));
        }

        return $this->set('referee', new Referee($name));
    }

    public function setTimeElapsed(?int $elapsed): self
    {
        return $this->set('elapsed', $elapsed ? new TimeElapsed($elapsed) : $elapsed);
    }

    public function setTimezone(string $timezoneAbbrv): self
    {
        return $this->set('timezone', new TimeZone($timezoneAbbrv));
    }

    public function setDate(string $date): self
    {
        return $this->set('date', new FixtureStartTime($date));
    }

    public function setId(int $id): self
    {
        return $this->set('id',  new FixtureId($id));
    }

    public function build(): Fixture
    {
        return new Fixture($this->attributes);
    }
}
