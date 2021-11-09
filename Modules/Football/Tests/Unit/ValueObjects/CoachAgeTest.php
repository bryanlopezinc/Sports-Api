<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\CoachAge;

class CoachAgeTest extends TestCase
{
    public function test_throws_exception_when_age_is_less_than_min_age(): void
    {
        $this->expectExceptionCode(433);

        new CoachAge(CoachAge::MIN_AGE - 1);
    }

    public function test_throws_exception_when_age_is_greater_than_max_age(): void
    {
        $this->expectExceptionCode(434);

        new CoachAge(CoachAge::MAX_AGE + 1);
    }
}
