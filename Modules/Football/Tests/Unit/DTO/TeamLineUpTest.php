<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\DTO;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\PlayerFactory;
use Module\Football\DTO\Builders\TeamLineUpBuilder;

class TeamLineUpTest extends TestCase
{
    public function test_starting_eleven_cannot_exceeds_11(): void
    {
        $this->expectException(LogicException::class);

        (new TeamLineUpBuilder)
            ->setStartingEleven(PlayerFactory::new()->count(12)->toCollection())
            ->build();
    }
}
