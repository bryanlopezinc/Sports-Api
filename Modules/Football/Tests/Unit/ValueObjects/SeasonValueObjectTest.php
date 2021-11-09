<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\Season;
use Module\Football\Exceptions\InvalidSeasonException;

class SeasonValueObjectTest extends TestCase
{
    public function test_should_not_be_less_than_min_season(): void
    {
        $this->expectException(InvalidSeasonException::class);

        new Season(Season::minSeason() - 1);
    }

    public function test_equals(): void
    {
        $season = Season::make(today()->year);

        $this->assertObjectEquals($season, $season);
    }
}
