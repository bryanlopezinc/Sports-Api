<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamHeadToHeadResponse;

class FetchTeamHeadToHeadTest extends TestCase
{
    private function getTestRespone(int $teamOne, int $teamTwo): TestResponse
    {
        return $this->getJson(route(Name::FETCH_TEAM_HEAD_TO_HEAD, [
            'team_id_1'     => $teamOne,
            'team_id_2'     => $teamTwo,
        ]));
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $this->getTestRespone(34, 33)->assertSuccessful();
    }
}
