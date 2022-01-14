<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Rules;

use Tests\TestCase;
use Module\Football\Rules\PartialFixtureFieldsRule;

class PartialFixtureResourceFieldsRuleTest extends TestCase
{
    public function test_cannot_request_only_id(): void
    {
        $rule = new PartialFixtureFieldsRule;

        $this->assertFalse($rule->passes('filter', 'id'));
        $this->assertEquals($rule->code, 100);
    }

    public function test_cannot_request_invalid_fields(): void
    {
        $rule = new PartialFixtureFieldsRule;

        $this->assertFalse($rule->passes('filter', 'foo,bar'));
        $this->assertEquals($rule->code, 101);
    }

    public function test_cannot_request_period_goals_and_any_of_its_children(): void
    {
        $rule = new PartialFixtureFieldsRule;

        foreach ([
            'period_goals.first_half',
            'period_goals.second_half',
            'period_goals.extra_time',
            'period_goals.penalty'
        ] as $value) {
            $this->assertFalse($rule->passes('filter', "period_goals,$value"));
            $this->assertEquals($rule->code, 102);
        }
    }

    public function test_cannot_request_valid_user_attributes(): void
    {
        $rule = new PartialFixtureFieldsRule;

        foreach ([
            'user.has_predicted',
            'user.prediction',
        ] as $field) {
            $this->assertFalse($rule->passes('filter', $field));
            $this->assertEquals($rule->code, 101);
        }
    }
}
