<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\HeightValue;

class HeightValueTest extends TestCase
{
    public function test_throws_exception_when_height_is_less_than_min_height(): void
    {
        $this->expectExceptionCode(400);

        new HeightValue(HeightValue::MIN_HEIGHT_CM - 1);
    }

    public function test_throws_exception_when_height_is_greater_than_max_height(): void
    {
        $this->expectExceptionCode(401);

        new HeightValue(HeightValue::MAX_HEIGHT_CM + 1);

    }
}
