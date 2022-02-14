<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Rules;

use Tests\TestCase;
use Module\Football\Rules\LeagueStandingFieldsRule;

class LeagueStandingFieldsRuleTest extends TestCase
{
    public function test_cannot_request_only_team(): void
    {
        $rule = new LeagueStandingFieldsRule;

        $this->assertFalse($rule->passes('filter', ['team']));
        $this->assertEquals($rule->message(), 'Only team field cannot be requested');
    }

    public function test_cannot_request_invalid_fields(): void
    {
        $rule = new LeagueStandingFieldsRule;

        $this->assertFalse($rule->passes('filter', ['foo','bar']));
        $this->assertEquals($rule->message(), 'The given partial resource fields are Invalid');
    }
}
