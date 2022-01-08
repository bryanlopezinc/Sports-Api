<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\ValueObjects\PlayerPosition;
use Module\Football\FixturePlayerStatistic\PlayerRating;
use Module\Football\DTO\Builders\PlayerStatisticBuilder as builder;

final class MapPlayerStatistics
{
    private Response $statisitcs;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $statisitcs, private Builder $builder)
    {
        $this->statisitcs = new Response($statisitcs);
    }

    public function setStatisitcs(): Builder
    {
        $this->setRating();
        $this->setPasses();
        $this->setGoalKeeperStats();

        return $this->builder
            ->cards($this->castValue('cards.red'), $this->castValue('cards.yellow'))
            ->interceptions($this->castValue('tackles.interceptions'))
            ->offsides($this->castValue('offsides'))
            ->shots($this->castValue('shots.on'), $this->castValue('shots.total'))
            ->minutesPlayed($this->castValue('games.minutes'))
            ->goals($this->castValue('goals.total'), $this->castValue('goals.assists'))
            ->dribbles($this->castValue('dribbles.attempts'), $this->castValue('dribbles.success'), $this->castValue('dribbles.past'));
    }

    private function castValue(string $key): int
    {
        return (int) $this->statisitcs->get($key);
    }

    private function setGoalKeeperStats(): void
    {
        if (!$this->getPlayerPosition()->isGoalKeeper()) {
            return;
        }

        $this->builder->goalKeeperGoalStat($this->castValue('goals.conceded'), $this->castValue('goals.saves'));
    }

    private function setPasses(): void
    {
        $passAccuracy = (string) $this->statisitcs->get('passes.accuracy');

        $this->builder->passes($this->castValue('passes.key'), $this->castValue('passes.total'), intval($passAccuracy));
    }

    private function setRating(): void
    {
        $rating = floatval($this->statisitcs->get('games.rating'));

        if ($rating < PlayerRating::LOWEST) {
            $rating = PlayerRating::LOWEST;
        }

        if ($rating > PlayerRating::HIGHEST) {
            $rating = PlayerRating::HIGHEST;
        }

        $this->builder->rating($rating);
    }

    private function getPlayerPosition(): PlayerPosition
    {
        return new PlayerPosition(
            match ($this->statisitcs->get('games.position')) {
                'G' => PlayerPosition::GOALIE,
                'D' => PlayerPosition::DEFENDER,
                'M' => PlayerPosition::MIDFIELDER,
                'F' => PlayerPosition::ATTACKER
            }
        );
    }
}
