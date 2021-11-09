<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Player;
use Module\Football\FixtureLineUp;
use Module\Football\DTO\TeamLineUp;
use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Collections\PlayersCollection;

final class FixtureLineUpResource extends JsonResource
{
    public function __construct(private FixtureLineUp $fixtureLineUp)
    {
        parent::__construct($fixtureLineUp);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'               => 'football_fixture_lineup',
            'fixture_id'         => FixtureId::fromRequest($request)->toInt(),
            'line_up'            => [
                'home'           => $this->transformTeamLineUp($this->fixtureLineUp->homeTeam()),
                'away'           => $this->transformTeamLineUp($this->fixtureLineUp->awayTeam()),
            ]
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function transformTeamLineUp(TeamLineUp $teamLineUp): array
    {
        return  [
            'team'                => new TeamResource($teamLineUp->getTeam()),
            'formation'           => $teamLineUp->getFormation()->toString(),
            'starting_XI'         => $this->tranformPlayers($teamLineUp->getStartingEleven()),
            'subs'                => $this->tranformPlayers($teamLineUp->getSubstitutes()),
            'coach'               => new CoachResource($teamLineUp->getCoach())
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function tranformPlayers(PlayersCollection $collection): array
    {
        return $collection
            ->toLaravelCollection()
            ->map(function (Player $player) {
                return new PlayerLineUpResource($player, $player->getPlayerPositionOnGridView());
            })
            ->all();
    }
}
