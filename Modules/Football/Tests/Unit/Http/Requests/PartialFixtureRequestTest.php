<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Requests;

use Tests\TestCase;
use Module\Football\Http\PartialFixtureRequest;

class PartialFixtureRequestTest extends TestCase
{
    public function test_wants_partial_response(): void
    {
        $request = new PartialFixtureRequest(['id', 'referee']);

        $this->assertTrue($request->wantsPartialResponse());
    }

    public function test_wantsSpecificPeriodGoalsData_returns_true_when_a_period_goals_element_is_requested(): void
    {
        $request = new PartialFixtureRequest(['period_goals.first_half']);

        $this->assertTrue($request->wantsSpecificPeriodGoalsData());
    }

    public function test_wantsSpecificPeriodGoalsData_returns_false_when_coverage_element_is_not_requested(): void
    {
        $request = new PartialFixtureRequest(['period_goals']);

        $this->assertFalse($request->wantsSpecificPeriodGoalsData());
    }

    public function test_will_remove_period_goals_if_request_has_period_goals_and_specific_period_goals_data(): void
    {
        $request = new PartialFixtureRequest(['period_goals', 'period_goals.first_half']);

        $this->assertFalse($request->wants('period_goals'));
    }

    public function test_will_return_unique_fields(): void
    {
        $request = new PartialFixtureRequest(['period_goals', 'period_goals']);

        $this->assertEquals(['period_goals'], $request->all());
    }
}
