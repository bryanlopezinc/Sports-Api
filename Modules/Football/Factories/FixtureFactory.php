<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\Team;
use Module\Football\DTO\Venue;
use Module\Football\DTO\League;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\Collections\FixturesCollection;
use Module\Football\DTO\Builders\FixtureBuilder;
use Module\Football\ValueObjects\TeamId;

final class FixtureFactory extends Factory
{
    protected string $dtoClass = Fixture::class;

    public function definition()
    {
        return (new FixtureBuilder)
            ->setId($this->getIncrementingId())
            ->setGoals(4, 2)
            ->setHalfTimeScore(2, 1)
            ->setFullTimeScore(2, 1)
            ->setReferee($this->faker->name)
            ->setExtraTimeScore(null, null)
            ->setPenaltyScore(null, null)
            ->setTimeElapsed(TimeElapsed::END_FULL_TIME)
            ->setDate((string) now()->subMinutes(105)->toDateTimeString())
            ->setTimezone('utc')
            ->setFixtureStatus(FixtureStatus::FULL_TIME)
            ->setHomeTeam($homeTeam = new Team(TeamFactory::new()->makeAttributes()))
            ->setAwayTeam(new Team(TeamFactory::new()->makeAttributes()))
            ->setLeague(new League(LeagueFactory::new()->makeAttributes()))
            ->setVenue(new Venue(VenueFactory::new()->makeAttributes()))
            ->setVenueInfoIsAvailable(true)
            ->setWinnerId($homeTeam->getId()->toInt())
            ->toArray();
    }

    public function firstPeriod(): self
    {
        return $this->withState(function (FixtureBuilder $builder) {
            return $builder
                ->setGoals(1, 1)
                ->setHalfTimeScore(1, 1)
                ->setFullTimeScore(null, null)
                ->setExtraTimeScore(null, null)
                ->setPenaltyScore(null, null)
                ->setTimeElapsed(TimeElapsed::HALF_TIME)
                ->setFixtureStatus(FixtureStatus::HALF_TIME)
                ->setWinnerId(null);
        });
    }

    /**
     * The callable accepts a FixtureBuilder instance and should return a fixture builder instance
     */
    public function withState(callable $state): self
    {
        return parent::state(function (array $attributes) use ($state) {
            return $state(new FixtureBuilder($attributes))->toArray();
        });
    }

    public function homeTeam(Team $team): self
    {
        return $this->withState(fn (FixtureBuilder $b) => $b->setHomeTeam($team));
    }

    public function winnerId(TeamId $teamId): self
    {
        return $this->withState(fn (FixtureBuilder $b) => $b->setWinnerId($teamId->toInt()));
    }

    public function awayTeam(Team $team): self
    {
        return $this->withState(fn (FixtureBuilder $b) => $b->setAwayTeam($team));
    }

    public function toCollection(): FixturesCollection
    {
        return $this->mapToCollection(FixturesCollection::class);
    }

    public function toDto(): Fixture
    {
        return $this->mapToDto();
    }
}
