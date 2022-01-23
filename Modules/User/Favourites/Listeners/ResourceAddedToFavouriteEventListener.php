<?php

declare(strict_types=1);

namespace Module\User\Favourites\Listeners;

use Illuminate\Support\Arr;
use Module\User\Favourites\Models\FavouriteCount;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;
use Module\User\Favourites\Models\Favourite;

final class ResourceAddedToFavouriteEventListener
{
    public function handle(ResourceAddedToFavouritesEvent $event): void
    {
        Favourite::query()->create([
            'user_id' => $event->userId->toInt(),
            'record_id' => $event->recordId->value
        ]);

        $attributes = [
            'user_id' => $event->userId->toInt(),
            'count'   => 1
        ];

        $model = FavouriteCount::query()->firstOrCreate(Arr::except($attributes, 'count'), $attributes);

        if (!$model->wasRecentlyCreated) {
            $model->increment('count');
        }
    }
}
