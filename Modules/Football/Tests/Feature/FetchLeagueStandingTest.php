<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueStandingResponse;

class FetchLeagueStandingTest extends TestCase
{
    private function getTestRespone(int $id, int $season): TestResponse
    {
        return $this->getJson(route(Name::FETCH_LEAGUE_STANDING, [
            'league_id'     => $id,
            'season'        => $season
        ]));
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchLeagueStandingResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureByDateResponse::json());

        $this->getTestRespone(400, 2018)->assertSuccessful();
    }
}
