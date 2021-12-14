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
        $this->assertEquals($rule->message(), 'Only id field cannot be requested');
    }

    public function test_cannot_request_invalid_fields(): void
    {
        $rule = new PartialFixtureFieldsRule;

        $this->assertFalse($rule->passes('filter', 'foo,bar'));
        $this->assertEquals($rule->message(), 'The given partial resource fields are Invalid');
    }
}
