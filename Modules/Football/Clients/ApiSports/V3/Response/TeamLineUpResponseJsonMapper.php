<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Coach;
use Module\Football\DTO\Player;
use Illuminate\Support\Collection;
use Module\Football\DTO\TeamLineUp;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\CoachBuilder;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\TeamFormation;
use Module\Football\ValueObjects\PlayerPosition;
use Module\Football\Collections\PlayersCollection;
use Module\Football\DTO\Builders\TeamLineUpBuilder;

final class TeamLineUpResponseJsonMapper
{
    private const PLAYER_POSITION_MAP = [
        'G' => PlayerPosition::GOALIE,
        'D' => PlayerPosition::DEFENDER,
        'M' => PlayerPosition::MIDFIELDER,
        'F' => PlayerPosition::ATTACKER
    ];

    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private CoachBuilder $coachBuilder = new CoachBuilder(),
        private PlayerBuilder $playerBuilder = new PlayerBuilder(),
        private TeamBuilder $teamBilder = new TeamBuilder(),
        private TeamLineUpBuilder $teamLineUpBuilder = new TeamLineUpBuilder()
    ) {
        $this->response = new Response($data);
    }

    public function toDataTransferObject(): TeamLineUp
    {
        return $this->teamLineUpBuilder
            ->setTeam((new TeamJsonMapper($this->response->get('team'), $this->teamBilder))->toDataTransferObject())
            ->setFormation(TeamFormation::fromString($this->response->get('formation')))
            ->setStartingEleven($this->mapPlayersIntoDto($this->response->get('startXI')))
            ->setSubstitutes($this->mapPlayersIntoDto($this->response->get('substitutes')))
            ->setCoach($this->mapCoachResponseIntoDto($this->response->get('coach')))
            ->build();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapPlayersIntoDto(array $data): PlayersCollection
    {
        return collect($data)
            ->map(function (array $player): Player {
                $hasPostionOnGridView = isset($player['grid']);

                return $this->playerBuilder
                    ->fromPlayer((new PlayerResponseJsonMapper($player['player'], self::PLAYER_POSITION_MAP, $this->playerBuilder))->toDataTransferObject())
                    ->when($hasPostionOnGridView, function (PlayerBuilder $b) use ($player): PlayerBuilder {
                        return $b->setPositionOnGridLineUp(...$this->getPlayerPositionOnGridView($player));
                    })
                    ->when(!$hasPostionOnGridView, fn (PlayerBuilder $b): PlayerBuilder => $b->setPositionOnGridLineUp(null, null))
                    ->build();
            })
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }

    /**
     * @param array<string, mixed> $playerData
     * @return array<int>
     */
    private function getPlayerPositionOnGridView(array $playerData): array
    {
        return collect(explode(':', $playerData['grid']))->map(fn (string $postion): int => (int)($postion))->all();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapCoachResponseIntoDto(array $data): Coach
    {
        $response = new Response($data);

        return $this->coachBuilder
            ->id($response->get('id'))
            ->name($response->get('name'))
            ->photoUrl($response->get('id'))
            ->build();
    }
}
