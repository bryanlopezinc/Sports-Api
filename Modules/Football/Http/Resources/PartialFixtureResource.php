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
    public function toArray($request)
    {
        $original = (new FixtureResource($this->fixture))->toArray($request);

        $partialResourceRequest = PartialFixtureRequest::fromRequest($request, $this->filterInputName);

        if ($partialResourceRequest->isEmpty()) {
            return $original;
        }

        $customAttributes = [];

        foreach ($partialResourceRequest->all() as $key) {
            Arr::set($customAttributes, $key, Arr::get($original['attributes'], $key));
        }

        Arr::set($original, 'attributes', $customAttributes);

        if ($partialResourceRequest->wants('league')) {
            Arr::set($original, 'attributes.league', $this->getLeagueResource($this->fixture->league()));
        }

        if (!$partialResourceRequest->wants('links')) {
            Arr::forget($original, 'links');
        }

        return $original;
    }

    private function getLeagueResource(League $league): LeagueResource|PartialLeagueResource
    {
        return match ($this->leagueResource) {
            LeagueResource::class => new LeagueResource($league),
            PartialLeagueResource::class => new PartialLeagueResource($league, $this->leagueFilterInputName),
            default => throw new \Exception('Invalid league resource ' . $this->leagueResource)
        };
    }
}
