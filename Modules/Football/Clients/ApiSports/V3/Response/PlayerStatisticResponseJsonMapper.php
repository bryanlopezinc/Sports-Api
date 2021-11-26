<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\PlayerStatistics;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\PlayerPosition;
use Module\Football\FixturePlayerStatistic\PlayerRating;
use Module\Football\DTO\Builders\PlayerStatisticBuilder as builder;

final class PlayerStatisticResponseJsonMapper
{
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        private ?TeamBuilder $teamBuilder = null,
        private ?PlayerBuilder $playerBuilder = null,
        private ?Builder $builder = null
    ) {
        $this->response = new Response($this->rekeyResponseDataForEasyAccess($data));
        $this->playerBuilder = $playerBuilder ?: new PlayerBuilder();
        $this->builder = $builder ?: new Builder();
    }

    private function rekeyResponseDataForEasyAccess(array $response): array
    {
        $data = [];

        $data['team'] = $response['team'];
        $data['player'] = $response['players'][0]['player'];
        $data['statistics'] = $response['players'][0]['statistics'][0];

        return $data;
    }

    public function toDataTransferObject(): PlayerStatistics
    {
        $this->setTeam();
        $this->setPlayer();
        $this->setRating();
        $this->setPasses();
        $this->setGoalKeeperStats();

        return $this->builder
            ->cards($this->castValue('statistics.cards.red'), $this->castValue('statistics.cards.red'))
            ->interceptions($this->castValue('statistics.tackles.interceptions'))
            ->offsides($this->castValue('statistics.offsides'))
            ->shots($this->castValue('statistics.shots.on'), $this->castValue('statistics.shots.total'))
            ->minutesPlayed($this->castValue('statistics.games.minutes'))
            ->goals($this->castValue('statistics.goals.total'), $this->castValue('statistics.goals.assists'))
            ->dribbles($this->castValue('statistics.dribbles.attempts'), $this->castValue('statistics.dribbles.success'), $this->castValue('statistics.dribbles.past'))
            ->build();
    }

    private function castValue(string $key): int
    {
        //Convert potential null value to zero as Apisports adore/worship null.
        return (int) $this->response->get($key);
    }

    private function setGoalKeeperStats(): void
    {
        if (!$this->getPlayerPosition()->isGoalKeeper()) {
            return;
        }

        $this->builder->goalKeeperGoalStat($this->castValue('statistics.goals.conceded'), $this->castValue('statistics.goals.saves'));
    }

    private function setPasses(): void
    {
        $passAccuracy = (string) $this->response->get('statistics.passes.accuracy');

        $this->builder->passes($this->castValue('statistics.passes.key'), $this->castValue('statistics.passes.total'), intval($passAccuracy));
    }

    private function setRating(): void
    {
        $rating = floatval($this->response->get('statistics.games.rating'));

        if ($rating < PlayerRating::LOWEST) {
            $rating = PlayerRating::LOWEST;
        }

        $this->builder->rating($rating);
    }

    private function setTeam(): void
    {
        $this->builder->team(
            (new TeamJsonMapper($this->response->get('team'), $this->teamBuilder))->toDataTransferObject()
        );
    }

    private function setPlayer(): void
    {
        $builder = $this->playerBuilder->setPosition($this->getPlayerPosition()->position());

        $this->builder->player(
            (new PlayerResponseJsonMapper($this->response->get('player'), [], $builder))->toDataTransferObject()
        );
    }

    private function getPlayerPosition(): PlayerPosition
    {
        return new PlayerPosition(
            match ($this->response->get('statistics.games.position')) {
                'G' => PlayerPosition::GOALIE,
                'D' => PlayerPosition::DEFENDER,
                'M' => PlayerPosition::MIDFIELDER,
                'F' => PlayerPosition::ATTACKER
            }
        );
    }
}
