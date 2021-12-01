<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\DTO\League;
use Module\Football\DTO\Fixture;
use Module\Football\Http\PartialFixtureRequest;
use Illuminate\Http\Resources\Json\JsonResource;

final class PartialFixtureResource extends JsonResource
{
    /**
     * The input name key name to get fixture filters from request.
     */
    public string $filterInputName;

    /**
     * The input name key name to get fixture league filters from request.
     */
    public string $leagueFilterInputName;

    /**
     * The resource class for transforming fixture league.
     */
    public string $leagueResource = LeagueResource::class;

    public function __construct(private Fixture $fixture)
    {
        parent::__construct($fixture);
    }

    public function setFilterInputName(string $name): self
    {
        $this->filterInputName = $name;

        return $this;
    }

    public function setLeagueFilterInputName(string $name): self
    {
        $this->leagueFilterInputName = $name;

        return $this;
    }

    public function withLeagueResource(string $resource): self
    {
        $this->leagueResource = $resource;

        return $this;
    }

    /**
     * @param Request $httpRequest
     * @return array<string, mixed>
     */
    public function toArray($httpRequest)
    {
        $originalResponse  = (new FixtureResource($this->fixture))->toArray($httpRequest);
        $request = PartialFixtureRequest::fromRequest($httpRequest, $this->filterInputName);

        $partialResponse = [];

        if (!$request->wantsPartialResponse()) {
            return $originalResponse;
        }

        $partialResponse['type'] = $originalResponse['type'];

        if ($request->wantsAnyOf($keys = $this->keysInTheAttributesLevel())) {
            $partialResponse['attributes'] = Arr::only($originalResponse['attributes'], array_intersect($keys, $request->all()));
        }

        if ($request->wants('links')) {
            $partialResponse['links'] =  $originalResponse['links'];
        }

        if ($request->wants('venue')) {
            Arr::set($partialResponse, 'attributes.has_venue_info', Arr::get($originalResponse, 'attributes.has_venue_info'));
        }

        if ($request->wants('league')) {
            Arr::set($partialResponse, 'attributes.league', $this->getLeagueResource($this->fixture->league()));
        }

        if ($request->wants('winner')) {
            Arr::set($partialResponse, 'attributes.has_winner', Arr::get($originalResponse, 'attributes.has_winner'));
        }

        if ($request->wants('score')) {
            Arr::set($partialResponse, 'attributes.score_is_available', Arr::get($originalResponse, 'attributes.score_is_available'));
        }

        if ($request->wantsSpecificPeriodGoalsData()) {
            Arr::set($partialResponse, 'attributes.period_goals', $this->getPeriodGoalsData($request, $originalResponse));
        }

        return $partialResponse;
    }

    private function getLeagueResource(League $league): LeagueResource|PartialLeagueResource
    {
        return match ($this->leagueResource) {
            LeagueResource::class => new LeagueResource($league),
            PartialLeagueResource::class => new PartialLeagueResource($league, $this->leagueFilterInputName),
            default => throw new \Exception('Invalid league resource ' . $this->leagueResource)
        };
    }

    /**
     * @return array<string>
     */
    private function keysInTheAttributesLevel(): array
    {
        return [
            'id',
            'referee',
            'date',
            'minutes_elapsed',
            'status',
            'teams',
            'venue',
            'score',
            'winner',
            'period_goals'
        ];
    }

    private function getPeriodGoalsData(PartialFixtureRequest $request, array $originalResponse): array
    {
        $periodGoals = Arr::get($originalResponse, 'attributes.period_goals');

        $requestedPeriodGoalsTypes = Arr::only($periodGoals, $request->getPeriodGoalsTypes());

        //The Attributes in the period goals meta array
        $periodGoalsMetaData = [];

        //A map of period goals keys and corresponding meta data keys
        $map = [
            'first_half'    => 'has_first_half_score',
            'second_half'   => 'has_full_time_score',
            'extra_time'    => 'has_extra_time_score',
            'penalty'       => 'has_penalty_score'
        ];

        foreach ($requestedPeriodGoalsTypes as $key => $value) {
            $metaKeyName = $map[$key];

            $periodGoalsMetaData[$metaKeyName] = $periodGoals['meta'][$metaKeyName];
        }

        return array_merge($requestedPeriodGoalsTypes, ['meta' => $periodGoalsMetaData]);
    }
}
