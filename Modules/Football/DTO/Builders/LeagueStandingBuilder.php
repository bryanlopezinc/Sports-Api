<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use Module\Football\DTO\StandingData;
use Module\Football\DTO\LeagueStanding;
use Module\Football\ValueObjects\TeamForm;

final class LeagueStandingBuilder extends Builder
{
    public static function fromStanding(LeagueStanding $standing): self
    {
        return new self($standing->toArray());
    }

    public function setTeam(Team $team): self
    {
        return $this->set('team', $team);
    }

    public function setLeague(League $league): self
    {
        return $this->set('league', $league);
    }

    /**
     * @param array<string> $form
     */
    public function setForm(array $form): self
    {
        return $this->set('form', new TeamForm($form));
    }

    public function setTeamRank(int $rank): self
    {
        return $this->set('rank', $rank);
    }

    public function setStandingRecord(StandingData $standingData): self
    {
        return $this->set('allData', $standingData);
    }

    public function setHomeRecord(StandingData $standingData): self
    {
        return $this->set('home_record', $standingData);
    }

    public function setAwayRecord(StandingData $standingData): self
    {
        return $this->set('away_record', $standingData);
    }

    public function setTeamPoints(int $points): self
    {
        return $this->set('points', $points);
    }

    public function setGoalsDiff(int $goalsDiff): self
    {
        return $this->set('goalsDiff', $goalsDiff);
    }

    public function setPositionDescription(?string $description): self
    {
        if ($description === null) {
            return $this->set('hasDescription', false);
        }

        return $this->set('description', $description)->set('hasDescription', true);
    }

    public function build(): LeagueStanding
    {
        return new LeagueStanding($this->toArray());
    }
}
