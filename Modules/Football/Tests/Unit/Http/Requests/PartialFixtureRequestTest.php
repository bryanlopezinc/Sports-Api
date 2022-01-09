<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Requests;

use Tests\TestCase;
use Module\Football\Http\PartialFixtureRequest;

class PartialFixtureRequestTest extends TestCase
{
    public function test_will_return_unique_fields(): void
    {
        $request = new PartialFixtureRequest(['period_goals', 'period_goals']);

        $this->assertEquals(['period_goals'], $request->all());
    }

    public function test_will_add_extra_required_attributes(): void
    {
        $this->assertAttributesMatch(['venue'], ['venue', 'has_venue_info'], 1);
        $this->assertAttributesMatch(['winner'], ['winner', 'has_winner'], 2);
        $this->assertAttributesMatch(['score'], ['score', 'score_is_available'], 3);
        $this->assertAttributesMatch(['period_goals.first_half'], ['period_goals.meta.has_first_half_score', 'period_goals.first_half'], 4);
        $this->assertAttributesMatch(['period_goals.second_half'], ['period_goals.meta.has_full_time_score', 'period_goals.second_half'], 5);
        $this->assertAttributesMatch(['period_goals.extra_time'], ['period_goals.meta.has_extra_time_score', 'period_goals.extra_time'], 6);
        $this->assertAttributesMatch(['period_goals.penalty'], ['period_goals.meta.has_penalty_score', 'period_goals.penalty'], 7);
    }

    private function assertAttributesMatch(array $requestedFields, array $expected, int $id)
    {
        $this->assertTrue(array_diff($expected, (new PartialFixtureRequest($requestedFields))->all()) == [], "failed Validation for $id");
    }
}
