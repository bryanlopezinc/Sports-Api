<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Module\Football\DTO\Fixture;

interface FixtureJsonResourceInterface
{
    public function getFixture(): Fixture;
}