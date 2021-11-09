<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureStatisticsRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureStatisticsResponse;

class FetchFixtureStatisticsTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureStatisticsRoute(new FixtureId($id))
        );
    }

    /**
     * @test
     */
    public function FetchFixtureStatistics_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestRespone(34)
            ->assertSuccessful();
    }
}
