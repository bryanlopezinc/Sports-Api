<?php

declare(strict_types=1);

namespace Module\Football\Http\FetchFixtureResource;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\Http\PartialFixtureRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\DTO\Fixture;
use Module\Football\Http\Resources\FixtureJsonResourceInterface;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FetchFixturePredictionsService;
use Module\User\Predictions\Football\Prediction;

final class SetUserPrediction extends JsonResource implements FixtureJsonResourceInterface
{
    private FetchFixturePredictionsService $service;

    public function __construct(private JsonResource&FixtureJsonResourceInterface $jsonResource, FetchFixturePredictionsService $service = null)
    {
        $this->service = $service ?? app(FetchFixturePredictionsService::class);

        parent::__construct($jsonResource->getFixture());
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $fixtureId = $this->getFixture()->id();

        $attributes = $this->jsonResource->toArray($request);

        $partialResource = PartialFixtureRequest::fromRequest($request, 'filter');

        $wantsUserPrediction = $partialResource->isEmpty() ? true : $partialResource->wants('user.prediction');

        if (!$wantsUserPrediction) {
            return $attributes;
        }

        if (!$this->service->authUserHasPredictedFixture($fixtureId)) {
            return $attributes;
        }

        Arr::set($attributes, 'user.prediction', $this->transformPrediction($fixtureId));

        return $attributes;
    }

    private function transformPrediction(FixtureId $fixtureId): string
    {
        $prediction = $this->service->fetchAuthUserHasPrediction($fixtureId);

        return match (true) {
            $prediction->isAwayToWin()  => 'away2win',
            $prediction->isHomeToWin()  => 'home2win',
            $prediction->isDraw()      => 'draw'
        };
    }

    public function getFixture(): Fixture
    {
        return $this->jsonResource->getFixture();
    }
}
