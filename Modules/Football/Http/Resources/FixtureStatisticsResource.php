<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\DTO\FixtureStatistics as FixtureStatisticsDto;

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
            'stats'            => collect([$this->statistics->teamOne(), $this->statistics->teamTwo()])->map(function (FixtureStatisticsDto $stat) {
                return [
                    'team'      => new TeamResource($stat->team()),
                    'stats'     => [
                        'shots_on_target'   => $stat->shotsOnGoal(),
                        'shots_off_target'  => $stat->shotsOffGoal(),
                        'shots_inside_box'  => $stat->shotsInsideBox(),
                        'shots_outside_box' => $stat->shotsOutsideBox(),
                        'shots'             => $stat->totalShots(),
                        'blocked_shots'     => $stat->blockedShots(),
                        'fouls'             => $stat->fouls(),
                        'corners'           => $stat->cornerKicks(),
                        'offsides'          => $stat->offsides(),
                        'yellow_cards'      => $stat->yellowCards(),
                        'red_cards'         => $stat->redCards(),
                        'keeper_saves'      => $stat->goalKeeperSaves(),
                        'passes'            => $stat->passes(),
                        'accurate_passes'   => $stat->accuratePasses(),
                        'ball_possession'   => $stat->ballPossession(),
                    ]
                ];
            })
        ];
    }
}
