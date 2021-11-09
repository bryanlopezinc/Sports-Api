<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureLineUpRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureLineUpResponse;

class FetchFixtureLineUpTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
           (string) new FetchFixtureLineUpRoute(new FixtureId($id))
        );
    }

    /**
     * @test
     */
    public function FetchFixtureLineUp_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureLineUpResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestRespone(34)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data'  => [
                    'type',
                    'fixture_id',
                    'line_up'   => [
                        'home'  => [
                            'team',
                            'formation',
                            'starting_XI',
                            'subs',
                            'coach',
                        ],
                        'away'  => [
                            'team',
                            'formation',
                            'starting_XI',
                            'subs',
                            'coach',
                        ]
                    ]
                ]
            ]);
    }
}
