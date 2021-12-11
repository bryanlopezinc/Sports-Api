<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\FixturePredictions;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Http\Resources\FixturePredictionsResource;
use Module\User\Predictions\Football\FetchFixturePredictionsService;
use Module\Football\Http\Requests\FetchFixturePredictionsRequest as Request;

/**
 * @see \Module\Football\Providers\FixturePredictionsContextualBindingServiceProvider
 */
final class FetchFixturePredictionsController
{
    public function __construct(private FetchFixtureService $service, private FetchFixturePredictionsService $fixturePredictions)
    {
    }

    public function __invoke(Request $request): FixturePredictionsResource
    {
        $fixtureId = FixtureId::fromRequest($request);

        //Will also throw exception if fixture is not valid
        $fixture = $this->service->fetchFixture($fixtureId);

        return new FixturePredictionsResource(
            new FixturePredictions($fixture, $this->fixturePredictions->for($fixtureId))
        );
    }
}
