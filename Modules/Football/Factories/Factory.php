<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use App\Collections\BaseCollection;
use App\DTO\DataTransferObject;
use App\Collections\DtoCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

abstract class Factory extends BaseFactory
{
    private static int $lastId = 1;
    protected string $dtoClass;

    protected function getIncrementingId(): int
    {
        return static::$lastId++;
    }

    public function makeAttributes($attributes = []): array
    {
        return parent::make($attributes)->toArray();
    }

    public function newModel(array $attributes = [])
    {
        return new class($attributes) extends Model
        {
            public function toArray()
            {
                return $this->attributes;
            }
        };
    }

    protected function mapToDto()
    {
        $class = $this->dtoClass;

        return new $class($this->makeAttributes());
    }

    /**
     * @template T
     * @phpstan-param class-string<T> $collectionClass
     * @phpstan-return T
     */
    protected function mapToCollection(string $collectionClass)
    {
        $count = (int) $this->count;
        $dto = $this->dtoClass;
        $itemsArray = $this->makeAttributes();
        $values = [];

        if ($count < 1) {
            $values = [new $dto($itemsArray)];
        }

        if ($count > 1) {
            $values = collect($itemsArray)->map(fn (array $attributes) => new $dto($attributes))->all();
        }

        return new $collectionClass($values);
    }
}
