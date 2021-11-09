<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;

class TeamsCollectionTest extends TestCase
{
    public function test_find_team_by_id(): void
    {
        $collection = TeamFactory::new()->count(10)->toCollection();

        $team = $collection->toLaravelCollection()->first();

        $result = $collection->findById($team->getId());

        $this->assertEquals($result, $team);
    }
}
