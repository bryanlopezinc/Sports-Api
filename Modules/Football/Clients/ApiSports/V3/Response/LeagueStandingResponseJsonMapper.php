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

final class LeagueStandingResponseJsonMapper
{
    public function __construct(
        private TeamBuilder $teamBuilder = new TeamBuilder,
        private LeagueStandingBuilder $builder = new LeagueStandingBuilder,
        private StandingDataBuilder $standingDataBuilder = new StandingDataBuilder
    ) {
    }

    public function __invoke(array $data): LeagueStanding
    {
        return $this->builder
            ->setForm($this->convertTeamForm($data['form']))
            ->setTeamRank($data['rank'])
            ->setTeam($this->mapResponseIntoTeamDto($data['team']))
            ->setTeamPoints($data['points'])
            ->setGoalsDiff($data['goalsDiff'])
            ->setPositionDescription($data['description'])
            ->setStandingRecord($this->mapResponseIntoStandingDto($data['all']))
            ->setHomeRecord($this->mapResponseIntoStandingDto($data['home']))
            ->setAwayRecord($this->mapResponseIntoStandingDto($data['away']))
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

    private function mapResponseIntoTeamDto(array $data): Team
    {
        return (new TeamJsonMapper($data, $this->teamBuilder))->toDataTransferObject();
    }
}
