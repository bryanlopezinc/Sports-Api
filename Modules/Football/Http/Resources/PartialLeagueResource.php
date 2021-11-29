<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\DTO\League;
use Module\Football\Http\PartialLeagueRequest;
use Illuminate\Http\Resources\Json\JsonResource;

final class PartialLeagueResource extends JsonResource
{
    public function __construct(private League $league, private string $filterInputName)
    {
        parent::__construct($league);
    }

    /**
     * @param Request $httpRequest
     * @return array<string, mixed>
     */
    public function toArray($httpRequest)
    {
        $originalResponse  = (new LeagueResource($this->league))->toArray($httpRequest);
        $request = PartialLeagueRequest::fromRequest($httpRequest, $this->filterInputName);

        $partialResponse = [];

        if (!$request->wantsPartialResponse()) {
            return $originalResponse;
        }

        $partialResponse['type'] = $originalResponse['type'];

        //Set only the required keys needed at the attributes level
        if ($request->wantsAnyOf($keys = $this->keysInTheAttributesLevel())) {
            $partialResponse['attributes'] = Arr::only($originalResponse['attributes'], array_intersect($keys, $request->all()));
        }

        if ($request->wants('links')) {
            $partialResponse['links'] =  $originalResponse['links'];
        }

        //if request needs only season (or season data) set only required season data
        if ($request->wants('season') || $request->wantsSpecificSeasonData()) {
            Arr::set($partialResponse, 'attributes.season', $this->getSeasonData($request, $originalResponse));
        }

        //if request needs only coverage (or coverage data) set only required season coverage data
        if ($request->wants('coverage') || $request->wantsSpecificCoverageData()) {
            Arr::set($partialResponse, 'attributes.season.coverage', $this->getSeasonCoverageData($request, $originalResponse));
        }

        return $partialResponse;
    }

    /**
     * @return array<string>
     */
    private function keysInTheAttributesLevel(): array
    {
        return [
            'id',
            'logo_url',
            'name',
            'country',
        ];
    }

    private function getSeasonCoverageData(PartialLeagueRequest $request, array $originalResponse): array
    {
        $originalCoverageResponse = Arr::get($originalResponse, 'attributes.season.coverage');

        if ($request->wants('coverage')) {
            return $originalCoverageResponse;
        }

        return Arr::only($originalCoverageResponse, $request->getCoverageTypes());
    }

    private function getSeasonData(PartialLeagueRequest $request, array $originalResponse): array
    {
        $originalSeasonResponse = Arr::get($originalResponse, 'attributes.season');

        if ($request->wants('season')) {
            return Arr::except($originalSeasonResponse, 'coverage');
        }

        return Arr::only($originalSeasonResponse, $request->getSeasonTypes());
    }
}
