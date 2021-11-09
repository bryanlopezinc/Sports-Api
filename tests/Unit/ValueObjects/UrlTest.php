<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\ValueObjects\Url;

class UrlTest extends TestCase
{
    public function test_throws_exception_on_invalid_url(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Url::fromString('google.com');
    }
}
