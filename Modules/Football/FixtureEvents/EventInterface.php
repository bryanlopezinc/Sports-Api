<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

interface EventInterface
{
    public function team(): Team;

    public function time(): TimeElapsed;
}
