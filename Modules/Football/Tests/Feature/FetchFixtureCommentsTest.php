<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use App\Utils\PaginationData;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Factories\CommentFactory;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixtureCommentsTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function getTestResponse(array $query): TestResponse
    {
        if (isset($query['id'])) {
            $query['id'] = $this->hashId($query['id']);
        }

        return $this->getJson(route(RouteName::FIXTURE_COMMENTS, $query));
    }

    public function test_attributes_must_be_valid(): void
    {
        Http::fakeSequence()->whenEmpty(Http::response(FetchFixtureResponse::json()));

        $this->getTestResponse([])->assertStatus(422)->assertJsonValidationErrorFor('id');

        $this->getTestResponse([
            'page' => -1,
            'id'   => 2
        ])->assertStatus(422)->assertJsonValidationErrorFor('page');

        $this->getTestResponse([
            'page' => PaginationData::MAX_PAGE + 1,
            'id'   => 2
        ])->assertStatus(422)->assertJsonValidationErrorFor('page');

        $this->getTestResponse([
            'per_page' => PaginationData::MIN_PER_PAGE - 1,
            'id'   => 2
        ])->assertStatus(422)->assertJsonValidationErrorFor('per_page');

        $this->getTestResponse([
            'per_page' => PaginationData::MAX_PER_PAGE + 1,
            'id'   => 2
        ])->assertStatus(422)->assertJsonValidationErrorFor('per_page');
    }

    public function test_will_return_404_status_code_when_fixture_is_invalid(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse([
            'id' => 200,
        ])->assertNotFound();
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()->whenEmpty(Http::response(FetchFixtureResponse::json()));

        CommentFactory::new()->create([
            'commentable_id' => $id = 3090
        ]);

        $this->getTestResponse(['id' => 2])->assertSuccessful();

        $response = $this->getTestResponse([
            'page'     => 1,
            'id'       => $id,
            'per_page' => PaginationData::MAX_PER_PAGE
        ])
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonCount(2, 'links')
            ->assertJsonCount(4, 'meta');

        $response->assertJsonStructure(['first', 'prev'], $response->json('links'));
        $response->assertJsonStructure([
            'current_page', 'path', 'per_page', 'has_more_pages'
        ], $response->json('meta'));
    }
}
