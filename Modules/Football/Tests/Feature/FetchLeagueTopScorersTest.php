<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Routes\FetchLeagueTopScorersRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTopScorersResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;

class FetchLeagueTopScorersTest extends TestCase
{
    private function getTestRespone(int $id, int $season): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueTopScorersRoute(new LeagueId($id), new Season($season))
        );
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchTopScorersResponse::json())
            ->push(FetchLeagueFixturesByDateResponse::json());

        $this->withoutExceptionHandling()
            ->getTestRespone(34, 2020)
            ->assertSuccessful();
    }

    public function test_throws_exception_when_top_scorers_is_not_yet_available(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(status: 204);

        $this->getTestRespone(34, 2020)
            ->assertNoContent();
    }
}
