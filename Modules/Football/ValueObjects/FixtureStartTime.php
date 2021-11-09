<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use App\ValueObjects\DateValue;

final class FixtureStartTime extends DateValue
{
    protected string $format = 'Y-m-d H:i:s';
}
