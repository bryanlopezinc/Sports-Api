<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use LogicException;
use Tests\TestCase;
use Module\Football\ValueObjects\LeagueEndDate;
use Module\Football\ValueObjects\LeagueStartDate;
use Module\Football\ValueObjects\LeagueSeasonDuration;

class LeagueDurationTest extends TestCase
{
    public function test_start_date_must_be_before_end_date(): void
    {
        $this->expectException(LogicException::class);

        new LeagueSeasonDuration(
            new LeagueStartDate((string) now()->addWeek()),
            new LeagueEndDate((string) now()->subWeek())
        );
    }
}
