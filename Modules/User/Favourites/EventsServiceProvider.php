<?php

namespace Module\User\Favourites;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;

class EventsServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ResourceAddedToFavouritesEvent::class => [
            \Module\User\Favourites\Listeners\ResourceAddedToFavouriteEventListener::class,
        ],
    ];
}
