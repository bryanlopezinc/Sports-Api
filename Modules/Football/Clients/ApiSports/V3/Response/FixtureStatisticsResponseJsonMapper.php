<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\FixtureStatistics;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\FixtureStatisticsBuilder;

final class FixtureStatisticsResponseJsonMapper
{
    public function __construct(
        private TeamBuilder $teamBuilder = new TeamBuilder,
        private FixtureStatisticsBuilder $builder = new FixtureStatisticsBuilder
    ) {
    }

    public function __invoke(array $data): FixtureStatistics
    {
        $statistics = collect($data['statistics']);

        $this->mapTeamToDto($data);

        $getValueForType = fn (string $type) => $statistics->where('type', $type)->first()['value'];

        return $this->builder
            ->shotsOnGoal((int) $getValueForType('Shots on Goal'))
            ->accuratePasses((int) $getValueForType('Passes accurate'))
            ->blockedShots((int) $getValueForType('Blocked Shots'))
            ->shotsInsideBox((int) $getValueForType('Shots insidebox'))
            ->cornerKicks((int) $getValueForType('Corner Kicks'))
            ->fouls((int) $getValueForType('Fouls'))
            ->goalKeeperSaves((int) $getValueForType('Goalkeeper Saves'))
            ->offsides((int) $getValueForType('Offsides'))
            ->passes((int) $getValueForType('Total passes'))
            ->possession(intval((string) $getValueForType('Ball Possession')))
            ->redCards((int) $getValueForType('Red Cards'))
            ->shotsOffGoal((int) $getValueForType('Shots off Goal'))
            ->shotsOutsideBox((int) $getValueForType('Shots outsidebox'))
            ->totalShots((int) $getValueForType('Total Shots'))
            ->yellowCards((int) $getValueForType('Yellow Cards'))
            ->build();
    }

    private function mapTeamToDto(array $data): void
    {
        $this->builder->team(
            (new TeamJsonMapper($data['team'], $this->teamBuilder))->toDataTransferObject()
        );
    }
}
