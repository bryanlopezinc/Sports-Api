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

final class TeamLineUpResponseJsonMapper extends Response
{
    private const PLAYER_POSITION_MAP = [
        'G' => PlayerPosition::GOALIE,
        'D' => PlayerPosition::DEFENDER,
        'M' => PlayerPosition::MIDFIELDER,
        'F' => PlayerPosition::ATTACKER
    ];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private ?CoachBuilder $coachBuilder = null,
        private ?PlayerBuilder $playerBuilder = null,
        private ?TeamBuilder $teamBilder = null,
        private ?TeamLineUpBuilder $teamLineUpBuilder = null
    ) {
        parent::__construct($data);

        $this->coachBuilder = $coachBuilder ?: new CoachBuilder();
        $this->playerBuilder = $playerBuilder ?: new PlayerBuilder();
        $this->teamLineUpBuilder = $teamLineUpBuilder ?: new TeamLineUpBuilder();
    }

    public function toDataTransferObject(): TeamLineUp
    {
        return $this->teamLineUpBuilder
            ->setTeam((new TeamJsonMapper($this->get('team'), $this->teamBilder))->toDataTransferObject())
            ->setFormation(TeamFormation::fromString($this->get('formation')))
            ->setStartingEleven($this->mapPlayersIntoDto($this->get('startXI')))
            ->setSubstitutes($this->mapPlayersIntoDto($this->get('substitutes')))
            ->setCoach($this->mapCoachResponseIntoDto($this->get('coach')))
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
            ->photoUrl($response->get('photo'))
            ->build();
    }
}
