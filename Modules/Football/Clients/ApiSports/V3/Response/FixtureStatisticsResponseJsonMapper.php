<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\FixtureStatistics;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\FixtureStatisticsBuilder;

final class FixtureStatisticsResponseJsonMapper
{
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private ?TeamBuilder $teamBuilder = null,
        private ?FixtureStatisticsBuilder $builder = null
    ) {

        $this->response = new Response($data);
        $this->builder = $this->builder ?: new FixtureStatisticsBuilder;
    }

    public function toDataTransferObject(): FixtureStatistics
    {
        $statistics = collect($this->response->get('statistics'));

        $this->mapTeamToDto();

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

    private function mapTeamToDto(): void
    {
        $this->builder->team(
            (new TeamJsonMapper($this->response->get('team'), $this->teamBuilder))->toDataTransferObject()
        );
    }
}
