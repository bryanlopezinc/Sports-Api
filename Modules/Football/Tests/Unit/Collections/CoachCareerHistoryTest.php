<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use App\ValueObjects\Date;
use Module\Football\Factories\TeamFactory;
use Module\Football\ValueObjects\CoachCareer;
use Module\Football\Collections\CoachCareerHistory;

class CoachCareerHistoryTest extends TestCase
{
    public function test_coach_can_be_only_one_teams_current_manager(): void
    {
        $this->expectExceptionMessage('Invalid coach career history' . 'Duplicate cuurent teams');

        new CoachCareerHistory([
            new CoachCareer(TeamFactory::new()->toDto(), new Date(now()->yesterday()->toDateString()), null),
            new CoachCareer(TeamFactory::new()->toDto(), new Date(now()->yesterday()->toDateString()), new Date(now()->toDateString())),
            new CoachCareer(TeamFactory::new()->toDto(), new Date(now()->yesterday()->toDateString()), null)
        ]);
    }
}
