<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use App\ValueObjects\Date;
use Module\Football\Factories\TeamFactory;
use Tests\TestCase;
use Module\Football\ValueObjects\CoachCareer;

class CoachCareerTest extends TestCase
{
    public function test_start_date_cannot_be_after_end_date(): void
    {
        $this->expectException(\LogicException::class);

        new CoachCareer(TeamFactory::new()->toDto(), new Date(now()->addDay()->toDateString()), new Date(now()->toDateString()));
    }
}
