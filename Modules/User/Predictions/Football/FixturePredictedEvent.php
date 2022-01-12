<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Foundation\Events\Dispatchable;
use Module\Football\ValueObjects\FixtureId;
use Module\User\ValueObjects\UserId;

final class FixturePredictedEvent
{
    use Dispatchable;
    
    public function __construct(public readonly FixtureId $fixtureId, public readonly UserId $userId)
    {
    }
}
