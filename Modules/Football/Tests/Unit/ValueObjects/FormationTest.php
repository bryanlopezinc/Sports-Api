<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\TeamFormation as Formation;

class FormationTest extends TestCase
{
    public function test_invalid_formation(): void
    {
        $this->expectExceptionCode(400);

        Formation::fromString('foobar');
    }

    public function test_formation_with_zero(): void
    {
        $this->expectExceptionCode(400);

        Formation::fromString('0-1-9');
    }

    public function test_formation_with_more_than_ten_players(): void
    {
        $this->expectExceptionCode(401);

        Formation::fromString('4-5-2');
    }
}
