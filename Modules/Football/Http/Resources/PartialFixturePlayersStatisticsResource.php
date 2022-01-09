<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\DTO\PlayerStatistics;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Http\PartialFixturePlayersStatisticsResourceRequest;

final class PartialFixturePlayersStatisticsResource extends JsonResource
{
    public function __construct(private readonly PlayerStatistics $statistics)
    {
        parent::__construct($statistics);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $original = (new FixturePlayerStatisticsResource($this->statistics))->toArray($request);

        $partialResourceRequest = PartialFixturePlayersStatisticsResourceRequest::fromRequest($request);

        if ($partialResourceRequest->isEmpty()) {
            return $original;
        }

        $result = [];

        foreach ($partialResourceRequest->all() as $key) {
            Arr::set($result, $key, Arr::get($original['attributes'], $key));
        }

        Arr::set($original, 'attributes', $result);

        return $original;
    }
}
