<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixtureTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string)new FetchFixtureRoute(new FixtureId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestRespone(12)
            ->assertSuccessful()
            ->assertHeader('max-age');
    }
}
