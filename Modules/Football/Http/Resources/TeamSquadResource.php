<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Player;
use Module\Football\ValueObjects\TeamId;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Collections\PlayersCollection;

final class TeamSquadResource extends JsonResource
{
    public function __construct(private PlayersCollection $players)
    {
        parent::__construct($players);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'football_team_squad',
            'team_id'       => TeamId::fromRequest($request)->toInt(),
            'total'         => $this->players->count(),
            'squad'         => [
                'goal_keepers'  => $this->transformCollection($this->players->goalKeepers()),
                'defenders'     => $this->transformCollection($this->players->defenders()),
                'midfielders'   => $this->transformCollection($this->players->midfielders()),
                'attackers'     => $this->transformCollection($this->players->attackers()),
            ],
        ];
    }

    /**
     * @return array<PlayerResource>
     */
    private function transformCollection(PlayersCollection $players): array
    {
        return $players->toLaravelCollection()->map(fn (Player $player) => new PlayerResource($player))->all();
    }
}
