<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Http\Resources\TeamResource;

final class FixturePredictionsResource extends JsonResource
{
    public function __construct(private PredictionsQueryResult $prediction)
    {
        parent::__construct($prediction);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'total'          => $this->prediction->predictions->total,
            'draws'          => $this->prediction->predictions->draws,
            'away_team'      => [
                'team'         => new TeamResource($this->prediction->fixture->getAwayTeam()),
                'wins'         => $this->prediction->predictions->awayWins
            ],
            'home_team'      => [
                'team'         => new TeamResource($this->prediction->fixture->getHomeTeam()),
                'wins'         => $this->prediction->predictions->homeWins
            ],
        ];
    }
}
