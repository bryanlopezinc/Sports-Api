<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\TeamYearFounded;

class YearFoundedTest extends TestCase
{
    public function test_throws_exception_when_team_year_founded_less_than_min_year(): void
    {
        $this->expectExceptionCode(400);

        new TeamYearFounded(TeamYearFounded::MIN_YEAR - 1);
    }

    public function test_throws_exception_when_team_year_founded_greater_than_max_year(): void
    {
        $this->expectExceptionCode(401);

        new TeamYearFounded(today()->year + 1);
    }
}
