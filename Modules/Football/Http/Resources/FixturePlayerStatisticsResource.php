<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\PlayerStatistics;
use Illuminate\Http\Resources\Json\JsonResource;

final class FixturePlayerStatisticsResource extends JsonResource
{
    public function __construct(private PlayerStatistics $statistics)
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
            'type'       => 'football_player_fixture_statistics',
            'attributes' => [
                'player'           => new PlayerResource($this->statistics->player()),
                'team'             => new TeamResource($this->statistics->team()),
                'rating'           => $this->statistics->rating()->rating(),
                'minutes_played'   => $this->statistics->minutesPlayed()->minutes(),
                'offsides'         => $this->statistics->offsides(),
                'interception'     => $this->statistics->interceptions(),
                'cards'            => [
                    'yellow'       => $this->statistics->cards()->yellows(),
                    'red'          => $this->statistics->cards()->reds(),
                    'total'        => $this->statistics->cards()->total()
                ],
                'dribbles'         => [
                    'attempts'     => $this->statistics->dribbles()->attempts(),
                    'successful'   => $this->statistics->dribbles()->successful(),
                    'past'         => $this->statistics->dribbles()->dribbledPast(),
                ],
                'goals'            => [
                    'total'        => $this->statistics->goals()->goals(),
                    'assists'      => $this->statistics->goals()->assists(),
                    'saves'        => $this->when($this->statistics->player()->getPosition()->isGoalKeeper(), fn () => $this->statistics->goalKeeperGoalsStat()->saves()),
                    'conceeded'    => $this->when($this->statistics->player()->getPosition()->isGoalKeeper(), fn () => $this->statistics->goalKeeperGoalsStat()->goalsConceded()),
                ],
                'shots'            => [
                    'on_target'    => $this->statistics->shots()->onTarget(),
                    'total'        => $this->statistics->shots()->total(),
                ],
                'passes'           => [
                    'accuracy'     => $this->statistics->passes()->accuracy() . '%',
                    'key'          => $this->statistics->passes()->keyPasses(),
                    'total'        => $this->statistics->passes()->total()
                ]
            ],
        ];
    }
}
