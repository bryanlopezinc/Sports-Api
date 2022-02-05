<?php

declare(strict_types=1);

namespace App\Collections;

use Countable;
use IteratorAggregate;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use App\Exceptions\InvalidCollectionItemException;
use App\Utils\ValidateClassWithAtrributes;

/**
 * @template T
 */
abstract class BaseCollection implements Arrayable, Countable, IteratorAggregate
{
    protected Collection $collection;

    abstract protected function isValid(mixed $item): bool;

    /**
     * @phpstan-param iterable<T>|Arrayable $items
     */
    public function __construct(iterable|Arrayable $items)
    {
        $this->setItems($items);

        $this->validateItems();
    }

    /**
     * @phpstan-return \Traversable<T>.
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    protected function validateItems(): void
    {
        $this->collection->each(function ($item, $index) {
            if (!$this->isValid($item)) {
                throw new InvalidCollectionItemException($index, static::class, $item);
            }
        })
        ->whenNotEmpty(fn () => (new ValidateClassWithAtrributes($this))->validate());
    }

    protected function setItems(mixed $items): void
    {
        $this->collection = collect($items);
    }

    /**
     * @phpstan-return array<T>
     */
    public function toArray()
    {
        return $this->collection->all();
    }

    final public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    final public function isNotEmpty(): bool
    {
        return $this->collection->isNotEmpty();
    }

    /**
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     * @throws \OutOfBoundsException
     */
    final protected function soleItem(): mixed
    {
        try {
            return $this->collection->sole();
        } catch (\Illuminate\Collections\ItemNotFoundException) {
            throw new \OutOfBoundsException;
        }
    }

    final public function count()
    {
        return $this->collection->count();
    }

    final public function toLaravelCollection(): Collection
    {
        return $this->collection;
    }
}
