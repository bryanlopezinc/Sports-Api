<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\ValueObjects\Email;

class EmailTest extends TestCase
{
    public function test_throws_exception_on_invalid_date(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('email');
    }
}
