<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Requests;

use Tests\TestCase;
use Module\Football\Http\PartialLeagueRequest;

class PartialLeagueRequestTest extends TestCase
{
    public function test_will_return_unique_fields(): void
    {
        $request = new PartialLeagueRequest(['name', 'name']);

        $this->assertEquals(['name'], $request->all());
    }

    public function test_all_will_return_correct_values(): void
    {
        $values = (new PartialLeagueRequest(['name', 'logo_url', 'country']))->all(['country', 'logo_url']);

        $this->assertCount(1, $values);
        $this->assertEquals('name', $values[0]);
    }
}
