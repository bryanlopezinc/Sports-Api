<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Requests;

use Tests\TestCase;
use Module\Football\Http\PartialLeagueStandingRequest;

class PartialLeagueStandingRequestTest extends TestCase
{
    public function test_will_include_team_attribute_by_default(): void
    {
        $request = new PartialLeagueStandingRequest(['points']);

        $this->assertEquals(['points', 'team'], $request->all());
    }

    public function test_will_not_duplicate_team_field_when_team_field_is_requested(): void
    {
        $request = new PartialLeagueStandingRequest(['points', 'team']);

        $this->assertEquals(['points', 'team'], $request->all());
    }

    public function test_wants_partial_response_will_return_false_when_no_fields_are_requested(): void
    {
        $request = new PartialLeagueStandingRequest([]);

        $this->assertFalse($request->wantsPartialResponse());
    }
}
