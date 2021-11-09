<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Module\Football\Collections\FixturesCollection;
use Tests\TestCase;
use Module\Football\Factories\FixtureFactory;

class FixturesCollectionTest extends TestCase
{
    public function test_all_fixtures_are_finished(): void
    {
        $this->assertTrue(
            FixtureFactory::new()->count(5)->toCollection()->allFixturesArefinished()
        );
    }

    public function test_any_fixtures_is_in_progress(): void
    {
        $fixtures = FixtureFactory::new()
            ->count(5)
            ->toCollection()
            ->toLaravelCollection()
            ->add(FixtureFactory::new()->firstPeriod()->toDto());

        $this->assertTrue(
            (new FixturesCollection($fixtures->all()))->anyFixtureIsInProgress()
        );
    }
}
