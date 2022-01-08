<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\PlayerStatistics;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\PlayerPosition;
use Module\Football\DTO\Builders\PlayerStatisticBuilder as builder;
use Module\Football\DTO\Player;
use Module\Football\DTO\Team;

final class PlayerStatisticResponseJsonMapper
{
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private TeamBuilder $teamBuilder = new TeamBuilder,
        private PlayerBuilder $playerBuilder = new PlayerBuilder(),
        private Builder $builder = new Builder()
    ) {
        $this->response = new Response($data);
    }

    /**
     * @return array<PlayerStatistics>
     */
    public function toArray(): array
    {
        $this->builder->team($this->mapTeam($this->response->get('team')));

        $callback = function (array $data): PlayerStatistics {
            $this->builder->player($this->mapPlayer($data['player'], $data['statistics'][0]['games']['position']));

            return (new MapPlayerStatistics($data['statistics'][0], $this->builder))
                ->setStatisitcs()
                ->build();
        };

        return array_map($callback, $this->response->get('players'));
    }

    private function mapTeam(array $data): Team
    {
        return (new TeamJsonMapper($data, $this->teamBuilder))->toDataTransferObject();
    }

    private function mapPlayer(array $data, string $position): Player
    {
        $playerPosition = new PlayerPosition(
            match ($position) {
                'G' => PlayerPosition::GOALIE,
                'D' => PlayerPosition::DEFENDER,
                'M' => PlayerPosition::MIDFIELDER,
                'F' => PlayerPosition::ATTACKER
            }
        );

        $builder = $this->playerBuilder->setPosition($playerPosition->position());

        return (new PlayerResponseJsonMapper($data, [], $builder))->toDataTransferObject();
    }
}
