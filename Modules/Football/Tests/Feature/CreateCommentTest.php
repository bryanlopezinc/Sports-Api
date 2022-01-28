<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Model\Comment as Model;
use Module\Football\Repository\CommentsRepository;
use Module\Football\Routes\RouteName;
use Module\User\Factories\UserFactory;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\ValueObjects\Comment;

class CreateCommentTest extends TestCase
{
    private function getTestResponse(array $query): TestResponse
    {
        if (isset($query['fixture_id'])) {
            $query['fixture_id'] = $this->hashId($query['fixture_id']);
        }

        return $this->postJson(route(RouteName::CREATE_COMMENT), $query);
    }

    public function test_unauthorized_user_cannot_create_comment(): void
    {
        $this->getTestResponse([
            'fixture_id' => 200,
            'comment' => 'lets go manchester!'
        ])->assertUnauthorized();
    }

    public function test_comment_attribute_must_be_present(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(['fixture_id' => 200])->assertStatus(422)->assertJsonValidationErrorFor('comment');
    }

    public function test_fixture_id_attribute_must_be_present(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(['comment' => 'lets go manchester!'])->assertStatus(422)->assertJsonValidationErrorFor('fixture_id');
    }

    public function test_comment_attribute_cannot_be_empty(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse([
            'fixture_id' => 200,
            'comment' => '  '
        ])->assertStatus(422)->assertJsonValidationErrorFor('comment');
    }

    public function test_comment_cannot_exceed_max_length(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse([
            'fixture_id' => 200,
            'comment' => str_repeat('B', Comment::MAX + 1)
        ])->assertStatus(422)->assertJsonValidationErrorFor('comment');
    }

    public function test_will_return_404_status_code_when_fixture_is_invalid(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse([
            'fixture_id' => 200,
            'comment' => 'lets go manchester!'
        ])->assertNotFound();
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        Passport::actingAs($user = UserFactory::new()->create());

        $this->getTestResponse([
            'fixture_id' => 209,
            'comment' => 'lets go manchester!'
        ])->assertCreated();

        $this->assertDatabaseHas(new Model(), [
            'commentable_id'   => 209,
            'comment'          => 'lets go manchester!',
            'commented_by_id'  => $user->id,
            'commentable_type' => CommentsRepository::COMMENTABLE_TYPE
        ]);
    }
}
