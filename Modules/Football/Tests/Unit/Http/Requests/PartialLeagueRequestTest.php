<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Requests;

use Tests\TestCase;
use Module\Football\Http\PartialLeagueRequest;

class PartialLeagueRequestTest extends TestCase
{
    public function test_cannot_request_only_id(): void
    {
        $request = new PartialLeagueRequest(['id']);

        $this->assertFalse($request->wantsPartialResponse());
    }

    public function test_will_return_empty_if_all_fields_are_invalid(): void
    {
        $request = new PartialLeagueRequest(['foo', 'bar']);

        $this->assertFalse($request->wantsPartialResponse());
    }

    public function test_wants_partial_response(): void
    {
        $request = new PartialLeagueRequest(['id', 'name']);

        $this->assertTrue($request->wantsPartialResponse());
    }

    public function test_wantsSpecificCoverageData_returns_true_when_a_coverage_element_is_requested(): void
    {
        $request = new PartialLeagueRequest(['coverage.line_up']);

        $this->assertTrue($request->wantsSpecificCoverageData());
    }

    public function test_wantsSpecificCoverageData_returns_false_when_coverage_element_is_not_requested(): void
    {
        $request = new PartialLeagueRequest(['coverage']);

        $this->assertFalse($request->wantsSpecificCoverageData());
    }

    public function test_will_remove_coverage_if_request_has_coverage_and_specific_coverage_data(): void
    {
        $request = new PartialLeagueRequest(['coverage', 'coverage.line_up']);

        $this->assertFalse($request->wants('coverage'));
    }

    public function test_will_remove_season_if_request_has_season_and_specific_season_data(): void
    {
        $request = new PartialLeagueRequest(['season', 'season.end']);

        $this->assertFalse($request->wants('season'));
    }

    public function test_will_return_unique_fields(): void
    {
        $request = new PartialLeagueRequest(['coverage', 'coverage']);

        $this->assertEquals(['coverage'], $request->all());
    }
}
