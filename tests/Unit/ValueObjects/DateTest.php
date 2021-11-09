<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\ValueObjects\Date;

class DateTest extends TestCase
{
    public function test_throws_exception_on_invalid_date(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Date('date');
    }
}
