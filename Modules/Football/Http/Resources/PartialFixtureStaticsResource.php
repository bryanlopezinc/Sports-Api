<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\DTO\FixtureStatistics;

final class PartialFixtureStaticsResource extends JsonResource
{
    /**
     * @param array<FixtureStatistics> $statistics
     */
    public function __construct(private array $statistics)
    {
        parent::__construct($statistics);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $original = (new FixtureStatisticsResource($this->statistics))->toArray($request);

        if ($request->missing('fields')) {
            return $original;
        }

        $requestedFields = explode(',', $request->input('fields'));

        $callback = function (array $statistics) use ($requestedFields): array {
            $statistics['stats'] = array_intersect_key($statistics['stats'], array_flip($requestedFields));

            return $statistics;
        };

        $original['stats'] = array_map($callback, $original['stats']);

        return $original;
    }
}
