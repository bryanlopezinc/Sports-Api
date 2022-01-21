<?php

declare(strict_types=1);

namespace Module\Football\Http\FetchFixtureResource;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\Http\PartialFixtureRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\DTO\Fixture;
use Module\Football\Http\Resources\FixtureJsonResourceInterface;
use Module\Football\Prediction\Services\FetchFixturePredictionsService;

final class SetUserHasPredictionFixture extends JsonResource implements FixtureJsonResourceInterface
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
        $attributes = $this->jsonResource->toArray($request);

        $partialResource = PartialFixtureRequest::fromRequest($request, 'filter');

        $wantsPredictionStatus = $partialResource->isEmpty() ? true : $partialResource->wants('user.has_predicted');

        if (!$wantsPredictionStatus) {
            return $attributes;
        }

        Arr::set($attributes, 'user.has_predicted', $this->service->authUserHasPredictedFixture($this->getFixture()->id()));

        return $attributes;
    }

    public function getFixture(): Fixture
    {
        return $this->jsonResource->getFixture();
    }
}
