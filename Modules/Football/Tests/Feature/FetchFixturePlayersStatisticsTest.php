<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Routes\FetchFixturePlayersStatisticsRoute;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixturePlayersStatisticsResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixturePlayersStatisticsTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixturePlayersStatisticsRoute(new FixtureId($id))
        );
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixturePlayersStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestRespone(34)
            ->assertSuccessful();
    }
}
