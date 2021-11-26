<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Team;
use Module\Football\DTO\Player;
use Module\Football\DTO\PlayerStatistics;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\FixturePlayerStatistic\Cards;
use Module\Football\FixturePlayerStatistic\Goals;
use Module\Football\FixturePlayerStatistic\Dribbles;
use Module\Football\FixturePlayerStatistic\GoalKeeperGoalsStat;
use Module\Football\FixturePlayerStatistic\Passes;
use Module\Football\FixturePlayerStatistic\PlayerRating;
use Module\Football\FixturePlayerStatistic\Shots;

final class PlayerStatisticBuilder extends Builder
{
    public function goalKeeperGoalStat(int $conceeded, int $saves): self
    {
        return $this->set('goalKeeperGoalsStat', new GoalKeeperGoalsStat($conceeded, $saves));
    }

    public function interceptions(int $total): self
    {
        return $this->set('interceptions', $total);
    }

    public function offsides(int $total): self
    {
        return $this->set('offsides', $total);
    }

    public function shots(int $onTaget, int $total): self
    {
        return $this->set('shots', new Shots($onTaget, $total));
    }

    public function passes(int $keyPasses, int $total, int $accuracy): self
    {
        return $this->set('passes', new Passes($keyPasses, $total, $accuracy));
    }

    public function minutesPlayed(int $minutes): self
    {
        return $this->set('minutesPlayed', new TimeElapsed($minutes));
    }

    public function rating(float $rating): self
    {
        return $this->set('rating', new PlayerRating($rating));
    }

    public function goals(int $goals, int $assists): self
    {
        return $this->set('goals', new Goals($goals, $assists));
    }

    public function dribbles(int $attempts, int $uccessful, int $timeDribbledPast): self
    {
        return $this->set('dribbles', new Dribbles($attempts, $uccessful, $timeDribbledPast));
    }

    public function cards(int $reds, int $yellows): self
    {
        return $this->set('cards', new Cards($reds, $yellows));
    }

    public function team(Team $team): self
    {
        return $this->set('team', $team);
    }

    public function player(Player $player): self
    {
        return $this->set('player', $player);
    }

    public function build(): PlayerStatistics
    {
        return new PlayerStatistics($this->toArray());
    }
}
