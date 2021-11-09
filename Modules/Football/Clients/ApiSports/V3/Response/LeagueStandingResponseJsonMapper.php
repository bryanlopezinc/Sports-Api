<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\StandingData;
use Module\Football\DTO\LeagueStanding;
use Module\Football\ValueObjects\TeamForm;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\StandingDataBuilder;
use Module\Football\DTO\Builders\LeagueStandingBuilder;

final class LeagueStandingResponseJsonMapper extends Response
{
    private LeagueStandingBuilder $builder;
    private StandingDataBuilder $standingDataBuilder;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private ?TeamBuilder $teamBuilder = null,
        LeagueStandingBuilder $builder = null,
        StandingDataBuilder $standingDataBuilder = null
    ) {
        parent::__construct($data);

        $this->builder = $builder ?: new LeagueStandingBuilder();
        $this->standingDataBuilder = $standingDataBuilder ?: new StandingDataBuilder();
    }

    public function toDataTransferObject(): LeagueStanding
    {
        return $this->builder
            ->setForm($this->convertTeamForm($this->get('form')))
            ->setTeamRank($this->get('rank'))
            ->setTeam($this->mapResponseIntoTeamDto($this->get('team')))
            ->setTeamPoints($this->get('points'))
            ->setGoalsDiff($this->get('goalsDiff'))
            ->setPositionDescription($this->get('description'))
            ->setStandingRecord($this->mapResponseIntoStandingDto($this->get('all')))
            ->setHomeRecord($this->mapResponseIntoStandingDto($this->get('home')))
            ->setAwayRecord($this->mapResponseIntoStandingDto($this->get('away')))
            ->build();
    }

    /**
     * @return string[]
     */
    private function convertTeamForm(string $form): array
    {
        return array_map(fn (string $form): string => match ($form) {
            'W'    => TeamForm::WIN,
            'L'    => TeamForm::LOOSE,
            'D'    => TeamForm::DRAW,
        }, str_split($form));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapResponseIntoStandingDto(array $data): StandingData
    {
        $standingData = new Response($data);

        return $this->standingDataBuilder
            ->setMatchesPlayed($standingData->get('played'))
            ->setMatchedWon($standingData->get('win'))
            ->setMatchesLost($standingData->get('lose'))
            ->setMatchesDrawn($standingData->get('draw'))
            ->setGoalsFound($standingData->get('goals.for'))
            ->setGoalsAgainst($standingData->get('goals.against'))
            ->build();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapResponseIntoTeamDto(array $data): Team
    {
        return (new TeamJsonMapper($data, $this->teamBuilder))->toDataTransferObject();
    }
}
