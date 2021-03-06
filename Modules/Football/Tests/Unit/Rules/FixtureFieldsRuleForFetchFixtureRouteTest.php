<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Rules;

use Tests\TestCase;
use Module\Football\Rules\FixtureFieldsRuleForFetchFixtureRequest;

class FixtureFieldsRuleForFetchFixtureRouteTest extends TestCase
{
    public function test_can_request_valid_user_attributes(): void
    {
        $rule = new FixtureFieldsRuleForFetchFixtureRequest;

        foreach ([
            'user.has_predicted',
            'user.prediction',
        ] as $field) {
            $this->assertTrue($rule->passes('filter', [$field]));
        }
    }
}
