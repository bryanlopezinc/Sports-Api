<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\Player;
use Illuminate\Support\Collection;
use App\Collections\BaseCollection;
use Module\Football\ValueObjects\LeagueTopScorer;

final class LeagueTopScorersCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof LeagueTopScorer;
    }

    protected function validateItems(): void
    {
        parent::validateItems();
        
        $this->players()
            ->toLaravelCollection()
            ->duplicates(fn (Player $player): int => $player->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('Duplicate players found in top scorers collection'));
    }

    public function players(): PlayersCollection
    {
        return $this->collection
            ->map(fn (LeagueTopScorer $topScorer): Player => $topScorer->player())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }
}
