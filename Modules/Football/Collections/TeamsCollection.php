<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TeamId;

final class TeamsCollection extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof Team;
    }

    public function pluckIds(): TeamIdsCollection
    {
        return new TeamIdsCollection(
            $this->collection->map(fn (Team $team): TeamId => $team->getId())->all()
        );
    }

    public function merge(mixed $values): self
    {
        return new self(collect($values)->merge($this->collection->all()));
    }

    /**
     * @throws \Illuminate\Support\ItemNotFoundException
     * @throws \Illuminate\Support\MultipleItemsFoundException
     */
    public function sole(): Team
    {
        return $this->soleItem();
    }
}
