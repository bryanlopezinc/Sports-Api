<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\FixtureReferee;
use Module\Football\FixturePeriodGoals;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TimeZone;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\ValueObjects\MatchGoals;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\ValueObjects\FixtureStartTime;
use Module\Football\Attributes\ValidateFixture;
use Module\Football\Venue;

#[ValidateFixture]
final class Fixture extends DataTransferObject
{
    protected FixtureStartTime $date;
    protected TimeZone $timezone;
    protected ?TimeElapsed $elapsed;
    protected FixtureId $id;
    protected FixtureReferee $referee;
    protected Venue $venue;
    protected FixtureStatus $status;
    protected Team $home_team;
    protected Team $away_team;
    protected TeamId $winner_id;
    protected bool $has_winner;
    protected MatchGoals $goals_home;
    protected MatchGoals $goals_away;
    protected bool $score_available;
    protected FixturePeriodGoals $half_time_score;
    protected FixturePeriodGoals $full_time_score;
    protected FixturePeriodGoals $extra_time_score;
    protected FixturePeriodGoals $penalty_score;
    protected League $league;

    public function venue(): Venue
    {
        return $this->venue;
    }

    /**
     * The fixture referee name is not always available for some fixtures. The fixtureReferee 'nameIsAvailable'
     * method should be used to check if fixture referee name is available before calling this method
     */
    public function referee(): FixtureReferee
    {
        return $this->referee;
    }

    public function id(): FixtureId
    {
        return $this->id;
    }

    public function league(): League
    {
        return $this->league;
    }

    /**
     * The fixture score is available if the fixture is started or finished and has coverage.
     * use the scoreIsAvailable method to determine if fixture score is available before calling this
     * method.
     */
    public function goalsHome(): MatchGoals
    {
        return $this->goals_home;
    }

    /**
     * The fixture score is available if the fixture is started or finished and has coverage.
     * use the scoreIsAvailable method to determine if fixture score is available
     */
    public function goalsAway(): MatchGoals
    {
        return $this->goals_away;
    }

    public function scoreIsAvailable(): bool
    {
        return $this->score_available;
    }

    /**
     * The penalty score is not available if fixture is still in first period or second period or extra time.
     * The penalty period score is available if the fixture is concluded (with penalties) or penailty is in progress.
     * The penalty period score is not if there is no coverage for the particular fixture.
     */
    public function getPenaltyPeriodScore(): FixturePeriodGoals
    {
        return $this->penalty_score;
    }

    /**
     * The Extra period score is available if the fixture is concluded (after extra time) or is in extra time.
     * The extratime score is not available if  there is no coverage for the particular fixture.
     */
    public function getExtraTimeScore(): FixturePeriodGoals
    {
        return $this->extra_time_score;
    }

    /**
     * The second period score is available if the fixture is started (or finished).
     * The first period score can still be unavailble for a fixture in progress(or finished)
     * if there is no coverage for the particular fixture or if the fixture is still in the first period.
     */
    public function getSecondPeriodScore(): FixturePeriodGoals
    {
        return $this->full_time_score;
    }

    /**
     * The first period score is available if the fixture is started (or finished).
     * The first period score can still be unavailble for a fixture in progress(or finished)
     * if there is no coverage for the particular fixture.
     */
    public function getFirstPeriodScore(): FixturePeriodGoals
    {
        return $this->half_time_score;
    }

    /**
     * This winner id is only available when the fixture is concluded.
     * it can still be unavailable if the fixture outcome is a draw outcome
     * use the hasWinner to check if fixture has winner before calling this method
     */
    public function winnerId(): TeamId
    {
        return $this->winner_id;
    }

    /**
     * Evaluates to true is fixture is finished and fixture is not a draw outcome
     */
    public function hasWinner(): bool
    {
        return $this->has_winner;
    }

    public function getHomeTeam(): Team
    {
        return $this->home_team;
    }

    public function getAwayTeam(): Team
    {
        return $this->away_team;
    }

    public function date(): FixtureStartTime
    {
        return $this->date;
    }

    public function timezone(): TimeZone
    {
        return $this->timezone;
    }

    public function status(): FixtureStatus
    {
        return $this->status;
    }

    public function timeElapsed(): ?TimeElapsed
    {
        return $this->elapsed;
    }
}
