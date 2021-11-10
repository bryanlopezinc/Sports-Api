<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\ValueObjects\LeagueTopScorer;

final class LeagueTopScorerResource extends JsonResource
{
    public function __construct(private LeagueTopScorer $topScorer)
    {
        parent::__construct($topScorer);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'football_league_topScorer',
            'player'        => new PlayerResource($this->topScorer->player()),
            'goals'         => $this->topScorer->leagueGoals()
        ];
    }
}
