<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Testing\TestResponse;
use Module\Football\Routes\RouteName;
use Module\User\Factories\UserFactory;
use Module\Football\Model\Comment as Model;
use Module\Football\Factories\CommentFactory;

class DeleteCommentTest extends TestCase
{
    private function getTestResponse(array $query): TestResponse
    {
        return $this->deleteJson(route(RouteName::DELETE_COMMENT), $query);
    }

    public function test_unauthorized_user_cannot_delete_comment(): void
    {
        $this->getTestResponse(['id' => 200])->assertUnauthorized();
    }

    public function test_comment_id_attribute_must_be_present(): void
    {
        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse([])->assertStatus(422)->assertJsonValidationErrorFor('id');
    }

    public function test_user_can_only_delete_own_comment(): void
    {
        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(['id' => $id = CommentFactory::new()->create()->id])->assertForbidden();

        $this->assertDatabaseHas(Model::class, ['id' => $id]);
    }

    public function test_success_response(): void
    {
        Passport::actingAs($user = UserFactory::new()->create());

        $this->getTestResponse($query = [
            'id' => $id = CommentFactory::new()->create(['commented_by_id' => $user->id])->id
        ])->assertNoContent();

        $this->getTestResponse($query)->assertNoContent();

        $this->assertDatabaseMissing(Model::class, ['id' => $id]);
    }
}
