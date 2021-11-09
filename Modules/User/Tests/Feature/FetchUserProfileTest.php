<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Module\User\Routes\RouteName;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Module\User\Routes\UserFavouritesRoute;
use Module\User\ValueObjects\UserId;

class FetchUserProfileTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(route(RouteName::PROFILE, [
            'id' => $id
        ]));
    }

    public function test_cannot_view_user_profile_when_profile_is_private(): void
    {
        $user = UserFactory::new()->private()->create();

        $this->getTestRespone($user->id)->assertForbidden();
    }

    public function test_returns_not_found_response_when_user_does_not_exists(): void
    {
        $this->getTestRespone(UserFactory::new()->create()->id + 1)->assertNotFound(); // @phpstan-ignore-line
    }

    public function test_success_response(): void
    {
        $this->getTestRespone($id = UserFactory::new()->create()->id) // @phpstan-ignore-line
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $assert) use ($id) {
                $assert->where('data.links.favourites', (string) new UserFavouritesRoute(new UserId($id)));
                $assert->etc();
            });
    }
}
