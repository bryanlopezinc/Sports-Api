<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ResourceIdRule;

class ResourceIdRuleTest extends TestCase
{
    public function test_fails_validation_when_int_id_is_a_negative_number(): void
    {
        $rule = new ResourceIdRule;

        $this->assertFalse($rule->passes('id', -1));
    }

    public function test_fails_validation_when_int_id_is_zero(): void
    {
        $rule = new ResourceIdRule;

        $this->assertFalse($rule->passes('id', 0));
    }
}
