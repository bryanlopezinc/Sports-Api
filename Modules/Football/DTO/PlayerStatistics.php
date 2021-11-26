<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\FixturePlayerStatistic\Cards;
use Module\Football\FixturePlayerStatistic\Goals;
use Module\Football\FixturePlayerStatistic\Dribbles;
use Module\Football\FixturePlayerStatistic\GoalKeeperGoalsStat;
use Module\Football\FixturePlayerStatistic\Passes;
use Module\Football\FixturePlayerStatistic\PlayerRating;
use Module\Football\FixturePlayerStatistic\Shots;

final class PlayerStatistics extends DataTransferObject
{
    protected Player $player;
    protected Team $team;
    protected Cards $cards;
    protected Dribbles $dribbles;
    protected Goals $goals;
    protected PlayerRating $rating;
    protected TimeElapsed $minutesPlayed;
    protected Passes $passes;
    protected Shots $shots;
    protected int $offsides;
    protected int $interceptions;
    protected GoalKeeperGoalsStat $goalKeeperGoalsStat;

    /**
     * Exclusively for a goal keeper.
     * Check if player is a goalkeeper (with PlayerPosition) before calling this method.
     */
    public function goalKeeperGoalsStat(): GoalKeeperGoalsStat
    {
        return $this->goalKeeperGoalsStat;
    }

    public function goals(): Goals
    {
        return $this->goals;
    }

    public function interceptions(): int
    {
        return $this->interceptions;
    }

    public function offsides(): int
    {
        return $this->offsides;
    }

    public function shots(): Shots
    {
        return $this->shots;
    }

    public function passes(): Passes
    {
        return $this->passes;
    }

    public function minutesPlayed(): TimeElapsed
    {
        return $this->minutesPlayed;
    }

    public function rating(): PlayerRating
    {
        return $this->rating;
    }

    public function dribbles(): Dribbles
    {
        return $this->dribbles;
    }

    public function cards(): Cards
    {
        return $this->cards;
    }

    public function team(): Team
    {
        return $this->team;
    }

    public function player(): Player
    {
        return $this->player;
    }
}
