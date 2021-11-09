<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Illuminate\Support\Collection;
use Module\Football\TeamFixtureStatistics;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\FixtureStatistic\BallPossesion;
use Module\Football\Contracts\FixtureStatisticInterface;
use Module\Football\Collections\FixtureStatisticsCollection;
use Module\Football\DTO\Team;
use Module\Football\FixtureStatistic\GenericFixtureStatistic;

final class FixtureStatisticsResponseJsonMapper extends Response
{
    /*** A map of the api response key name and fixture statistic type */
    private const TYPE_MAP = [
        'Shots on Goal'     => FixtureStatisticInterface::SHOTS_ON_GOAL,
        'Shots off Goal'    => FixtureStatisticInterface::SHOTS_OFF_GOAL,
        'Shots insidebox'   => FixtureStatisticInterface::SHOTS_INSIDE_BOX,
        'Shots outsidebox'  => FixtureStatisticInterface::SHOTS_OUTSIDE_BOX,
        'Total Shots'       => FixtureStatisticInterface::TOTAL_SHOTS,
        'Blocked Shots'     => FixtureStatisticInterface::BLOCKED_SHOTS,
        'Fouls'             => FixtureStatisticInterface::FOULS,
        'Corner Kicks'      => FixtureStatisticInterface::CORNER_KICKS,
        'Offsides'          => FixtureStatisticInterface::OFFSIDES,
        'Yellow Cards'      => FixtureStatisticInterface::YELLOW_CARDS,
        'Red Cards'         => FixtureStatisticInterface::RED_CARDS,
        'Goalkeeper Saves'  => FixtureStatisticInterface::GOALKEPPER_SAVES,
        'Total passes'      => FixtureStatisticInterface::PASSES,
        'Passes accurate'   => FixtureStatisticInterface::ACCURATE_PASSES,
        'Ball Possession'   => FixtureStatisticInterface::BALL_POSSESION,
    ];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private ?TeamBuilder $teamBuilder = null,
    ) {
        parent::__construct($data);
    }

    public function toDataTransferObject(): TeamFixtureStatistics
    {
        //Statistics that should to mapped to custom types
        $mapTocustomTypes = [
            'Ball Possession',
        ];

        $statistics = collect($this->get('statistics'))
            // remove 'Passes %' statistics bcos its not needed
            ->reject(fn (array $stat): bool => $stat['type'] === 'Passes %')
            ->map(function (array $stat) use ($mapTocustomTypes): FixtureStatisticInterface {
                $statisticName = $stat['type'];

                if (inArray($statisticName, $mapTocustomTypes)) {
                    return $this->mapStatisticToCustomType($stat);
                }

                return new GenericFixtureStatistic(self::TYPE_MAP[$statisticName], (int) $stat['value']);
            })
            ->pipe(fn (Collection $collection) => new FixtureStatisticsCollection($collection->all()));

        return new TeamFixtureStatistics($this->mapTeamToDto(), $statistics);
    }

    private function mapTeamToDto(): Team
    {
        return (new TeamJsonMapper($this->get('team'), $this->teamBuilder))->toDataTransferObject();
    }

    /**
     * @param array<string, mixed> $statistic
     */
    private function mapStatisticToCustomType(array $statistic): FixtureStatisticInterface
    {
        return match ($statistic['type']) {
            'Ball Possession'   => new BallPossesion((int) $statistic['value'])
        };
    }
}
