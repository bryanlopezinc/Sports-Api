<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamHeadToHeadResponse;

class FetchTeamHeadToHeadTest extends TestCase
{
    private function getTestRespone(int $teamOne, int $teamTwo, array $query = []): TestResponse
    {
        $parameters = array_merge([
            'team_id_1'     => $teamOne,
            'team_id_2'     => $teamTwo,
        ], $query);

        return $this->getJson(route(Name::FETCH_TEAM_HEAD_TO_HEAD, $parameters));
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $this->getTestRespone(34, 33)->assertSuccessful();
    }

    public function test_will_throw_validation_exception_when_ids_are_same(): void
    {
        $this->getTestRespone(33, 33)->assertStatus(422);
    }

    public function test_will_limit_head_to_head_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $this->getTestRespone(34, 33)->assertJsonCount(23, 'data'); // 23 head to head fixtures in headtohead json stub
        $this->getTestRespone(34, 33, ['limit' => 5])->assertJsonCount(5, 'data');
    }

    public function test_will_return_partial_response_when_needed(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $response = $this->getTestRespone(34, 33, ['fields' => 'date,status'])->assertSuccessful();

        foreach ($response->decodeResponseJson()->json('data') as $data) {
            $assert = new AssertableJsonString($data);

            $assert->assertCount(2)
                ->assertCount(2, 'attributes')
                ->assertStructure([
                    "type",
                    "attributes" => [
                        "date",
                        "status" => [
                            "info",
                            "short"
                        ]
                    ]
                ]);
        }
    }
}
