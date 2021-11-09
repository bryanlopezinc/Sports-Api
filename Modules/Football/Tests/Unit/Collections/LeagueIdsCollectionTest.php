<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueIdsCollection;

final class LeagueIdsCollectionTest extends TestCase
{
    public function test_except_ids(): void
    {
        $collection = new LeagueIdsCollection([
            new LeagueId(1),
            new LeagueId(2),
            new LeagueId(3)
        ]);

        $newCollection =  $collection->except((new LeagueId(3))->toCollection());

        $this->assertSame([1, 2], $newCollection->toIntegerArray());
    }
}
