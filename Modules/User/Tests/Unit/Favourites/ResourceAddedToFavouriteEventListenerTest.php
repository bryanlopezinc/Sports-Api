<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Favourites;

use App\ValueObjects\Uid;
use Tests\TestCase;
use Module\User\Favourites\Models\Favourite;
use Module\User\Favourites\Listeners\ResourceAddedToFavouriteEventListener;
use Module\User\Favourites\Models\FavouriteCount;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;
use Module\User\ValueObjects\UserId;

class ResourceAddedToFavouriteEventListenerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Favourite::truncate();
        FavouriteCount::truncate();
    }

    public function test_will_create_new_record(): void
    {
        $listener = new ResourceAddedToFavouriteEventListener;

        $listener->handle($event = new ResourceAddedToFavouritesEvent(new UserId(400), Uid::generate()));

        $this->assertDatabaseHas(new Favourite(), [
            'user_id' => $event->userId->toInt(),
            'record_id' => $event->recordId->value
        ]);

        $this->assertDatabaseHas(new FavouriteCount(), [
            'user_id' => $event->userId->toInt(),
            'count'   => 1
        ]);
    }

    public function test_will_increment_favourites_count_when_user_has_favourites(): void
    {
        $listener = new ResourceAddedToFavouriteEventListener;

        $listener->handle(new ResourceAddedToFavouritesEvent(new UserId(40), Uid::generate()));
        $listener->handle($event = new ResourceAddedToFavouritesEvent(new UserId(40), Uid::generate()));

        $this->assertDatabaseHas(new FavouriteCount(), [
            'user_id' => $event->userId->toInt(),
            'count'   => 2
        ]);
    }
}
