<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Favourites\AddLeagueRoute;
use Module\Football\Favourites\Models\Favourite;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\ValueObjects\LeagueId;
use Module\User\Favourites\Models\FavouriteCount;

class AddFootballLeagueToFavouritesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Favourite::truncate();
    }

    private function getTestRespone(int $id): TestResponse
    {
        return $this->postJson(
            (new AddLeagueRoute(new LeagueId($id)))->toString()
        );
    }

    public function test_returns_409_status_code_when_favourite_already_exists(): void
    {
        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->getTestRespone(90)->assertSuccessful();
        $this->getTestRespone(90)->assertStatus(409);
    }

    public function test_returns_404_status_code_when_league_does_not_exists(): void
    {
        Http::fake(fn () => Http::response([], 404));

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->getTestRespone(40)->assertNotFound();

        $this->assertDatabaseMissing(new Favourite(),
            [
                'user_id'      => $user->id, // @phpstan-ignore-line
                'favourite_id' => 40,
                'type'         => Favourite::LEAGUE_TYPE
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
                'favourite_id' => $league->getId()->toInt(),
                'type'         => Favourite::LEAGUE_TYPE
            ]
        );

        $this->assertDatabaseHas((new FavouriteCount())->getTable(),
            [
                'user_id' => $factory->id, // @phpstan-ignore-line
                'count'   => 1
            ]
        );
    }

    public function test_unauthorized_user_cannot_add_league_to_favourite(): void
    {
        $this->getTestRespone(22)->assertUnauthorized();
    }
}
