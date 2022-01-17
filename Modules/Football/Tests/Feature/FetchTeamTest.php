<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Routes\FetchTeamRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;

class FetchTeamTest extends TestCase
{
    private function getTestResponse(int $teamId): TestResponse
    {
        return $this->getJson(
            (string)new FetchTeamRoute(new TeamId($teamId))
        );
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchTeamResponse::json()));

        $this->withoutExceptionHandling()
            ->getTestResponse(400)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes'        => [
                        'id',
                        'name',
                        'logo_url',
                        'year_founded',
                        'country',
                        'venue',
                    ],
                    'links'     => [
                        'self'
                    ]
                ]
            ]);
    }
}
