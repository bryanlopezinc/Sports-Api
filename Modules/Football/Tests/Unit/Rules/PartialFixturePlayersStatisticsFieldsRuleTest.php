<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Rules;

use Tests\TestCase;
use Module\Football\Rules\PartialFixturePlayersStatisticsFieldsRule as Rule;

class PartialFixturePlayersStatisticsFieldsRuleTest extends TestCase
{
    public function test_cannot_request_invalid_field(): void
    {
        $rule = new Rule;

        $this->assertFalse($rule->passes('filter', 'foo'));
        $this->assertEquals($rule->message(), "The given partial resource field foo is Invalid");
        $this->assertFalse($rule->passes('filter', 'cards,bar'));
        $this->assertEquals($rule->message(), "The given partial resource field bar is Invalid");
    }

    public function test_will_pass_validation_when_valid(): void
    {
        $rule = new Rule;

        $this->assertTrue($rule->passes('filter', 'cards.yellow'));
        $this->assertTrue($rule->passes('filter', 'cards.yellow,cards.red,cards.total'));
        $this->assertTrue($rule->passes('filter', 'cards'));
    }

    public function test_cannot_request_parent_and_child_attribute(): void
    {
        $rule = new Rule;

        $parentChildrenMap = [
            'cards'     => ['cards.yellow', 'cards.red', 'cards.total'],
            'dribbles'  => ['dribbles.past', 'dribbles.successful', 'dribbles.attempts'],
            'goals'     => ['goals.total', 'goals.assists', 'goals.saves', 'goals.conceeded'],
            'shots'     => ['shots.on_target', 'shots.total'],
            'passes'    => ['passes.key', 'passes.accuracy', 'passes.total']
        ];

        foreach ($parentChildrenMap as $parent => $children) {
            foreach ($children as $child) {
                $this->assertFalse($rule->passes('filter', implode(',', [$parent, $child])));
                $this->assertEquals($rule->message(), $rule->errorMessageForParentChildRequest($parent, $child));
            }
        }
    }
}
