<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use App\ValueObjects\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Routes\FetchLeagueFixturesByDateRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;

class FetchLeagueFixturesByDateTest extends TestCase
{
    private function getTestResponse(): TestResponse
    {
        $route = new FetchLeagueFixturesByDateRoute(
            new LeagueId(22),
            new Season(2020),
            new Date(today()->toDateString())
        );

        return $this->getJson((string) $route);
    }

    /**
     * @test
     */
    public function FetchLeagueFixturesByDate_success_response(): void
    {
        Http::fakeSequence()->push(FetchLeagueFixturesByDateResponse::json());

        $this->withoutExceptionHandling()->getTestResponse()->assertSuccessful();
    }
}
