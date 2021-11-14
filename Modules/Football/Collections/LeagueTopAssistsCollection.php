<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Illuminate\Support\Collection;
use Module\Football\DTO\Player;
use Module\Football\ValueObjects\LeagueTopAssist;
use Module\Football\Attributes\LeagueTopAssistsValidators\EnsureCollectionHasNoDuplcatePlayers;

#[EnsureCollectionHasNoDuplcatePlayers]
final class LeagueTopAssistsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof LeagueTopAssist;
    }

    public function players(): PlayersCollection
    {
        return $this->collection
            ->map(fn (LeagueTopAssist $topScorer): Player => $topScorer->player())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }
}
