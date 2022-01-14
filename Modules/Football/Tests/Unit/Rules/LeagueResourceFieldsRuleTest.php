<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Rules;

use Tests\TestCase;
use Module\Football\Rules\LeagueFieldsRule;

class LeagueResourceFieldsRuleTest extends TestCase
{
    public function test_cannot_request_only_id(): void
    {
        $rule = new LeagueFieldsRule;

        $this->assertFalse($rule->passes('filter', 'id'));
        $this->assertEquals($rule->code, 2000);
    }

    public function test_cannot_request_invalid_fields(): void
    {
        $rule = new LeagueFieldsRule;

        $this->assertFalse($rule->passes('filter', 'foo,bar'));
        $this->assertEquals($rule->code, 2001);

        $this->assertFalse($rule->passes('filter', 'name,foo'));
        $this->assertEquals($rule->code, 2001);
    }

    public function test_cannot_request_parent_with_child_attribute(): void
    {
        $rule = new LeagueFieldsRule;

        $parentChildrenMap = [
            'season'     => [
                'season.season',
                'season.start',
                'season.end',
                'season.is_current_season',
            ],
            'coverage'  => [
                'coverage.line_up',
                'coverage.events',
                'coverage.stats',
                'coverage.top_scorers',
                'coverage.top_assists',
            ],
        ];

        foreach ($parentChildrenMap as $parent => $children) {
            foreach ($children as $child) {
                $this->assertFalse($rule->passes('filter', implode(',', [$parent, $child])));
                $this->assertEquals($rule->code, 2002);
            }
        }
    }
}
