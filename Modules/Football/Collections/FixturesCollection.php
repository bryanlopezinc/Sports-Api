<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\Fixture;
use Illuminate\Support\Collection;

/**
 * @template T of Fixture
 */
final class FixturesCollection extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof Fixture;
    }

    /**
     * @throws \OutOfBoundsException
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     */
    public function sole(): Fixture
    {
        return $this->soleItem();
    }

    public function teams(): TeamsCollection
    {
        return $this->collection
            ->map(fn (Fixture $fixture): array => [$fixture->getHomeTeam(), $fixture->getAwayTeam()])
            ->flatten()
            ->pipe(fn (Collection $collection) => new TeamsCollection($collection->all()));
    }

    public function allFixturesArefinished(): bool
    {
        return $this->count() === $this->collection->filter(fn (Fixture $fixture): bool => $fixture->status()->isFinished())->count();
    }

    public function anyFixtureIsInProgress(): bool
    {
        /** @var Fixture */
        foreach ($this->collection as $fixture) {
            if ($fixture->status()->isInProgress()) {
                return true;
            }
        }

        return false;
    }

    public function sortByDateTimeDesc(): FixturesCollection
    {
        return $this->collection
            ->sortByDesc(fn (Fixture $fixture) => $fixture->date()->toCarbon()->toDateTimeString())
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));
    }

    public function hasUpcomingFixture(): bool
    {
        /** @var Fixture */
        foreach ($this->sortByDateTimeDesc()->collection as $fixture) {
            if ($fixture->status()->isNotStarted()) {
                return true;
            }
        }

        return false;
    }

    public function nextUpcomingFixture(): Fixture
    {
        /** @var Fixture */
        foreach ($this->sortByDateTimeDesc() as $fixture) {
            if ($fixture->status()->isNotStarted()) {
                return $fixture;
            }
        }

        throw new \OutOfBoundsException();
    }
}
