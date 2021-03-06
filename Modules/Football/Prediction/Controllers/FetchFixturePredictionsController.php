<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\Prediction\FixturePredictionsResource;
use Module\Football\Prediction\PredictionsQueryResult;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Prediction\Services\FetchFixturePredictionsService;

final class FetchFixturePredictionsController
{
    public function __construct(private FetchFixtureService $service, private FetchFixturePredictionsService $fixturePredictions)
    {
    }

    public function __invoke(Request $request): FixturePredictionsResource
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()]
        ]);

        $fixtureId = FixtureId::fromRequest($request);

        //Will also throw exception if fixture is not valid
        $fixture = $this->service->fetchFixture($fixtureId);

        return new FixturePredictionsResource(
            new PredictionsQueryResult($fixture, $this->fixturePredictions->for($fixtureId))
        );
    }
}
