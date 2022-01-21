<?php

namespace Module\Football\Prediction\Providers;

use Module\Football\Prediction\FixturePredictedEvent;
use Module\Football\Prediction\Listeners\RemoveUserPredictionRecord;
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
