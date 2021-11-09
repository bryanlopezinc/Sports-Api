<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use InvalidArgumentException;
use Module\Football\ValueObjects\JerseyNumber;

class JerseyNumberTest extends TestCase
{
    public function test_throws_exception_when_jersey_number_is_less_than_minimum_number(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new JerseyNumber(JerseyNumber::MIN - 1);
    }

    public function test_throws_exception_when_jersey_number_is_greater_than_maximum_number(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new JerseyNumber(JerseyNumber::MAX + 1);
    }
}
