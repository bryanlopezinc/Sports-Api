<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;

class FetchFixturesByDateTest extends TestCase
{
    private function getTestResponse(): TestResponse
    {
        return $this->getJson(route(RouteName::FIXTURES_BY_DATE, [
            'date'  => today()->toDateString()
        ]));
    }

    /**
     * @test
     */
    public function FetchFixturesByDate_success_response(): void
    {
        Http::fakeSequence()->push(FetchFixtureByDateResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse()
            ->assertSuccessful();
    }
}
