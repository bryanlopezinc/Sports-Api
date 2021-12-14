<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Module\Football\FixturePredictions;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Http\Resources\FixturePredictionsResource;
use Module\Football\Http\Traits\ValidatesFixtureId;
use Module\User\Predictions\Football\FetchFixturePredictionsService;

/**
 * @see \Module\Football\Providers\FixturePredictionsContextualBindingServiceProvider
 */
final class FetchFixturePredictionsController
{
    use ValidatesFixtureId;

    public function __construct(private FetchFixtureService $service, private FetchFixturePredictionsService $fixturePredictions)
    {
    }

    public function __invoke(Request $request): FixturePredictionsResource
    {
        $request->validate([
            'id' => $this->rules()
        ]);

        $fixtureId = FixtureId::fromRequest($request);

        //Will also throw exception if fixture is not valid
        $fixture = $this->service->fetchFixture($fixtureId);

        return new FixturePredictionsResource(
            new FixturePredictions($fixture, $this->fixturePredictions->for($fixtureId))
        );
    }
}
