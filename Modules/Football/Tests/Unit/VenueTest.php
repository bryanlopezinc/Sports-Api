<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit;

use Module\Football\Venue;
use Module\Football\ValueObjects\Name;
use Tests\TestCase;

class VenueTest extends TestCase
{
    public function test_will_throw_exception_when_only_venue_city_is_given(): void
    {
        $this->expectExceptionCode(3000);

        new Venue(null, 'Tortuga');
    }

    public function test_will_throw_exception_when_only_venue_name_is_given(): void
    {
        $this->expectExceptionCode(3000);

        new Venue(new Name('isla de Muerta'), null);
    }

    public function test_is_known(): void
    {
        $knonVenue = new Venue(new Name('ShipWreck Cove'), 'Tortuga');

        $this->assertTrue($knonVenue->isKnown());
        $this->assertFalse(Venue::unknown()->isKnown());
    }
}
