<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\PlayerAge;

class PayerAgeTest extends TestCase
{
    public function test_throws_exception_when_age_is_less_than_min_age(): void
    {
        $this->expectExceptionCode(400);

        new PlayerAge(PlayerAge::MIN - 1);
    }

    public function test_throws_exception_when_age_is_greater_than_max_age(): void
    {
        $this->expectExceptionCode(401);

        new PlayerAge(PlayerAge::MAX + 1);
    }
}
