<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Clients\ApiSports\V3\Response;

use Module\Football\Clients\ApiSports\V3\Response\FixtureResponseJsonMapper;
use Tests\TestCase;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\ValueObjects\FixtureStatus;

class FixtureResponseJsonMapperTest extends TestCase
{
    /**
     * @dataProvider fixtureResponse
     */
    public function test_will_mark_fixture_as_finished_when_fixture_was_concluded_more_than_25_minutes_ago(array $response): void
    {
        $fixture = (new FixtureResponseJsonMapper())->toDataTransferObject($response);

        $this->assertTrue($fixture->status()->isFinished());
    }

    /**
     * @dataProvider fixtureResponse
     */
    public function test_will_mark_fixture_as_confirmingExtraTime_when_fixture_was_concluded_less_than_25_minutes_ago(array $response): void
    {
        $response['fixture']['periods']['second'] = now()->subMinutes(50)->timestamp;

        $fixture = (new FixtureResponseJsonMapper())->toDataTransferObject($response);

        $this->assertFalse($fixture->status()->isFinished());
        $this->assertTrue($fixture->status()->code() === FixtureStatus::CONFIRMING_EXTRA_TIME);
    }

    public function fixtureResponse(): array
    {
        return [
            [json_decode(FetchFixtureResponse::json(), true)['response'][0]]
        ];
    }
}
