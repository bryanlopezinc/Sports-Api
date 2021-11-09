<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\StandingData;
use Module\Football\DTO\LeagueStanding;
use Module\Football\ValueObjects\TeamForm;
use Module\Football\DTO\Builders\LeagueStandingBuilder;

final class LeagueStandingFactory extends Factory
{
    protected string $dtoClass = LeagueStanding::class;

    public function definition()
    {
        $record = new StandingData(StandingDataFactory::new()->makeAttributes());

        return (new LeagueStandingBuilder)
            ->setTeamRank(1)
            ->setTeam(TeamFactory::new()->toDto())
            ->setLeague(LeagueFactory::new()->toDto())
            ->setTeamPoints(
                ($record->getTotalWins() * 3) + $record->getTotalDraws()
            )
            ->setGoalsDiff(rand(5, 20))
            ->setForm([TeamForm::WIN, TeamForm::LOOSE, TeamForm::DRAW])
            ->setPositionDescription($this->faker->sentence)
            ->setStandingRecord(StandingDataFactory::new()->toDto())
            ->setAwayRecord(StandingDataFactory::new()->toDto())
            ->setHomeRecord(StandingDataFactory::new()->toDto())
            ->toArray();
    }

    /**
     * The callable accepts a LeagueStandingBuilder instance
     * and should return a LeagueStandingBuilder instance
     */
    public function withState(callable $state): self
    {
        return $this->state(function (array $attributes) use ($state) {
            return $state(new LeagueStandingBuilder($attributes))->toArray();
        });
    }

    public function toDto(): LeagueStanding
    {
        return $this->mapToDto();
    }
}
