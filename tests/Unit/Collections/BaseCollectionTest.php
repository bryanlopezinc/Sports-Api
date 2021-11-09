<?php

declare(strict_types=1);

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Collections\BaseCollection;
use App\Exceptions\InvalidCollectionItemException;

class BaseCollectionTest extends TestCase
{
    public function test_throws_exception_when_item_is_invalid(): void
    {
        $this->expectException(InvalidCollectionItemException::class);

        $items = ['foo'];

        new class($items) extends BaseCollection
        {
            protected function isValid(mixed $item): bool
            {
                return false;
            }
        };
    }
}
