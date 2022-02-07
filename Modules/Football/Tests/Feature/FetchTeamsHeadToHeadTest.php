<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamHeadToHeadResponse;

class FetchTeamsHeadToHeadTest extends TestCase
{
    private function getTestResponse(int $teamOne, int $teamTwo, array $query = []): TestResponse
    {
        $parameters = array_merge([
            'team_id_1' => $this->hashId($teamOne),
            'team_id_2' => $this->hashId($teamTwo),
        ], $query);

        return $this->getJson(route(RouteName::TEAMS_H2H, $parameters));
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $this->withoutExceptionHandling()
            ->getTestResponse(34, 33)
            ->assertSuccessful()
            ->assertHeader('max-age');
    }

    public function test_will_return_404_status_code_when_team_ids_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(341, 133)->assertNotFound();
    }

    public function test_will_throw_validation_exception_when_ids_are_same(): void
    {
        $this->getTestResponse(33, 33)->assertStatus(422);
    }

    public function test_will_limit_head_to_head_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $this->getTestResponse(34, 33)->assertJsonCount(23, 'data'); // 23 head to head fixtures in headtohead json stub
        $this->getTestResponse(34, 33, ['limit' => 5])->assertJsonCount(5, 'data');
    }

    public function test_will_return_partial_response_when_needed(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchTeamHeadToHeadResponse::json()));

        $response = $this->getTestResponse(34, 33, ['fields' => 'date,status'])->assertSuccessful();

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
