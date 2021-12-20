<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\Player;
use Illuminate\Support\Collection;

/**
 * @template T of Player
 */
final class PlayersCollection extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof Player;
    }

    public function goalKeepers(): PlayersCollection
    {
        return $this->collection
            ->filter(fn (Player $player): bool => $player->getPosition()->isGoalKeeper())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }

    public function defenders(): PlayersCollection
    {
        return $this->collection
            ->filter(fn (Player $player): bool => $player->getPosition()->isDefender())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }

    public function midfielders(): PlayersCollection
    {
        return $this->collection
            ->filter(fn (Player $player): bool => $player->getPosition()->isMiddlFielder())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }

    public function attackers(): PlayersCollection
    {
        return $this->collection
            ->filter(fn (Player $player): bool => $player->getPosition()->isAttacker())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }
}
