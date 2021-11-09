<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\Team;
use App\Collections\DtoCollection;
use Module\Football\ValueObjects\TeamId;

/**
 * @template T of Team
 */
final class TeamsCollection extends DtoCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof Team;
    }

    public function findById(TeamId $id): Team
    {
        return $this->collection->filter(fn (Team $team) => $team->getId()->equals($id))->sole();
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
     * @throws \OutOfBoundsException
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     */
    public function sole(): Team
    {
        return $this->soleItem();
    }
}
