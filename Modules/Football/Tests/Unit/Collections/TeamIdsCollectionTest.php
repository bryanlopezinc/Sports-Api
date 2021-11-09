<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\TeamIdsCollection;

final class TeamIdsCollectionTest extends TestCase
{
    public function test_except_ids(): void
    {
        $collection = new TeamIdsCollection([
            new TeamId(1),
            new TeamId(2),
            new TeamId(3)
        ]);

        $newCollection =  $collection->except((new TeamId(3))->toCollection());

        $this->assertSame([1, 2], $newCollection->toIntegerArray());
    }
}
