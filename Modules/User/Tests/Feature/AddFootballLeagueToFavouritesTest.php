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
use Module\Football\Factories\LeagueFactory;
use Module\User\Favourites\Models\Favourite;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\User\Favourites\Models\FavouriteCount;

class AddFootballLeagueToFavouritesTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->postJson(route(RouteName::ADD_FOOTBALL_LEAGUE_TO_FAVOURITES), [
            'id'        => $id
        ]);
    }

    public function test_returns_409_status_code_when_favourite_already_exists(): void
    {
        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $league = LeagueFactory::new()->toDto();
        $user = UserFactory::new()
            ->has(Factory::new()->footballLeagueType()->favouriteId($league->getId()->toInt()), 'favourites')
            ->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->getTestRespone($league->getId()->toInt())->assertStatus(409);
    }

    public function test_returns_404_status_code_when_league_does_not_exists(): void
    {
        Http::fake(fn () => Http::response([], 404));

        $factory = UserFactory::new()->create();
        $league = LeagueFactory::new()->toDto();

        Passport::actingAs($factory); // @phpstan-ignore-line

        $this->getTestRespone($league->getId()->toInt())
            ->assertNotFound();

        $this->assertDatabaseMissing((new Favourite())->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'favourite_id' => $league->getId()->toInt()
            ]
        );
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $league = LeagueFactory::new()->toDto();
        $factory = UserFactory::new()->create();

        Passport::actingAs($factory); // @phpstan-ignore-line

        $this->withoutExceptionHandling()
            ->getTestRespone($league->getId()->toInt())
            ->assertCreated();

        $this->assertDatabaseHas((new Favourite)->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'favourite_id' => $league->getId()->toInt()
            ]
        );

        $this->assertDatabaseHas((new FavouriteCount())->getTable(),
            [
                'user_id'      => $factory->id, // @phpstan-ignore-line
                'count'        => 1
            ]
        );
    }

    public function test_unauthorized_user_cannot_add_league_to_favourite(): void
    {
        $this->getTestRespone(22)->assertUnauthorized();
    }
}
