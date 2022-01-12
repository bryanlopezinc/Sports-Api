<?php

namespace Module\User\Predictions\Football\Providers;

use Module\User\Predictions\Football\FixturePredictedEvent;
use Module\User\Predictions\Football\Listeners\RemoveUserPredictionRecord;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        FixturePredictedEvent::class => [
            RemoveUserPredictionRecord::class,
        ],
    ];
}
