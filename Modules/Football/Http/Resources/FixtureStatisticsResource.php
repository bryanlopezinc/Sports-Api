<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\TeamFixtureStatistics;
use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Contracts\FixtureStatisticInterface;
use Module\Football\Collections\FixtureStatisticsCollection;

final class FixtureStatisticsResource extends JsonResource
{
    public function __construct(private FixtureStatistics $statistics)
    {
        parent::__construct($statistics);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'             => 'football_fixture_statistics',
            'fixture_id'       => FixtureId::fromRequest($request)->toInt(),
            'stats'            => collect([$this->statistics->teamOne(), $this->statistics->teamTwo()])->map(function (TeamFixtureStatistics $stat) {
                return [
                    'team'      => new TeamResource($stat->team()),
                    'stats'     => $this->transformStatistics($stat->statistics())
                ];
            })
        ];
    }

    /**
     * @return array<string, int>
     */
    private function transformStatistics(FixtureStatisticsCollection $collection): array
    {
        $statistics = [];

        /** @var FixtureStatisticInterface */
        foreach ($collection->toArray() as $statistic) {
            $statistics[$this->getTypeFrom($statistic)] = $statistic->value();
        }

        return $statistics;
    }

    private function getTypeFrom(FixtureStatisticInterface $stat): string
    {
        return match ($stat->name()) {
            FixtureStatisticInterface::SHOTS_ON_GOAL      => 'shots_on_target',
            FixtureStatisticInterface::SHOTS_OFF_GOAL     => 'shots_off_target',
            FixtureStatisticInterface::SHOTS_INSIDE_BOX   => 'shots_inside_box',
            FixtureStatisticInterface::SHOTS_OUTSIDE_BOX  => 'shots_outside_box',
            FixtureStatisticInterface::TOTAL_SHOTS        => 'shots',
            FixtureStatisticInterface::BLOCKED_SHOTS      => 'blocked_shots',
            FixtureStatisticInterface::FOULS              => 'fouls',
            FixtureStatisticInterface::CORNER_KICKS       => 'corners',
            FixtureStatisticInterface::OFFSIDES           => 'offsides',
            FixtureStatisticInterface::YELLOW_CARDS       => 'yellow_cards',
            FixtureStatisticInterface::RED_CARDS          => 'red_cards',
            FixtureStatisticInterface::GOALKEPPER_SAVES   => 'keeper_saves',
            FixtureStatisticInterface::PASSES             => 'passes',
            FixtureStatisticInterface::ACCURATE_PASSES    => 'accurate_passes',
            FixtureStatisticInterface::BALL_POSSESION     => 'ball_possession',
        };
    }
}
