<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Module\User\Favourites\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Factories\TeamFactory;
use Module\User\Favourites\Models\Favourite;
use Module\User\Favourites\Models\FavouriteCount;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;

class AddFootballTeamToFavouriteTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->postJson(route(RouteName::ADD_FOOTBALL_TEAM_TO_FAVOURITES), [
            'id'        => $id
        ]);
    }

    public function test_returns_409_status_code_when_favourite_already_exists(): void
    {
        Http::fake(fn () => Http::response(FetchTeamResponse::json()));

        $team = TeamFactory::new()->toDto();
        $user = UserFactory::new()
            ->has(Factory::new()->favouriteId($team->getId()->toInt()), 'favourites')
            ->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->getTestRespone($team->getId()->toInt())->assertStatus(409);
    }

    public function test_returns_404_status_code_when_team_does_not_exists(): void
    {
        Http::fake(fn () => Http::response([], 404));

        $factory = UserFactory::new()->create();
        $team = TeamFactory::new()->toDto();

        Passport::actingAs($factory); // @phpstan-ignore-line

        $this->getTestRespone($team->getId()->toInt())->assertNotFound();

        $this->assertDatabaseMissing((new Favourite())->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'favourite_id' => $team->getId()->toInt()
            ]
        );
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchTeamResponse::json()));

        $team = TeamFactory::new()->toDto();
        $factory = UserFactory::new()->create();

        Passport::actingAs($factory); // @phpstan-ignore-line

        $this->withoutExceptionHandling()
            ->getTestRespone($team->getId()->toInt())
            ->assertCreated();

        $this->assertDatabaseHas((new Favourite)->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'favourite_id' => $team->getId()->toInt()
            ]
        );

        $this->assertDatabaseHas((new FavouriteCount())->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'count'        => 1
            ]
        );
    }

    public function test_unauthorized_user_cannot_add_favourite(): void
    {
        $this->getTestRespone(22)->assertUnauthorized();
    }
}
