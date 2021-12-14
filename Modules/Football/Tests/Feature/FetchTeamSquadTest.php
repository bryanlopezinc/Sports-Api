<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamSquadResponse;

class FetchTeamSquadTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(route(Name::FETCH_TEAM_SQUAD, [
            'id'     => $id,
        ]));
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchTeamSquadResponse::json()));

        $this->withoutExceptionHandling()
            ->getTestResponse(900)
            ->assertSuccessful()
            ->assertHeader('max-age')
            ->assertJsonStructure([
                'type',
                'team_id',
                'total',
                'squad' => [
                    'goal_keepers',
                    'defenders',
                    'midfielders',
                    'attackers'
                ]
            ]);
    }
}
