<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Favourites;

use Tests\TestCase;
use App\ValueObjects\Uid;
use App\Utils\PaginationData;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Module\Football\Favourites\Models\Favourite;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Favourites\Repository;
use Module\Football\ValueObjects\LeagueId;
use Module\User\Favourites\FavouritesRepository;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;

class FavouritesRepositoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_will_return_correct_data(): void
    {
        $repository = new FavouritesRepository;

        (new Repository)->addTeam(new TeamId(20), $userId = new UserId(22), $uid1 = Uid::generate());
        (new Repository)->addLeague(new LeagueId(24), $userId, $uid2 = Uid::generate());

        event(new ResourceAddedToFavouritesEvent($userId, $uid1));
        event(new ResourceAddedToFavouritesEvent($userId, $uid2));

        $this->assertCount(2, $result = $repository->getFavourites($userId, new PaginationData()));

        $this->assertEquals($result->getCollection()->first()->toArray(), [
            "favourite_id" => 20,
            "type" => Favourite::TEAM_TYPE
        ]);

        $this->assertEquals($result->getCollection()->last()->toArray(), [
            "favourite_id" => 24,
            "type" => Favourite::LEAGUE_TYPE
        ]);
    }
}
